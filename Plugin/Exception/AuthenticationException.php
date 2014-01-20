<?php

namespace JAC\Payment\StripeBundle\Plugin\Exception;

use JMS\Payment\CoreBundle\Plugin\Exception\Exception;

/**
 * Class AuthenticationException
 *
 * This exception is thrown whenever authentication with Stripe's API fails
 *
 * @package JAC\Payment\StripeBundle\Plugin\Exception
 */
class AuthenticationException extends Exception
{
}
