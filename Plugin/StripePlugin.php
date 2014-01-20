<?php

namespace JAC\Payment\StripeBundle\Plugin;

use JAC\Payment\StripeBundle\Client\Client;
use JAC\Payment\StripeBundle\Plugin\Exception\AuthenticationException;
use JMS\Payment\CoreBundle\Model\FinancialTransactionInterface;
use JMS\Payment\CoreBundle\Model\PaymentInstructionInterface;
use JMS\Payment\CoreBundle\Plugin\AbstractPlugin;
use JMS\Payment\CoreBundle\Plugin\Exception\CommunicationException;
use JMS\Payment\CoreBundle\Plugin\Exception\FinancialException;
use JMS\Payment\CoreBundle\Plugin\Exception\InternalErrorException;
use JMS\Payment\CoreBundle\Plugin\Exception\InvalidDataException;
use JMS\Payment\CoreBundle\Plugin\PluginInterface;

/**
 * Class StripePlugin
 * @package JAC\Payment\StripeBundle\Plugin
 */
class StripePlugin extends AbstractPlugin
{
    /**
     * @var \JAC\Payment\StripeBundle\Client\Client
     */
    protected $client;

    /**
     * @var bool
     */
    protected $isDebug;

    /**
     * @param \JAC\Payment\StripeBundle\Client\Client $client
     * @param bool                                    $isDebug
     */
    public function __construct(Client $client, $isDebug)
    {
        $this->client = $client;
        $this->isDebug = !!$isDebug;
    }

    /**
     * @param \JMS\Payment\CoreBundle\Model\FinancialTransactionInterface $transaction
     * @param bool                                                        $retry
     */
    public function approve(FinancialTransactionInterface $transaction, $retry)
    {
        $data = $transaction->getExtendedData();

        $this->doClientRequest(
            'authorize',
            [
                'amount'   => $transaction->getRequestedAmount(),
                'currency' => $transaction->getPayment()->getPaymentInstruction()->getCurrency(),
                'customer' => $data->has('customer_id') ? $data->get('customer_id') : null,
                'card'     => $data->has('card_token') ? $data->get('card_token') : null,
                'capture'  => true
            ],
            $transaction
        );
    }

    /**
     * @param \JMS\Payment\CoreBundle\Model\FinancialTransactionInterface $transaction
     * @param bool                                                        $retry
     */
    public function approveAndDeposit(FinancialTransactionInterface $transaction, $retry)
    {
        $data = $transaction->getExtendedData();

        $this->doClientRequest(
            'charge',
            [
                'amount'   => $transaction->getRequestedAmount(),
                'currency' => $transaction->getPayment()->getPaymentInstruction()->getCurrency(),
                'customer' => $data->has('customer_id') ? $data->get('customer_id') : null,
                'card'     => $data->has('card_token') ? $data->get('card_token') : null,
            ],
            $transaction
        );
    }

    /**
     * @param \JMS\Payment\CoreBundle\Model\PaymentInstructionInterface $paymentInstruction
     */
    public function checkPaymentInstruction(PaymentInstructionInterface $paymentInstruction)
    {

    }

    /**
     * @param \JMS\Payment\CoreBundle\Model\FinancialTransactionInterface $transaction
     * @param bool                                                        $retry
     */
    public function credit(FinancialTransactionInterface $transaction, $retry)
    {
        $this->doClientRequest(
            'credit',
            [
                'charge_id' => $transaction->getReferenceNumber()
            ],
            $transaction
        );
    }

    /**
     * @param \JMS\Payment\CoreBundle\Model\FinancialTransactionInterface $transaction
     * @param bool                                                        $retry
     */
    public function deposit(FinancialTransactionInterface $transaction, $retry)
    {
        $this->doClientRequest(
            'capture',
            [
                'charge_id' => $transaction->getReferenceNumber()
            ],
            $transaction
        );
    }

    /**
     * @param  string                                                             $action
     * @param  array                                                              $parameters
     * @param  \JMS\Payment\CoreBundle\Model\FinancialTransactionInterface        $transaction
     * @throws \JMS\Payment\CoreBundle\Plugin\Exception\InvalidDataException
     * @throws \JAC\Payment\StripeBundle\Plugin\Exception\AuthenticationException
     * @throws \JMS\Payment\CoreBundle\Plugin\Exception\FinancialException
     * @throws \JMS\Payment\CoreBundle\Plugin\Exception\CommunicationException
     * @throws \JMS\Payment\CoreBundle\Plugin\Exception\InternalErrorException

     */
    public function doClientRequest($action, array $parameters, FinancialTransactionInterface $transaction)
    {
        try {
            $response = $this->client->$action($parameters);

            $transaction->setReferenceNumber($response->id);
            $transaction->setProcessedAmount($response->amount);
            $transaction->setResponseCode(PluginInterface::RESPONSE_CODE_SUCCESS);
            $transaction->setReasonCode(PluginInterface::REASON_CODE_SUCCESS);
        } catch (\Stripe_CardError $e) {
            $body = $e->getJsonBody();
            $error  = $body['error'];

            $transaction->setReasonCode($error['code']);
            $transaction->setResponseCode($error['type']);

            $financialException = new FinancialException($error['message']);
            $financialException->setFinancialTransaction($transaction);

            throw $financialException;
        } catch (\Stripe_InvalidRequestError $e) {
            $body = $e->getJsonBody();
            $error  = $body['error'];

            $transaction->setReasonCode($error['code']);
            $transaction->setResponseCode($error['type']);

            $invalidDataException = new InvalidDataException($error['message']);
            $invalidDataException->setFinancialTransaction($transaction);

            throw $invalidDataException;
        } catch (\Stripe_AuthenticationError $e) {
            $body = $e->getJsonBody();
            $error  = $body['error'];

            $transaction->setReasonCode($error['code']);
            $transaction->setResponseCode($error['type']);

            $authenticationException = new AuthenticationException($error['message']);
            $authenticationException->setFinancialTransaction($transaction);

            throw $authenticationException;
        } catch (\Stripe_ApiConnectionError $e) {
            $body = $e->getJsonBody();
            $error  = $body['error'];

            $transaction->setReasonCode($error['code']);
            $transaction->setResponseCode($error['type']);

            $communicationException = new CommunicationException($error['message']);
            $communicationException->setFinancialTransaction($transaction);

            throw $communicationException;
        } catch (\Stripe_Error $e) {
            $body = $e->getJsonBody();
            $error  = $body['error'];

            $transaction->setReasonCode($error['code']);
            $transaction->setResponseCode($error['type']);

            $internalErrorException = new InternalErrorException($error['message']);
            $internalErrorException->setFinancialTransaction($transaction);

            throw $internalErrorException;
        }
    }

    /**
     * @return bool
     */
    public function isDebug()
    {
        return $this->isDebug;
    }

    /**
     * @param  string $paymentSystemName
     * @return bool
     */
    public function processes($paymentSystemName)
    {
        return 'stripe' === $paymentSystemName;
    }
}
