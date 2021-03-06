<?php

namespace Okneloper\Forms\Elements;

use Okneloper\Forms\Element;
use Okneloper\Forms\Exceptions\NotArrayException;

/**
 *
 * Temporary element for use with radio buttons and rendering manually
 *
 * Class Choice
 * @package Okneloper\Forms\Elements
 */
class Choice extends Element
{
    protected $type = 'choice';

    protected $options;

    public function __construct($name, $label = null, $attributes = [])
    {
        if (isset($attributes['options'])) {
            $this->options = $attributes['options'];
            unset($attributes['options']);
        } else {
            $this->options = [];
        }

        parent::__construct($name, $label, $attributes);
    }

    public function render()
    {
        throw new \BadMethodCallException("Choice element cannot be rendered");
    }

    /**
     * Set or get dropdown options.
     *
     * @param null $options
     * @param \Closure|null $extractOption
     * @return $this|array
     */
    public function options($options = null, \Closure $extractOption = null)
    {
        if ($options === null) {
            return $this->options;
        }

        if ($extractOption !== null) {
            $cleanOptions = [];
            foreach ($options as $option) {
                $thisOption = $extractOption($option);
                if (!is_array($thisOption)) {
                    throw new \RuntimeException("Options closure must return an array, " . gettype($thisOption) . " returned");
                }
                if (count($thisOption) == 1) {
                    // accept arrays like [value => text]
                    $value = key($thisOption);
                    $text = current($thisOption);
                } else {
                    // or like [value, text]
                    list($value, $text) = $thisOption;
                }
                $cleanOptions[$value] = $text;
            }
            $options = $cleanOptions;
        }

        if (!is_array($options)) {
            throw new NotArrayException($options, "Choice element");
        }

        // we cannot use array_merge on $options from a database like [id] => value, because [id] is numeric key
        // and as per array_merge documentation, number indexes will be reset
        $this->options = array_replace($this->options, $options);

        return $this;
    }

    public function clearOptions()
    {
        $this->options = [];
        return $this;
    }

    public function listValues($delimiter = ',')
    {
        return implode($delimiter, array_keys($this->options));
    }

    /**
     * Get the display value of the option of current value
     * @return string
     */
    public function valueText()
    {
        $value = $this->val();
        if ($value === null || !isset($this->options[$value])) {
            return '';
        }
        return $this->options[$value];
    }
}
