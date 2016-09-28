<?php

namespace Okneloper\Forms\Observers;

use Okneloper\Forms\Element;
use Okneloper\Forms\Form;

class Observer implements ValueObserver
{
    /**
     * @var Form
     */
    protected $form;

    public function __construct(Form $form)
    {
        $this->form = $form;
    }

    public function valueChanged(Element $element, $oldValue)
    {
        // do nothing until the model is assigned (bound)
        if (!$this->form->modelAssigned()) {
            return;
        }

        $this->form->updateModel($element->name, $element->val());
        // if value on the model has been transformed in some way, assign it back to element
        $modelValue = $this->form->getModel()->{$element->name};
        if ($modelValue != $element->val()) {
            $element->val($modelValue);
        }
    }
}
