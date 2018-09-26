<?php

namespace Okneloper\Forms\Validation\Illuminate;

use Illuminate\Translation\ArrayLoader;
use Illuminate\Translation\Translator;
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

    /**
     * Classname or resolver Closure.
     *
     * @var string|\Closure
     */
    protected $override;

    public $reportIlluminateErrors;

    /**
     * IlluminateValidatorResolver constructor. If $override is a string, it should contain a class name of a class
     * that extend Validator with unoverrided contructor. If you want more control, then you can  pass a closure
     * in $override that will be set as resolver on the Factory.
     *
     * @param \Illuminate\Container\Container $app
     * @param \Okneloper\Forms\Validation\Illuminate\RuleSetInterface|\Closure|array $rules
     * @param string|\Closure $override
     */
    public function __construct(\Illuminate\Container\Container $app, $rules, $reportIlluminateErrors = true, $override = null)
    {
        $this->app   = $app;

        if (!($rules instanceof RuleSetInterface) && !($rules instanceof \Closure) && !is_array($rules)) {
            throw new \BadMethodCallException('$rules must be an array or an instance of Okneloper\Forms\Validation\Illuminate\RuleSetInterface or Closure');
        }
        $this->rules = $rules;

        $this->reportIlluminateErrors = $reportIlluminateErrors;

        if (is_string($override)) {
            $this->override = function ($translator, $data, $rules, $messages, $customAttributes) use ($override) {
                return new $override($translator, $data, $rules, $messages, $customAttributes);
            };
        } else {
            $this->override = $override;
        }
    }

    /**
     * @param \Okneloper\Forms\Form $form
     * @return \Okneloper\Forms\Validation\ValidatorInterface
     */
    public function resolve(\Okneloper\Forms\Form $form)
    {
        if ($this->rules instanceof RuleSetInterface) {
            $rules = $this->rules->bootValidatorRules($form);
        } elseif ($this->rules instanceof \Closure) {
            $rules = call_user_func($this->rules);
        } else {
            // $this->rules is an array
            $rules = $this->rules;
        }
        $messages = $form->bootErrorMessages();

        $laravelValidator = $this->makeValidator($form, $form->modelToArray(), $rules, $messages);

        if ($this->rules instanceof ComplexRuleSetInterface) {
            $this->rules->addMoreValidatorRules($form, $laravelValidator);
        }

        return new IlluminateValidator($form, $laravelValidator, $this->reportIlluminateErrors);
    }


    /**
     * @return \Illuminate\Validation\Factory
     */
    public function getValidationFactory()
    {
        if (isset($this->app['validator'])) {
            return $this->app['validator'];
        }

        if (isset($this->app['translator'])) {
            $translator = $this->app['translator'];
        } else {
            // create a dummy translator
            $translator = new Translator(new ArrayLoader(), 'en');
        }

        $factory = new Factory($translator, $this->app);

        if ($this->override !== null) {
            $factory->resolver($this->override);
        }

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
