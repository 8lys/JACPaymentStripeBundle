<?php

namespace JAC\Payment\StripeBundle\Client\Authentication;

interface AuthenticationStrategyInterface
{
    public function authenticate();
    public function getApiEndpoint();
}
