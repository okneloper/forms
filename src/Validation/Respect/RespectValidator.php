<?php
/**
 * @author Aleksey Lavrinenko
 * @version 28.12.2015.
 */
namespace Okneloper\Forms\Validation\Respect;

use Okneloper\Forms\Element;
use Okneloper\Forms\Form;
use Okneloper\Forms\Validation\ValidatorInterface;
use Respect\Validation\Validator;
use Respect\Validation\Validator as v;

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

    protected $rules = [];

    protected $errorMessages = [];

    /**
     * RespectValidator constructor.
     * @param Form $form
     * @param $rules
     */
    public function __construct(Form $form, $rules)
    {
        $this->form = $form;
        $this->rules = $rules;
    }

    /**
     * @param Form $form
     * @return bool
     */
    public function validateForm(Form $form)
    {
        $errors = [];
        $elements = $form->getElements();

        foreach ($elements as $element) { /* @var $element Element */
            if (isset($this->rules[$element->name])) {
                $respect = $this->rules[$element->name];
                if (!$respect->validate($element->val())) {
                    $errors[$element->name] = true;
                }
            }
        }

        if ($errors) {
            $allErrorMessages = $form->bootErrorMessages();
            foreach ($errors as $field => &$error) {
                $label = $elements[$field]->label ?: $field;
                $error = isset($allErrorMessages[$field]) ? $allErrorMessages[$field] : "{$label} is not valid";
            }
        }

        $this->errorMessages = $errors;

        return empty($errors);
    }

    public function getErrorMessages()
    {
        return $this->errorMessages;
    }
}
