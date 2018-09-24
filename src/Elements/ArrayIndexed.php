<?php

namespace Okneloper\Forms\Elements;

use Okneloper\Forms\ElementInterface;
use Okneloper\Forms\Filters\ArrayFilter;
use Okneloper\Forms\Filters\StringSanitizeFilter;
use Okneloper\Forms\Observers\Observable;

/**
 * @author Aleksey Lavrinenko <aleksey.lavrinenko@mtcmedia.co.uk>
 * Created on 06.11.2017.
 */
class ArrayIndexed implements ElementInterface
{
    use Observable;

    /**
     * List of values
     * @var array
     */
    protected $values;

    /**
     * Element to be multiplied
     * @var ElementInterface
     */
    protected $element;

    /**
     * Array of generated Elements
     * @var ElementInterface[]
     */
    protected $elements;

    /**
     * Element group (array) name
     * @var string
     */
    public $name;

    /**
     * elements getter
     * @return ElementInterface[]
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * Returns element by it's index
     * @param $index
     * @return ElementInterface
     */
    public function el($index)
    {
        return $this->elements[$index];
    }

    /**
     * ArrayIndexed constructor.
     * @param ElementInterface $element
     */
    public function __construct(ElementInterface $element)
    {
        $this->element = $element;
        $this->name = $element->name;

        // set default empty value to generate one element
        $this->val(['']);
    }

    public function val($value = null)
    {
        if ($value === null) {
            return $this->values;
        }

        $oldValue = $this->values;

        $this->values = (array)$value;

        $this->generateElements();

        $this->triggerValueChanged($oldValue);

        return $this;
    }

    public function forceValue($value)
    {
        return $this->val($value);
    }


    /**
     * @param $name
     * @param null $value
     * @return $this
     */
    public function attr($name, $value = null)
    {
        return $this->element->attr($name, $value);
    }

    public function generateElements()
    {
        $this->elements = array_map(function ($value, $key) {
            $element = clone $this->element;
            $element->attr('id', $element->attr('id') . '_' . $key);

            $element->val($value);

            return $element;
        }, $this->values, array_keys($this->values));
    }

    public function getDefaultFilter()
    {
        return new ArrayFilter(new StringSanitizeFilter());
    }
}
