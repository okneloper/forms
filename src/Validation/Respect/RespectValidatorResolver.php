<?php
/**
 * @author Aleksey Lavrinenko
 * @version 28.12.2015.
 */
namespace Okneloper\Forms\Validation\Respect;

use Okneloper\Forms\Validation\ValidatorResolverInterface;

class RespectValidatorResolver implements ValidatorResolverInterface
{
    /**
     * @var RespectFormValidator
     */
    protected $formValidator;

    public function __construct(RespectFormValidator $formValidator)
    {
        $this->formValidator = $formValidator;
    }

    /**
     * @param \Okneloper\Forms\Form $form
     * @return \Okneloper\Forms\Validation\ValidatorInterface
     */
    public function resolve(\Okneloper\Forms\Form $form)
    {
        return new RespectValidator($form, $this->formValidator);
    }
}
