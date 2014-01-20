<?php

namespace JAC\Payment\StripeBundle\Client;

use JAC\Payment\StripeBundle\Client\Authentication\AuthenticationStrategyInterface;

/**
 * Class Client
 *
 * Interacts directly with the Stripe API
 *
 * @package JAC\Payment\StripeBundle\Client
 */
class Client
{
    const API_VERSION = '2013-10-29';

    /**
     * @var Authentication\AuthenticationStrategyInterface
     */
    protected $authenticationStrategy;

    /**
     * @var bool
     */
    protected $isDebug;

    /**
     * @param Authentication\AuthenticationStrategyInterface $authenticationStrategy
     * @param $isDebug
     */
    public function __construct(AuthenticationStrategyInterface $authenticationStrategy, $isDebug)
    {
        $this->authenticationStrategy = $authenticationStrategy;
        $this->isDebug = !!$isDebug;

        $this->authenticationStrategy->authenticate();
        \Stripe::setApiVersion(self::API_VERSION);
    }

    /**
     * @param array $parameters
     * @return mixed
     */
    public function authorize(array $parameters)
    {
        return \Stripe_Charge::create($parameters);
    }

    /**
     * @param array $parameters
     * @return mixed
     */
    public function capture(array $parameters)
    {
        $charge = Stripe_Charge::retrieve($parameters['charge_id']);

        return $charge->capture();
    }

    /**
     * @param array $parameters
     * @return mixed
     */
    public function charge(array $parameters)
    {
        return \Stripe_Charge::create($parameters);
    }

    /**
     * @param array $parameters
     * @return mixed
     */
    public function credit(array $parameters)
    {
        $charge = Stripe_Charge::retrieve($parameters['charge_id']);

        return $charge->refund();
    }

}