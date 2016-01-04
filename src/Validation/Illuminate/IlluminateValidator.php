<?php
/**
 * @author Aleksey Lavrinenko
 * @version 28.12.2015.
 */
namespace Okneloper\Forms\Validation\Illuminate;

use Illuminate\Validation\Validator;
use Okneloper\Forms\Form;
use Okneloper\Forms\Validation\ValidationException;
use Okneloper\Forms\Validation\ValidatorInterface;

class IlluminateValidator implements ValidatorInterface
{
    /**
     * @var Validator
     */
    protected $validator;

    /**
     * @var Form
     */
    protected $form;

    public function __construct(Form $form, Validator $validator)
    {
        $this->validator = $validator;
        $this->form = $form;
    }

    /**
     * @param Form $form
     * @return bool
     */
    public function validateForm(Form $form)
    {
        if ($form != $this->form) {
            throw new ValidationException("Cannot validate different form with the same validator.");
        }

        return $this->validator->passes();
    }

    public function getErrorMessages()
    {
        $allMessages = $this->validator->messages()->getMessages();
        foreach ($allMessages as $fieldName => &$messages) {
            foreach ($messages as &$message) {
                $search = str_replace('_', ' ', $fieldName);
                $message = str_replace('{' . $search . '}', $this->form->el($fieldName)->label, $message);
            }

            $messages = implode(', ', $messages);
        }

        return $allMessages;
    }
}
