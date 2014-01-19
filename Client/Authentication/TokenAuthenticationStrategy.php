<?php

namespace JAC\Payment\StripeBundle\Client\Authentication;

Class TokenAuthenticationStrategy implements AuthenticationStrategyInterface
{
    protected $secretKey;

    public function __construct($secretKey)
    {
        $this->apiKey = $secretKey;
    }

    public function authenticate()
    {
        \Stripe::setApiKey($this->secretKey);
    }

    public function getApiEndpoint()
    {
        return 'https://api.stripe.com/';
    }
}
