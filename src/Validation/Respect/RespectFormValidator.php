<?php
/**
 * @author Aleksey Lavrinenko
 * @version 28.12.2015.
 */

namespace Okneloper\Forms\Validation\Respect;


use Okneloper\Forms\Form;

interface RespectFormValidator
{
    public function validateForm(Form $form);

    public function getErrorMessages();
}