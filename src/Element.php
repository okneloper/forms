<?php

namespace Okneloper\Forms;
use Okneloper\Forms\Filters\StringSanitizeFilter;
use Okneloper\Forms\Observers\Observable;

/**
 * Class Element
 *
 * @event valueChanged
 * @event attrChanged
 *
 * @property $id
 * @property $type
 *
 * @method disabled($value = null)
 * @method placeholder($value = null)
 *
 * @package Okneloper\Forms
 */
class Element implements ElementInterface
{
    use Observable;

    static protected $defaultAttributes = [];

    static protected $knownAttributes = [
        'disabled',
        'placeholder',
        'readonly',
    ];

    public static function setDefaultAttrib($name, $value)
    {
        static::$defaultAttributes[$name] = $value;
    }

    public static function factory($type, $name, $label = null, $attributes = [])
    {
        $class = __NAMESPACE__ . '\\Elements\\' . ucfirst($type);
        return new $class($name, $label, $attributes);
    }

    protected $attributes = [];

    protected $type;

    public $name;

    /**
     * Full name attribute including []
     *
     * @var string
     */
    public $nameAttribute;

    public $label;

    protected $value;

    public function __get($prop)
    {
        if (isset($this->$prop)) {
            return $this->$prop;
        }
        return $this->attr($prop);
    }

    public function __set($prop, $value)
    {
        if ($prop === 'type') {
            throw new \Exception("[type] property is read only");
        }
        if (isset($this->$prop)) {
            $this->$prop = $value;
        }
        $this->attr($prop, $value);
    }

    public function getAttributes()
    {
        // name is set to a public property for convenient use in twig templates (magic methods
        // don't as one would expect
        return ['name' => $this->nameAttribute] + $this->attributes;
    }

    public function __construct($name, $label = null, $attributes = [])
    {
        $this->setName($name);

        if (!isset($attributes['id'])) {
            $attributes['id'] = $name;
        }

        $this->label = $label;

        $attributes = $attributes + static::$defaultAttributes;

        foreach ($attributes as $attrName => $attrValue) {
            $this->attr($attrName, $attrValue);
        }
    }

    /**
     * @param $name
     * @param null $value
     * @return $this
     */
    public function attr($name, $value = null)
    {
        if ($value === null) {
            return $this->getAttribute($name);
        }

        if ($name === 'name') {
            return $this->setName($value);
        }

        if ($name === 'value') {
            return $this->val($value);
        }

        $this->setAttribute($name, $value);

        return $this;
    }


    /**
     * @param $className
     * @return $this
     */
    public function addClass($className)
    {
        $current = $this->attr('class');
        foreach (explode(' ', $className) as $className) {
            if (strpos($current, $className) !== false) {
                continue;
            }
            $current .= " $className";
        }

        $this->attr('class', $current);
        return $this;
    }

    public function render()
    {
        $attributes = ['type' => $this->type] + $this->getAttributes();
        $attributes = array_merge($attributes, ['value' => $this->value]);
        return '<input ' . $this->buildAttrs($attributes) . '>';
    }


    /**
     * Build up the attributes
     *
     * @return string
     */
    protected function buildAttrs($attrs)
    {
        $built = array();
        foreach ($attrs as $name => $value) {
            $built[] = $this->buildAttr($name, $value);
        }
        return implode(' ', $built);
    }

    /**
     * Build an attribute
     *
     * @param string $name
     * @return string
     */
    protected function buildAttr($name, $value)
    {
        #$value = $this->attr($name);
        // HTML5 syntax for boolean attributes: attribute name or nothing
        if (is_bool($value)) {
            return $value ? $name : '';
        }
        // regular syntax
        return sprintf('%s="%s"', $name, $name === 'value' ? $this->escape($value) : $value);
    }
    /**
     * Escape an attributes value
     *
     * @param string $value
     * @return string
     */
    protected function escape($value)
    {
        return htmlspecialchars($value, ENT_QUOTES, 'UTF-8');
    }

    /**
     * jQuery-like value getter/setter
     *
     * @param null $value
     * @return $this|mixed current value or $this
     */
    public function val($value = null)
    {
        if ($value === null) {
            return $this->value;
        }

        if ($this->disabled() || $this->readonly()) {
            // do not assign values for disabled elements, these are supposed to not be present among the form data
            // readonly ones are... read-only, so no action required here either
            return $this;
        }

        $this->setNewValue($value);

        return $this;
    }

    /**
     * Sets element value
     * @param $value
     * @return $this
     */
    public function setValue($value)
    {
        $this->val($value);
        return $this;
    }

    /**
     * Set the value even is the element is readonly/disabled (for setting default values)
     * @param $value
     * @return $this
     */
    public function forceValue($value)
    {
        $this->setNewValue($value);
        return $this;
    }

    public function __toString()
    {
        return $this->render();
    }

    public function cleanName($name)
    {
        return preg_replace('#^([^\[]+)\[.+$#', '$1', $name);
    }

    public function data($key, $value)
    {
        return $this->attr("data-$key", $this->anythingToString($value));
    }

    public function disable()
    {
        return $this->attr('disabled', true);
    }

    public function anythingToString($anything)
    {
        if (is_array($anything) || is_object($anything)) {
            $anything = json_encode($anything);
            return $this->escape($anything);
        }
        return $anything;
    }

    /**
     * @param $name
     * @return string|null
     */
    protected function getAttribute($name)
    {
        switch ($name) {
            case 'type':
                return $this->$name;
                break;

            case 'name':
                return $this->nameAttribute;
                break;

            case 'value':
                return $this->value;
                break;
        }
        return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
    }

    protected function setAttribute($name, $value)
    {
        $oldValue = $this->getAttribute($name);

        // for boolean attributes, as per HTML5 spec, we just unset the attribute if false is passed as value
        if ($value === false) {
            unset($this->attributes[$name]);
        } else {
            // otherwise set the new value
            $this->attributes[$name] = $value;
        }

        $this->triggerAttributeChanged($name, $oldValue);
    }

    public function setName($name)
    {
        $this->nameAttribute = $name;
        $name = $this->cleanName($name);
        $this->name = $name;
    }

    public function getDefaultFilter()
    {
        // this is the most commonly used filter, so apply it by default
        return [new StringSanitizeFilter()];
    }

    public function __call($name, $arguments)
    {
        if (in_array($name, static::$knownAttributes)) {
            if (!$arguments) {
                return $this->attr($name);
            } else {
                return $this->attr($name, $arguments[0]);
            }
        }

        throw new \BadMethodCallException("$name function not defined on " . get_class($this));
    }

    public function __clone()
    {
        $this->observers = [];
    }

    /**
     * @param $value
     */
    protected function setNewValue($value)
    {
        $oldValue = $this->value;

        $this->value = $value;

        $this->triggerValueChanged($oldValue);
    }
}
