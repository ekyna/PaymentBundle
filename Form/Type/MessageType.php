<?php

namespace Ekyna\Bundle\PaymentBundle\Form\Type;

use Ekyna\Bundle\PaymentBundle\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

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
    public function buildForm(FormBuilderInterface $builder, array $options = array())
    {
        $builder
            ->add('flash', 'textarea', array(
                'label' => 'ekyna_payment.message.field.flash',
                'required' => false,
                'attr' => array(
                    'class' => 'tinymce',
                    'data-theme' => 'simple',
                )
            ))
            ->add('email', 'textarea', array(
                'label' => 'ekyna_payment.message.field.email',
                'required' => false,
                'attr' => array(
                    'class' => 'tinymce',
                    'data-theme' => 'simple',
                )
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options = array())
    {
        $message = $form->getData();

        $view->vars['state'] = $message instanceof Message ? $message->getState() : '';
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver
            ->setDefaults(array(
                'data_class' => 'Ekyna\Bundle\PaymentBundle\Entity\Message',
            ))
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
