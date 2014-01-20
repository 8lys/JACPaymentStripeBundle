<?php

namespace JAC\Payment\StripeBundle\Twig;

/**
 * Class PaymentStripeExtension
 * @package JAC\Payment\StripeBundle\Twig
 */
class PaymentStripeExtension extends \Twig_Extension
{
    /**
     * @var string
     */
    protected $publishableKey;

    /**
     * @param string $publishableKey
     */
    public function __construct($publishableKey)
    {
        $this->publishableKey = $publishableKey;
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'payment_stripe_extension';
    }

    /**
     * @return array
     */
    public function getGlobals()
    {
        return [
            'stripe' => ['publishable_key' => $this->publishableKey]
        ];
    }
}
