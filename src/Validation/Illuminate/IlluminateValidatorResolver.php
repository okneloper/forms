<?php

namespace Okneloper\Forms\Validation\Illuminate;

use Illuminate\Validation\Factory;
use Okneloper\Forms\Form;
use Okneloper\Forms\Validation\ValidatorResolverInterface;
use Okneloper\Forms\Validation\Illuminate\RuleSetInterface;
use Symfony\Component\Translation\IdentityTranslator;

/**
 * @author Aleksey Lavrinenko
 * @version 26.12.2015.
 */
class IlluminateValidatorResolver implements ValidatorResolverInterface
{
    /**
     * @var \Illuminate\Container\Container
     */
    protected $app;

    /**
     * @var RuleSetInterface
     */
    protected $rules;

    public function __construct(\Illuminate\Container\Container $app, RuleSetInterface $rules)
    {
        $this->app   = $app;
        $this->rules = $rules;
    }

    /**
     * @param \Okneloper\Forms\Form $form
     * @return \Okneloper\Forms\Validation\ValidatorInterface
     */
    public function resolve(\Okneloper\Forms\Form $form)
    {
        $rules = $this->rules->bootValidatorRules($form);
        $messages = $form->bootErrorMessages();

        $laravelValidator = $this->makeValidator($form, $form->modelToArray(), $rules, $messages);

        return new IlluminateValidator($form, $laravelValidator);
    }


    /**
     * @return \Illuminate\Validation\Factory
     */
    public function getValidationFactory()
    {
        $factory = new Factory(new IdentityTranslator(), $this->app);
        return $factory;
    }


    /**
     * Override this function to make a suitable validator
     * @param Form $form
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @param array $customAttributes
     * @return \Illuminate\Validation\Validator
     */
    public function makeValidator(
        Form $form,
        array $data,
        array $rules,
        array $messages = array(),
        array $customAttributes = array()
    ) {
        $factory = $this->getValidationFactory();

        if (!$messages) {
            $messages = $form->bootErrorMessages();
        }

        return $factory->make($data, $rules, $messages, $customAttributes);
    }
}
