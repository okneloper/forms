<?php
/*
 * This file is part of the Forms package.
 *
 * (c) Aleksey Lavrinenko <okneloper@gmail.com>
 */
namespace Okneloper\Forms;

use Okneloper\Forms\Observers\Observer;
use Okneloper\Forms\Validator;
use Okneloper\Forms\Filters\ArrayFilter;
use Okneloper\Forms\Filters\FilterInterface;
use Okneloper\Forms\Filters\NativeFilter;
use Okneloper\Forms\Validation\ValidatorResolverInterface;
use Okneloper\Forms\Validation\ValidationException;
use Okneloper\Forms\Validation\ValidatorInterface;

/**
 * Class Form. Represents a form that can be submitted and validated.
 *
 * @package Okneloper\Forms
 */
class Form
{
    /**
     * @var \Closure
     */
    protected static $moreErrorMessages = [];

    /**
     * @param \Closure|array $moreErrorMessages
     */
    public static function addMoreErrorMessages($moreErrorMessages)
    {
        static::$moreErrorMessages[] = $moreErrorMessages;
    }

    /**
     * @param \Closure $moreErrorMessages
     */
    public static function setMoreErrorMessages($moreErrorMessages)
    {
        static::$moreErrorMessages = $moreErrorMessages;
    }

    /**
     * Form action attribute
     * @var string
     */
    protected $action = '';

    /**
     * Form elements
     * @var array
     */
    protected $elements = [];

    /**
     * Form model. Defaults to a new instance of Okneloper\Forms\Model. Can be any object, form values will be
     * assigned to public properties.
     * @var Model|mixed
     */
    protected $model;

    /**
     * Observes for changes on the element values to keep the model up to date.
     * @var Observer
     */
    protected $observer;

    /**
     * True when the form was submitted (submit() was called)
     * @var bool
     */
    protected $submitted = false;

    /**
     * Form errors after last validation.
     * @var array
     */
    protected $errors = [];

    /**
     * The ValidatorResolverInterface implementation
     * @var ValidatorResolverInterface
     */
    protected $validatorResolver;

    /**
     * The ValidatorInterface implementation
     * @var ValidatorInterface
     */
    protected $validator;

    /**
     * Filters to be applied on form submission.
     * @var array<FilterInterface>
     */
    protected $filters = [];

    /**
     * @return string
     */
    public function getAction()
    {
        return $this->action;
    }

