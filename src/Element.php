<?php

namespace Okneloper\Forms;
use Okneloper\Forms\Filters\NativeFilter;
use Okneloper\Forms\Filters\StringSanitizeFilter;
use Okneloper\Forms\Observers\AttributeObserver;
use Okneloper\Forms\Observers\ValueObserver;

/**
 * Class Element
 *
 * @event valueChanged
 * @event attrChanged
 *
 * @property $id
 * @property $type
 *
 * @method placeholder($value)
 *
 * @package Okneloper\Forms
 */
class Element
{
    static protected $defaultAttributes = [];

    public static function setDefaultAttrib($name, $value)
    {
        static::$defaultAttributes[$name] = $value;
    }

    public static function factory($type, $name, $label = null, $attributes = [])
    {
        $class = __NAMESPACE__ . '\\Elements\\' . ucfirst($type);
        return new $class($name, $label, $attributes);
    }

    protected $observers = [];

    protected $attributes = [];

    protected $type;

    public $name;

    /**
     * Fill name attribute including []
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
     * @return mixed|$this current value or $this
     */
    public function val($value = null)
    {
        if ($value === null) {
            return $this->value;
        }

        $oldValue = $this->value;

        $this->value = $value;

        $this->triggerValueChanged($oldValue);

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

    public function __toString()
    {
        return $this->render();
    }

    public function subscribe($observer)
    {
        $this->observers[] = $observer;
    }

    protected function triggerValueChanged($oldValue)
    {
        foreach ($this->observers as $observer) {
            if ($observer instanceof ValueObserver) {
                $observer->valueChanged($this, $oldValue);
            }
        }
    }

    protected function triggerAttributeChanged($name, $oldValue)
    {
        foreach ($this->observers as $observer) {
            if ($observer instanceof AttributeObserver) {
                $observer->attributeChanged($this, $name, $oldValue);
            }
        }
    }

    public function cleanName($name)
    {
        return preg_replace('#^([^\[]+)\[.+$#', '$1', $name);
    }

    public function __call($func, $args)
    {
        $value = isset($args[0]) ? $args[0] : null;
        return $this->attr($func, $value);
    }

    public function data($key, $value)
    {
        return $this->attr("data-$key", $this->anythingToString($value));
    }

    /**
     * Get 'disabled' state of the element
     * @return Element
     */
    public function disabled($value = null)
    {
        return $this->attr('disabled');
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
}
