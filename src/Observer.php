<?php

namespace Okneloper\Forms;

class Observer
{
    /**
     * @var Form
     */
    protected $form;

    public function __construct(Form $form)
    {
        $this->form = $form;
    }

    public function valueChanged(Element $element, $params)
    {
        $this->form->updateModel($element->name, $element->val());
        // if value on the model has been transformed in some way, assign it back to element
        $modelValue = $this->form->getModel()->{$element->name};
        if ($modelValue != $element->val()) {
            $element->val($modelValue);
        }
    }
}
