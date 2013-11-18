<?php

namespace JAC\Payment\StripeBundle\Client\Authentication;

interface AuthenticationStrategyInterface
{
    function authenticate();
    function getApiEndpoint();
}