<?php

namespace JAC\Payment\StripeBundle\Twig;

Class PaymentStripeExtension extends \Twig_Extension
{
    protected $publishableKey;

    public function __construct($publishableKey)
    {
        $this->publishableKey = $publishableKey;
    }

    public function getName()
    {
        return 'payment_stripe_extension';
    }

    public function getGlobals()
    {
        return [
            'stripe' => ['publishable_key' => $this->publishableKey]
        ];
    }
}