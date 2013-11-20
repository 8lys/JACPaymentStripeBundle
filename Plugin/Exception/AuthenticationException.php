<?php

namespace JAC\Payment\StripeBundle\Plugin\Exception;

use JMS\Payment\CoreBundle\Plugin\Exception\Exception;

/**
 * This exception is thrown whenever authentication with Stripe's API failed
 */
Class AuthenticationException extends Exception
{
}