<?php

namespace JAC\Payment\StripeBundle\Plugin;

use JAC\Payment\StripeBundle\Client\Client;
use JMS\Payment\CoreBundle\Model\FinancialTransactionInterface;
use JMS\Payment\CoreBundle\Plugin\AbstractPlugin;
use JMS\Payment\CoreBundle\Plugin\PluginInterface;

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

        $chargeObj = $this->client->authorize(
            $transaction->getRequestedAmount(),
            $transaction->getPayment()->getPaymentInstruction()->getCurrency(),
            $data->has('customer_id') ? $data->get('customer_id') : null,
            $data->has('card_token') ? $data->get('card_token') : null,
            $data->has('optional_parameters') ? $data->get('optional_parameters') : array()
        );

        $transaction->setReferenceNumber($chargeObj->id);
        $transaction->setProcessedAmount($chargeObj->amount / 100);
        $transaction->setResponseCode(PluginInterface::RESPONSE_CODE_SUCCESS);
        $transaction->setReasonCode(PluginInterface::REASON_CODE_SUCCESS);
    }

    public function processes($paymentSystemName)
    {
        return 'stripe' === $paymentSystemName;
    }
}