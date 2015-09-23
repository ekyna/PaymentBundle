<?php

namespace Ekyna\Bundle\PaymentBundle\Form\Type;

use Ekyna\Bundle\PaymentBundle\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class MessageType
 * @package Ekyna\Bundle\PaymentBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class MessageType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('flash', 'tinymce', [
                'label' => 'ekyna_payment.message.field.flash',
                'theme' => 'simple',
                'required' => false,
            ])
            ->add('email', 'tinymce', [
                'label' => 'ekyna_payment.message.field.email',
                'theme' => 'simple',
                'required' => false,
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $message = $form->getData();

        $view->vars['state'] = $message instanceof Message ? $message->getState() : '';
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefaults([
                'data_class' => 'Ekyna\Bundle\PaymentBundle\Entity\Message',
            ])
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_payment_message';
    }
}
