<?php

namespace JAC\Payment\StripeBundle\Client;

use JAC\Payment\StripeBundle\Client\Authentication\AuthenticationStrategyInterface;

Class Client
{
    const API_VERSION = '2013-10-29';

    protected $authenticationStrategy;
    protected $isDebug;

    /**
     * @param JAC\Payment\StripeBundle\Client\Authentication\AuthenticationStrategyInterface $authenticationStrategy
     * @param $isDebug
     */
    public function __construct(AuthenticationStrategyInterface $authenticationStrategy, $isDebug)
    {
        $this->authenticationStrategy = $authenticationStrategy;
        $this->isDebug = !!$isDebug;

        $this->authenticationStrategy->authenticate();
        \Stripe::setApiVersion(self::API_VERSION);
    }

    public function authorize(array $parameters)
    {
        return \Stripe_Charge::create($parameters);
    }

    public function capture(array $parameters)
    {
        $charge = Stripe_Charge::retrieve($parameters['charge_id']);
        return $charge->capture();
    }

    public function charge(array $parameters)
    {
        return \Stripe_Charge::create($parameters);
    }

    public function credit(array $parameters)
    {
        $charge = Stripe_Charge::retrieve($parameters['charge_id']);
        return $charge->refund();
    }

}