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
    }
}
