<?php

namespace Okneloper\Forms\Validation\Illuminate;

use Illuminate\Validation\Validator;
use Okneloper\Forms\Form;

interface ComplexRuleSetInterface
{
    public function addMoreValidatorRules(Form $form, Validator $validator);
}
