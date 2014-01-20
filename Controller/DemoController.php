<?php
namespace JAC\Payment\StripeBundle\Controller;

use JAC\Payment\StripeBundle\Entity\DemoOrder;
use JMS\DiExtraBundle\Annotation as DI;
use JMS\Payment\CoreBundle\Entity\Payment;
use JMS\Payment\CoreBundle\PluginController\Result;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Config\Definition\Exception\Exception;
use Symfony\Component\HttpFoundation\RedirectResponse;

/**
 * @Route("/demo/payments")
 */
class DemoController extends Controller
{
    /** @DI\Inject */
    private $request;

    /** @DI\Inject */
    private $router;

    /** @DI\Inject("doctrine.orm.entity_manager") */
    private $em;

    /** @DI\Inject("payment.plugin_controller") */
    private $ppc;

    /**
     * @Route("/{orderNumber}/details", name = "payment_details")
     * @Template
     */
    public function detailsAction(DemoOrder $order)
    {
        $form = $this->getFormFactory()->create('jms_choose_payment_method', null, array(
            'amount'   => $order->getAmount(),
            'currency' => 'USD'
        ));

        if ('POST' === $this->request->getMethod()) {
            $form->bind($this->request);

            if ($form->isValid()) {
                $this->ppc->createPaymentInstruction($instruction = $form->getData());

                $order->setPaymentInstruction($instruction);
                $this->em->persist($order);
                $this->em->flush($order);

                return new RedirectResponse($this->router->generate('payment_complete', array(
                    'orderNumber' => $order->getOrderNumber(),
                )));
            }
        }

        return array(
            'form' => $form->createView()
        );
    }

    // ...

    /** @DI\LookupMethod("form.factory") */
    protected function getFormFactory() { }

    /**
     * @Route("/{orderNumber}/complete", name = "payment_complete")
     * @Template
     */
    public function completeAction(DemoOrder $order)
    {
        $instruction = $order->getPaymentInstruction();
        if (null === $pendingTransaction = $instruction->getPendingTransaction()) {
            $payment = $this->ppc->createPayment($instruction->getId(), $instruction->getAmount() - $instruction->getDepositedAmount());
        } else {
            $payment = $pendingTransaction->getPayment();
        }

        try {
            $result = $this->ppc->approve($payment->getId(), $payment->getTargetAmount());
        } catch (\Exception $e) {
            var_dump($e);
            exit();
        }

        // payment was successful, do something interesting with the order
        return array();
    }

    /**
     * @Route("/{orderNumber}/cancel", name = "payment_cancel")
     */
    public function cancelAction(DemoOrder $order)
    {}
}
