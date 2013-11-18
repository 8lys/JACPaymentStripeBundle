<?php
namespace JAC\Payment\StripeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use JMS\Payment\CoreBundle\Entity\PaymentInstruction;

/**
 * @ORM\Entity
 * @ORM\Table(name="demo_orders")
 */
class DemoOrder
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected  $id;

    /** @ORM\OneToOne(targetEntity="JMS\Payment\CoreBundle\Entity\PaymentInstruction") */
    private $paymentInstruction;

    /** @ORM\Column(type="string", unique = true) */
    private $orderNumber;

    /**
     * Order amount due in cents
     *
     * @ORM\Column(type="integer")
     */
    private $amount;


    public function __construct($amount, $orderNumber)
    {
        $this->amount = $amount;
        $this->orderNumber = $orderNumber;
    }

    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    public function getAmount()
    {
        return $this->amount;
    }

    public function getPaymentInstruction()
    {
        return $this->paymentInstruction;
    }

    public function setPaymentInstruction(PaymentInstruction $instruction)
    {
        $this->paymentInstruction = $instruction;
    }

    // ...

    /**
     * Set orderNumber
     *
     * @param string $orderNumber
     * @return Order
     */
    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $orderNumber;
    
        return $this;
    }

    /**
     * Set amount
     *
     * @param float $amount
     * @return Order
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;
    
        return $this;
    }
}