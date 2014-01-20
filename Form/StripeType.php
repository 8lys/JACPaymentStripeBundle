<?php

namespace JAC\Payment\StripeBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class StripeType
 * @package JAC\Payment\StripeBundle\Form
 */
class StripeType extends AbstractType
{
    /**
     * @param \Symfony\Component\Form\FormBuilderInterface $builder
     * @param array                                        $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('number', 'text', array(
            'label' => 'Card Number',
            'attr' => array('data-stripe' => 'number'),
            'mapped' => false
        ))
        ->add('exp_month', 'choice', array(
            'label' => 'Expiration (MM/YYYY)',
            'attr' => array('data-stripe' => 'exp-month'),
            'choices' => array('01' => 'Jan', '02' => 'Feb', '03' => 'Mar', '04' => 'Apr', '05' => 'May', '06' => 'Jun', '07' => 'Jul', '08' => 'Aug', '09' => 'Sep', '10' => 'Oct', '11' => 'Nov', '12' => 'Dec'),
            'empty_value' => 'MM',
            'mapped' => false
        ))
        ->add('exp_year', 'choice', array(
            'label' => ' ',
            'attr' => array('data-stripe' => 'exp-year'),
            'empty_value' => 'YYYY',
            'choices' => array_combine(range(date('Y'), date('Y') + 20), range(date('Y'), date('Y') + 20)),
            'mapped' => false
        ))
        ->add('card_token', 'hidden');
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'stripe';
    }
}
