<?php

namespace JAC\Payment\StripeBundle\Plugin;

use JAC\Payment\StripeBundle\Client\Client;
use JMS\Payment\CoreBundle\Model\FinancialTransactionInterface;
use JMS\Payment\CoreBundle\Plugin\AbstractPlugin;

Class StripePlugin extends AbstractPlugin
{
    protected $client;
    protected $isDebug;

    public function __construct(Client $client, $isDebug)
    {
        $this->client = $client;
        $this->isDebug = !!$isDebug;
    }

    public function approve(FinancialTransactionInterface $transaction, $retry)
    {
        $data = $transaction->getExtendedData();

        $response = $this->client->authorize(
            $transaction->getRequestedAmount(),
            $transaction->getPayment()->getPaymentInstruction()->getCurrency(),
            $data->has('customer_id') ? $data->get('customer_id') : null,
            $data->has('card_token') ? $data->get('card_token') : null,
            $data->has('optional_parameters') ? $data->get('optional_parameters') : array()
        );
    }

    public function processes($paymentSystemName)
    {
        return 'stripe' === $paymentSystemName;
    }
}