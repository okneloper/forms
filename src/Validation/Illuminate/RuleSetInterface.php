<?php
/**
 * @author Aleksey Lavrinenko
 * @version 28.12.2015.
 */
namespace Okneloper\Forms\Validation\Illuminate;

use Okneloper\Forms\Form;

interface RuleSetInterface
{
    public function bootValidatorRules(Form $form);
}
