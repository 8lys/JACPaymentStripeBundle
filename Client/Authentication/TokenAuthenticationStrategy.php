<?php

namespace JAC\Payment\StripeBundle\Client\Authentication;

Class TokenAuthenticationStrategy implements AuthenticationStrategyInterface
{
    protected $apiKey;

    public function __construct($apiKey)
    {
        $this->apiKey = $apiKey;
    }

    public function authenticate()
    {
        \Stripe::setApiKey($this->apiKey);
    }

    public function getApiEndpoint()
    {
        return 'https://api.stripe.com/';
    }
}
