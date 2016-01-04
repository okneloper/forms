<?php

namespace Okneloper\Forms\Validation;

use Okneloper\Forms\Form;

/**
 * @author Aleksey Lavrinenko
 * @version 26.12.2015.
 */
interface ValidatorResolverInterface
{
    /**
     * @param \Okneloper\Forms\Form $form
     * @return \Okneloper\Forms\Validation\ValidatorInterface
     */
    public function resolve(Form $form);
}