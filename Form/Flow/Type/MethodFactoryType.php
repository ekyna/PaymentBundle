<?php

namespace Ekyna\Bundle\PaymentBundle\Form\Flow\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class MethodFactoryType
 * @package Ekyna\Bundle\PaymentBundle\Form\Flow\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class MethodFactoryType extends AbstractType
{
    /**
     * @var string
     */
    protected $dataClass;


    /**
     * Constructor.
     *
     * @param string $dataClass
     */
    public function __construct($dataClass)
    {
        $this->dataClass = $dataClass;
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('factoryName', 'payum_payment_factories_choice')
        ;
    }

    /**
     * {@inheritDoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->dataClass,
        ));
    }

    /**
     * {@inheritDoc}
     */
    public function getName()
    {
        return 'ekyna_payment_method_create_factory';
    }
}
