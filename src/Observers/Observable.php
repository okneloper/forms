<?php

namespace Okneloper\Forms\Observers;

trait Observable
{
    /**
     * @var array
     */
    protected $observers = [];

    /**
     * Subscribe an observer
     *
     * @param $observer
     */
    public function subscribe($observer)
    {
        $this->observers[] = $observer;
    }

    /**
     * Returns true if element has observers.
     * Primarily intended for testing
     *
     * @return bool
     */
    public function hasObservers()
    {
        return count($this->observers) > 0;
    }

    /**
     * Notify ValueObservers of a changed value
     *
     * @param mixed $oldValue
     */
    protected function triggerValueChanged($oldValue)
    {
        foreach ($this->observers as $observer) {
            if ($observer instanceof ValueObserver) {
                $observer->valueChanged($this, $oldValue);
            }
        }
    }

    /**
     * Notify AttributeObserver of a changed attribute
     *
     * @param string $name     Attribute name
     * @param mixed  $oldValue Previous value
     */
    protected function triggerAttributeChanged($name, $oldValue)
    {
        foreach ($this->observers as $observer) {
            if ($observer instanceof AttributeObserver) {
                $observer->attributeChanged($this, $name, $oldValue);
            }
        }
    }
}
