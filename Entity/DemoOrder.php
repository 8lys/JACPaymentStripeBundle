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
     * @var integer
     *
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var \JMS\Payment\CoreBundle\Entity\PaymentInstruction
     *
     * @ORM\OneToOne(targetEntity="\JMS\Payment\CoreBundle\Entity\PaymentInstruction")
     */
    private $paymentInstruction;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique = true)
     */
    private $orderNumber;

    /**
     * Order amount due in cents
     *
     * @var integer
     *
     * @ORM\Column(type="integer")
     */
    private $amount;

    /**
     * @param integer $amount
     * @param integer $orderNumber
     */
    public function __construct($amount, $orderNumber)
    {
        $this->amount = $amount;
        $this->orderNumber = $orderNumber;
    }

    /**
     * @return integer
     */
    public function getOrderNumber()
    {
        return $this->orderNumber;
    }

    /**
     * @return integer
     */
    public function getAmount()
    {
        return $this->amount;
    }

    /**
     * @return \JMS\Payment\CoreBundle\Entity\PaymentInstruction
     */
    public function getPaymentInstruction()
    {
        return $this->paymentInstruction;
    }

    /**
     * @param \JMS\Payment\CoreBundle\Entity\PaymentInstruction $instruction
     */
    public function setPaymentInstruction(PaymentInstruction $instruction)
    {
        $this->paymentInstruction = $instruction;
    }

    // ...

    /**
     * Set orderNumber
     *
     * @param  string    $orderNumber
     * @return DemoOrder
     */
    public function setOrderNumber($orderNumber)
    {
        $this->orderNumber = $orderNumber;

        return $this;
    }

    /**
     * Set amount
     *
     * @param  integer     $amount
     * @return DemoOrder
     */
    public function setAmount($amount)
    {
        $this->amount = $amount;

        return $this;
    }
}
