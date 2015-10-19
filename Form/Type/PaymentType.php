<?php

namespace Ekyna\Bundle\PaymentBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Ekyna\Component\Sale\Payment\PaymentStates;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

/**
 * Class PaymentType
 * @package Ekyna\Bundle\PaymentBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class PaymentType extends AbstractType
{
    /**
     * @var string
     */
    private $methodClass;


    /**
     * Constructor.
     * @param string $methodClass
     */
    public function __construct($methodClass)
    {
        $this->methodClass = $methodClass;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) use ($options) {
            $form = $event->getForm();

            /** @var \Ekyna\Bundle\PaymentBundle\Entity\Payment $payment */
            $payment = $event->getData();

            $disabled = (null !== $payment && $payment->getState() !== PaymentStates::STATE_NEW);
            $currency = null !== $payment ? $payment->getCurrency() : 'EUR';

            $form->add('amount', 'money', [
                'label' => 'ekyna_payment.payment.field.amount',
                'currency' => $currency,
                'disabled' => $disabled || !$options['admin_mode'],
            ]);

            if ($options['admin_mode']) {
                $form->add('method', 'entity', [
                    'label'    => 'ekyna_payment.payment.field.method',
                    'class'    => $this->methodClass,
                    'property' => 'gatewayName',
                    'disabled' => $disabled,
                    'query_builder' => function (EntityRepository $repository) {
                        return $repository
                            ->createQueryBuilder('m')
                            ->andWhere('m.enabled = :enabled')
                            ->andWhere('m.factoryName = :factoryName')
                            ->setParameters([
                                'enabled' => true,
                                'factoryName' => 'offline',
                            ])
                        ;
                    }
                ]);
            } else {
                $form->add('method', 'ekyna_payment_method_choice', [
                    'label'    => 'ekyna_payment.payment.field.method',
                ]);
            }
        });
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_payment_payment';
    }
}
