<?php

namespace Okneloper\Forms\Validation;

use Okneloper\Forms\Form;

/**
 * Interface ValidatorInterface
 * @package Okneloper\Forms\Validation
 */
interface ValidatorInterface
{
    /**
     * @param Form $form
     * @return bool
     */
    public function validateForm(Form $form);

    public function getErrorMessages();
}
