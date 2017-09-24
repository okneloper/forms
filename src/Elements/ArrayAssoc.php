<?php

namespace Okneloper\Forms\Elements;

use Okneloper\Forms\ElementInterface;
use Okneloper\Forms\Filters\VoidFilter;
use Okneloper\Forms\Model;
use Okneloper\Forms\Observers\Observable;

class ArrayAssoc implements ElementInterface
{
    use Observable;

    public $name;

    /**
     * @var ElementInterface[]
     */
    protected $elements;

    /**
     * @return ElementInterface[]
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * ArrayAssoc constructor.
     * @param $name
     */
    public function __construct($name)
    {
        $this->name = $name;
    }


    public function addElement(ElementInterface $element)
    {
        $elName = $element->name;
        $element->nameAttribute = "{$this->name}[{$elName}]";

        $this->elements[$elName] = $element;

        foreach ($this->observers as $observer) {
            #$element->subscribe($observer);
        }
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
            $model = new Model();
            foreach ($this->elements as $element) {
                $model->{$element->name} = $element->val();
            }
            return $model->toArray();
        }

        $oldValue = $this->val();

        foreach ((array)$value as $key => $val) {
            if (isset($this->elements[$key])) {
                $this->elements[$key]->val($val);
            }
        }

        $this->triggerValueChanged($oldValue);
    }

    /**
     * jQuery-like attribute getter/setter
     *
     * @param $name
     * @param null $value
     * @return $this
     */
    public function attr($name, $value = null)
    {
        throw new \BadMethodCallException("Not implemented");
    }

    /**
     * Get element by it's name.
     * @param $name
     * @return ElementInterface
     * @throws \Exception
     */
    public function el($name)
    {
        if (isset($this->elements[$name])) {
            return $this->elements[$name];
        }

        if (strpos($name, '.') !== false) {
            list($arrayName, $elName) = explode('.', $name);
            return $this->el($arrayName)->el($elName);
        }

        throw new \Exception("Element [$name] not found on the " . __CLASS__);
    }

    public function getDefaultFilter()
    {
        return new VoidFilter();
    }
}
