<?php
/**
 * @author Aleksey Lavrinenko
 * @version 28.12.2015.
 */
namespace Okneloper\Forms\Validation\Respect;

use Okneloper\Forms\Form;
use Okneloper\Forms\Validation\ValidatorInterface;
use Respect\Validation\Validator;

class RespectValidator implements ValidatorInterface
{
    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @var Form
     */
    protected $form;

    protected $formValidator;

    public function __construct(Form $form, RespectFormValidator $formValidator)
    {
        $this->form = $form;
        $this->formValidator = $formValidator;
    }

    /**
     * @param Form $form
     * @return bool
     */
    public function validateForm(Form $form)
    {
        return $this->formValidator->validateForm($form);
    }

    public function getErrorMessages()
    {
        return $this->formValidator->getErrorMessages();
    }
}
