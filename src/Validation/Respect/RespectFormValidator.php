<?php

namespace Okneloper\Forms\Validation\Respect;

use Okneloper\Forms\Form;

interface RespectFormValidator
{
    public function validateForm(Form $form);

    public function getErrorMessages();
}
