<?php
/**
 * @author Aleksey Lavrinenko
 * @version 26.12.2015.
 */
namespace Okneloper\Forms;

class FormWithResolver
{

    /**
     * @var ValidatorResolverInterface
     */
    protected $validatorResolver;

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
    public function setValidatorResolver($validatorResolver)
    {
        $this->validatorResolver = $validatorResolver;
    }

}
