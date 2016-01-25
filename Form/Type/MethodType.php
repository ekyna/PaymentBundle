<?php

namespace Ekyna\Bundle\PaymentBundle\Form\Type;

use Ekyna\Bundle\AdminBundle\Form\Type\ResourceFormType;
use Ekyna\Bundle\PaymentBundle\Form\EventListener\BuildConfigSubscriber;
use Payum\Core\Registry\RegistryInterface;
use Symfony\Component\Form\FormBuilderInterface;

/**
 * Class MethodType
 * @package Ekyna\Bundle\PaymentBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class MethodType extends ResourceFormType
{
    /**
     * @var RegistryInterface
     */
    private $registry;

    /**
     * Constructor.
     *
     * @param string            $class
     * @param RegistryInterface $registry
     */
    public function __construct($class, RegistryInterface $registry)
    {
        parent::__construct($class);

        $this->registry = $registry;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options = array())
    {
        $builder
            ->add('paymentName', 'text', array(
                'label' => 'ekyna_core.field.name',
            ))
            ->add('factoryName', 'payum_payment_factories_choice', array(
                'label' => 'ekyna_payment.method.field.factory_name',
                'disabled' => true,
            ))
            ->add('image', 'ekyna_cms_image')
            ->add('description', 'tinymce', array(
                'label' => 'ekyna_core.field.description',
            ))
            ->add('messages', 'ekyna_payment_messages')
            ->add('enabled', 'checkbox', array(
                'label' => 'ekyna_core.field.enabled',
                'required' => false,
                'attr' => array(
                    'align_with_widget' => true,
                ),
            ))
            ->add('available', 'checkbox', array(
                'label' => 'ekyna_payment.method.field.available',
                'required' => false,
                'attr' => array(
                    'align_with_widget' => true,
                ),
            ))
        ;

        $builder->addEventSubscriber(new BuildConfigSubscriber($this->registry));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_payment_method';
    }
}