    /**
     * @param string $action
     * @return Form
     */
    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }

    /**
     * Get resolved validator
     * @return ValidatorInterface
     */
    public function getValidator()
    {
        if (!$this->validator) {
            throw new \BadMethodCallException("Validator for this form is not ready yet. You should call \$form->isValid() first");
        }
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

    /**
     * @return array
     */
    public function getErrors()
    {
        return $this->errors;
    }

    /**
     * @return array
     */
    public function getFilters()
    {
        return $this->filters;
    }

    /**
     * @param array $filters
     * @return Form
     */
    public function setFilters($filters)
    {
        $this->filters = $filters;
        return $this;
    }

    /**
     * Form constructor.
     * @param array|object $model Model to bind
     */
    public function __construct($model = [])
    {
        $this->observer = new Observer($this);

        $this->initElements();

        $this->bind($model);
    }

    /**
     * Override this method to initialize elements when extending the form
     */
    public function initElements()
    {
        // override this method
    }

    /**
     * Universal Add function.
     * Add an element to the form. If $type is a string, it will be used as class name in one of the registered
     * namespaces (currently only Okneloper\Forms\Elements) along with other constructor arguments.
     * If an instance of Element is passed, the element is added and other arguments are ignored.
     *
     * @param string|Element $type
     * @param null $name
     * @param null $label
     * @param array $attribs
     * @return Element
     * @throws \Exception
     */
    public function add($type, $name = null, $label = null, $attribs = [])
    {
        if ($type instanceof Element) {
            $this->addElement($type);
            return $type;
        } else {
            if (!$name) {
                throw new \Exception("Name not specified");
            }
            $el = Element::factory($type, $name, $label, $attribs);
            $this->addElement($el);
            return $el;
        }
    }

    /**
     * Add an instance of Element to the form.
     * @param Element $element
     */
    public function addElement(Element $element)
    {
        // store element
        $this->elements[$element->name] = $element;

        // assign default value from bound model
        if ($this->modelAssigned() && isset($this->model->{$element->name})) {
            $element->val($this->model->{$element->name});
        }

        // add observer of "Value changed" event
        $element->subscribe($this->observer);
    }

    /**
     * Get element by it's name.
     * @param $name
     * @return Element
     * @throws \Exception
     */
    public function el($name)
    {
        if (!isset($this->elements[$name])) {
            throw new \Exception("Element [$name] not found on the form");
        }
        return $this->elements[$name];
    }

    /**
     * Returns true if form has element with this name.
     * @param $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->elements[$name]);
    }

    /**
     * Returns value of the element with name $name.
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
     * Bind a model to the form
     * @param array|object $model
     */
    public function bind($model)
    {
        // default to an empty array
        if ($model === null) {
            $model = [];
        }

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
     * Returns true if model has been assigned.
     * @return bool
     */
    public function modelAssigned()
    {
        return isset($this->model);
    }

    /**
     * @return \Model|object
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * Update a value on the model.
     * @param $key
     * @param $value
     */
    public function updateModel($key, $value)
    {
        $this->model->$key = $value;
    }

    /**
     * 'Submit' the form - assign the data coming from the submitted frontend form.
     * @param $data
     * @throws \Exception
     */
    public function submit($data)
    {
        $this->submitted = true;

        $data = $this->applyFilters($data);

        foreach ($this->elements as $el) { /* @var $el Element */
            // do not assign values for disabled elements, these are supposed to not be present among the form data
            if ($el->disabled()) {
                continue;
            }

            // buttons don't provide any values, so skip those as well
            if ($el instanceof Button) {
                continue;
            }

            $value = isset($data[$el->name]) ? $data[$el->name] : '';
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

    /**
     * Applies filters to the data and returns filtered data.
     * @param $data
     * @return mixed
     * @throws \Exception
     */
    public function applyFilters($data)
    {
        $filters = $this->bootFilters();

        foreach ($this->elements as $el) {
            /* @var $el Element */

            $name = $el->name;
            $value = isset($data[$name]) ? $data[$name] : null;

            if (isset($filters[$name])) {
                $filter = $filters[$name];
            } else {
                $filter = $el->getDefaultFilter();
                if (is_array($value)) {
                    $filter = new ArrayFilter($filter);
                }
            }

            $data[$name] = $this->applyFilter($filter, $name, $value);
        }

        return $data;
    }


    /**
     * Apply filter or array of filters to a form value
     *
     * @param $filter
     * @param $key
     * @param $value
     * @return mixed|string
     * @throws \Exception
     */
    protected function applyFilter($filter, $key, $value)
    {
        if (is_array($filter)) {
            // call the function recursively to apply every filter i the array
            foreach ($filter as $singleFilter) {
                $value = $this->applyFilter($singleFilter, $key, $value);
            }
            return $value;
        } else {
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

            return $filter->filter($key, $value);
        }
    }

    /**
     * Validates the form and returns true if the submitted data is valid.
     * @return bool
     * @throws ValidationException
     */
    public function isValid()
    {
        if (!$this->validator) {
            $this->validator = $this->bootValidator();
        }

        $passes = $this->validator->validateForm($this);

        $this->errors = $this->validator->getErrorMessages();

        return $passes;
    }

    /**
     * Resolves end returns a validator.
     * @return \Okneloper\Forms\Validator
     * @throws ValidationException
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
        $messages = [
            'required' => '{:attribute} is required',
            'phone'    => '{:attribute} must be a valid phone number',
            'accepted' => 'Please accept the terms',
            'in'       => 'Please chose one of the options for {:attribute}',
            'numeric'  => '{:attribute} should be a decimal number',
            'min'      => 'Minimum {:attribute} length is :min',
            'date_format' => '{:attribute} is not in the required date format',
            'date'     => '\'{value}\' could not be transformed to a date',
            'email'    => '{value} is not a valid email address',
        ];

        // merge all the messages that were added overriding default messages if overriding messages provided
        foreach ($this::$moreErrorMessages as $moreMessages) {
            if ($moreMessages instanceof \Closure) {
                $moreMessages = $moreMessages();
            }
            $messages = array_merge($messages, $moreMessages);
        }

        return $messages;
    }

    public function bootFilters()
    {
        return $this->filters;
    }

    /**
     * Get plain array of model data for validation
     * @param null $model
     * @return array
     */
    public function modelToArray($model = null)
    {
        if ($model === null) {
            $model = $this->model;
        }

        $array = [];
        foreach ($this->elements as $el) {
            // ignore buttons as their values don't represent actual data
            if ($el instanceof Button) {
                continue;
            }

            $array[$el->name] = $model->{$el->name};
            if ($array[$el->name] instanceof \DateTime && $el instanceof Date) {
                $array[$el->name] = $array[$el->name]->format($el->getInputFormat());
            }
        }

        return $array;
    }

    /**
     * Returns error for the field name of null if no errors were registered for the field.
     * If optional $newMessage is provided, sets the message as the field error and returns the form itself
     * @param $field
     * @param null $newMessage
     * @return null
     */
    public function error($field, $newMessage = null)
    {
        if ($newMessage === null) {
            return isset($this->errors[$field]) ? $this->errors[$field] : null;
        }
        $this->errors[$field] = $newMessage;
        return $this;
    }
}
