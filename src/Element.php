<?php

namespace Okneloper\Forms;

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

    static public function setDefaultAttrib($name, $value)
    {
        static::$defaultAttributes[$name] = $value;
    }

    static public function factory($type, $name, $label = null, $attributes = [])
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
        return $this->attributes + ['name' => $this->nameAttribute];
    }

    public function __construct($name, $label = null, $attributes = [])
    {
        $this->nameAttribute = $name;
        $name = $this->cleanName($name);
        $this->attr('name', $name);

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
            switch($name) {
                case 'type':
                    return $this->$name;
                    break;

                case 'name':
                    return $this->nameAttribute;
                    break;
            }
            return isset($this->attributes[$name]) ? $this->attributes[$name] : null;
        }

        switch ($name) {
            case 'label':
                $this->label = $value;
                break;

            case 'name':
                // prepare parameter for a 'change' event
                $eventParams = ['oldValue' => $this->name];

                $this->name = $value;
                break;

            default:
                // prepare parameter for a 'change' event
                $eventParams = [
                    'oldValue' => isset($this->attributes[$name]) ? $this->attributes[$name] : null,
                ];

                // set new value
                $this->attributes[$name] = $value;
                break;
        }

        // trigger event if there are event params to pass
        if (isset($eventParams)) {
            // trigger the event
            if ($name === 'value') {
                $this->trigger('valueChanged', $eventParams);
            } else {
                $this->trigger('attrChanged', $eventParams + ['attr' => $name]);
            }
        }

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
        return '<input ' . $this->buildAttrs($this->getAttributes() + ['type' => $this->type]) . '>';
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
        // HTML5 syntax for boolean attributes
        if ($value === true) {
            return $name;
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

    public function val($value = null)
    {
        if ($value === null) {
            return $this->attr('value');
        }
        $this->attr('value', $value);
        return $this;
    }

    public function __toString()
    {
        return $this->render();
    }

    public function observe($observer)
    {
        $this->observers[] = $observer;
    }

    public function trigger($eventType, $params = [])
    {
        foreach ($this->observers as $observer) {
            if (method_exists($observer, $eventType)) {
                $observer->$eventType($this, $params);
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
}
