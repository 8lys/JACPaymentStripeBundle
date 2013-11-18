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

        \Stripe::setApiVersion(self::API_VERSION);
    }

    /**
     * @param $amount
     * @param $currency
     * @param null $customer
     * @param null $card
     * @param array $optionalParameters
     * @return array
     */
    public function charge($amount, $currency, $customer = null, $card = null, array $optionalParameters = array())
    {
        return $this->chargeCreate(array_merge($optionalParameters, array(
            'amount' => $amount,
            'currency' => $currency,
            'customer' => $customer,
            'card' => $card
        )));
    }

    /**
     * @param $amount
     * @param $currency
     * @param null $customer
     * @param null $card
     * @param array $optionalParameters
     * @return array
     */
    public function authorize($amount, $currency, $customer = null, $card = null, array $optionalParameters = array())
    {
        return $this->chargeCreate(array_merge($optionalParameters, array(
            'amount' => $amount,
            'currency' => $currency,
            'customer' => $customer,
            'card' => $card,
            'capture' => false
        )));
    }

    /**
     * @param array $parameters
     * @return array
     */
    public function chargeCreate(array $parameters)
    {
        $this->authenticationStrategy->authenticate();
        return \Stripe_Charge::create($parameters);
    }
}