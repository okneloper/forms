<?php

namespace Okneloper\Forms;

use Oknedev\Forms\Validator;
use Okneloper\Forms\Elements\Checkbox;
use Okneloper\Forms\Filters\ArrayFilter;
use Okneloper\Forms\Filters\FilterInterface;
use Okneloper\Forms\Filters\NativeFilter;
use Okneloper\Forms\Validation\ValidatorResolverInterface;
use Okneloper\Forms\Validation\ValidationException;
use Okneloper\Forms\Validation\ValidatorInterface;

class Form
{
    protected $elements = [];

    protected $model;

    protected $values = [];

    protected $observer;

    protected $submitted = false;

    protected $errors = [];

    /**
     * @var ValidatorResolverInterface
     */
    protected $validatorResolver;

    /**
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * @return ValidatorInterface
     */
    public function getValidator()
    {
        return $this->validator;
    }

    /**
     * @return ValidatorResolverInterface
     */
    public function getValidatorResolver()
    {
        return $this->validatorResolver;
    }

    /**
     * @param ValidatorResolverInterface $validatorResolver
     */
    public function setValidatorResolver(ValidatorResolverInterface $validatorResolver)
    {
        $this->validatorResolver = $validatorResolver;
    }

    /**
     * @return boolean
     */
    public function isSubmitted()
    {
        return $this->submitted;
    }


    public function getElements()
    {
        return $this->elements;
    }

    public function __construct($model = [])
    {
        $this->observer = new Observer($this);

        $this->bind($model);

        $this->initElements();
    }

    public function initElements()
    {
        // override this method
    }

    /**
     * @param $type
     * @param null $name
     * @param array $attribs
     * @param null $label
     * @return Element
     * @throws \Exception
     */
    public function add($type, $name = null, $attribs = [], $label = null)
    {
        if ($type instanceof Element) {
            $this->addElement($type);
            return $type;
        } else {
            if (!$name) {
                throw new \Exception("Name not specified");
            }
            $el = Element::factory($type, $name, $attribs, $label);
            $this->addElement($el);
            return $el;
        }
    }

    public function addElement(Element $element)
    {
        // store element
        $this->elements[$element->name] = $element;


        // assign default value from bound model
        if ($this->modelAssigned() && isset($this->model->{$element->name})) {
            $element->val($this->model->{$element->name});
        }

        // add observer of "Value changed" event
        $element->observe($this->observer);
    }

    /**
     * @param $name
     * @return Element
     */
    public function el($name)
    {
        if (!isset($this->elements[$name])) {
            throw new \Exception("Element [$name] not found on the form");
        }
        return $this->elements[$name];
    }

    public function has($name)
    {
        return isset($this->elements[$name]);
    }

    /**
     * Get value of the element with name
     *
     * @param $name
     * @return string
     */
    public function val($name)
    {
        return $this->el($name)->val();
        // @todo or shoud this be
        // return $this->model->$name;
        // ?
    }

    /**
     * @param array|object $model
     */
    public function bind($model)
    {
        if (is_array($model)) {
            $model = new Model($model);
        }
        $this->model = $model;

        // assign values to already added elements
        foreach ($this->elements as $el) {
            $el->val($model->{$el->name});
        }
    }

    /**
     * Check if model has been assigned
     * @return bool
     */
    public function modelAssigned()
    {
        return isset($this->model);
    }

    /**
     * @return \ArrayObject|object
     */
    public function getModel()
    {
        return $this->model;
    }

    public function updateModel($name, $value)
    {
        $this->model->$name = $value;
    }

    public function submit($data)
    {
        $this->submitted = true;

        $data = $this->applyFilters($data);

        foreach ($this->elements as $el) {
            $value = isset($data[$el->name]) ? $data[$el->name] : null;
            $el->val($value);
            /*
            if ($el instanceof Checkbox) {
                if (isset($data[$el->name]) && $data[$el->name] == $el->attr('value')) {
                    $el->attr('checked', true);
                } else {
                    $el->attr('checked', false);
                }

            } elseif (isset($data[$el->name])) {
                $el->val($data[$el->name]);
            }
            */
        }
    }

    public function applyFilters($data)
    {
        $filters = $this->bootFilters();

        foreach ($data as $k => &$v)
        {
            if (isset($filters[$k])) {
                $filter = $filters[$k];
                if (is_object($filter)) {
                    if (!($filter instanceof FilterInterface)) {
                        throw new \Exception("Filter object should implement FilterInterface.");
                    }
                } else {
                    // cast to array
                    if (!is_array($filter)) {
                        $filter = [$filter];
                    }

                    // add second argument
                    if (!isset($filter[1])) {
                        $filter[1] = null;
                    }

                    $filter = new NativeFilter($filter[0], $filter[1]);
                }
            } else {
                $filter = new NativeFilter(FILTER_SANITIZE_STRING);
                if (is_array($v)) {
                    $filter = new ArrayFilter($filter);
                }
            }

            $v = $filter->filter($k, $v);
        }

        return $data;
    }


    public function isValid()
    {
        if (!$this->validator) {
            $this->validator = $this->bootValidator();
        }

        $passes = $this->validator->validateForm($this);

        $this->errors = $this->validator->getErrorMessages();

        return $passes;
    }

    public function errorMessages()
    {
        if (!$this->validator) {
            throw new \BadMethodCallException("Error message are only available after the form has been submitted and validated");
        }

        return $this->validator->getErrorMessages();
    }


    /**
     * Override this function to provide a suitable validator
     *
     * @return Validator
     */
    public function bootValidator()
    {
        if (!$this->validatorResolver) {
            throw ValidationException::noValidatorResolver();
        }

        $validator = $this->validatorResolver->resolve($this);

        if (!($validator instanceof ValidatorInterface)) {
            throw ValidationException::invalidValidatorClass();
        }

        return $validator;
    }

    public function bootErrorMessages()
    {
        return [
            'required' => '{:attribute} is required',
            'phone'    => 'Please enter a valid phone number',
            'accepted' => 'Please accept the terms',
            'in'       => 'Please chose one of the options',
            'numeric'  => '{:attribute} should be a decimal number',
            'min'      => 'Minimum {:attribute} is :min',
            'date_format' => 'The date you have provided seems to be invalid',
        ];
    }

    public function bootFilters()
    {
        return [];
    }



    /**
     * Get plain array of model data for validation
     *
     * @param null $model
     * @return array
     */
    public function modelToArray($model = null)
    {
        if ($model === null) {
            $model = $this->model;
        }

        $array = [];
        foreach ($this->elements as $el)
        {
            $array[$el->name] = $model->{$el->name};
        }

        return $array;
    }

    public function error($field)
    {
        return isset($this->errors[$field]) ? $this->errors[$field] : null;
    }
}
