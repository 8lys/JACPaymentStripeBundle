<?php

namespace JAC\Payment\StripeBundle\Client\Authentication;

/**
 * Interface AuthenticationStrategyInterface
 * @package JAC\Payment\StripeBundle\Client\Authentication
 */
interface AuthenticationStrategyInterface
{
    public function authenticate();
    public function getApiEndpoint();
}
