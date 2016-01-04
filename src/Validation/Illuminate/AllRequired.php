<?php
/**
 * @author Aleksey Lavrinenko
 * @version 28.12.2015.
 */
namespace Okneloper\Forms\Validation\Illuminate;

use Okneloper\Forms\Form;

class AllRequired implements RuleSetInterface
{
    public function bootValidatorRules(Form $form)
    {
        $rules = [];
        foreach ($form->getElements() as $el)
        {
            $rules[$el->name] = 'required';
        }

        return $rules;
    }
}
