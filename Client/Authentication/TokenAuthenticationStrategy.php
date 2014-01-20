<?php

namespace JAC\Payment\StripeBundle\Client\Authentication;

/**
 * Class TokenAuthenticationStrategy
 * @package JAC\Payment\StripeBundle\Client\Authentication
 */
class TokenAuthenticationStrategy implements AuthenticationStrategyInterface
{
    /**
     * @var string
     */
    protected $secretKey;

    /**
     * @param string $secretKey
     */
    public function __construct($secretKey)
    {
        $this->apiKey = $secretKey;
    }

    public function authenticate()
    {
        \Stripe::setApiKey($this->secretKey);
    }

    /**
     * @return string
     */
    public function getApiEndpoint()
    {
        return 'https://api.stripe.com/';
    }
}
