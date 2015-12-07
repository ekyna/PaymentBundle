<?php

namespace Ekyna\Bundle\PaymentBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * Class MethodChoiceType
 * @package Ekyna\Bundle\PaymentBundle\Form\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class MethodChoiceType extends AbstractType
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
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $queryBuilder = function (Options $options) {
            if (!$options['disabled']) {
                return function (EntityRepository $repository) {
                    return $repository
                        ->createQueryBuilder('m')
                        ->andWhere('m.enabled = true')
                        ->addOrderBy('m.position', 'ASC');
                };
            } else {
                return function (EntityRepository $repository) {
                    return $repository
                        ->createQueryBuilder('m')
                        ->addOrderBy('m.position', 'ASC');
                };
            }
        };
        $resolver
            ->setDefaults(array(
                'label' => false,
                'expanded' => true,
                'class' => $this->dataClass,
                'query_builder' => $queryBuilder,
            ))
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return 'entity';
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_payment_method_choice';
    }
}
