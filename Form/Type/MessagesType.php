<?php

namespace Ekyna\Bundle\PaymentBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class MessagesType
 * @package Ekyna\Bundle\PaymentBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class MessagesType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'label' => 'ekyna_payment.message.label.plural',
                'type' => 'ekyna_payment_message',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'collection';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_payment_messages';
    }
}
