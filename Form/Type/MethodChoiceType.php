<?php

namespace Ekyna\Bundle\PaymentBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

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
    public function configureOptions(OptionsResolver $resolver)
    {
        $queryBuilder = function (Options $options) {
            return function (EntityRepository $repository) use ($options) {
                $qb = $repository->createQueryBuilder('m');
                $qb->andWhere($qb->expr()->eq('m.enabled', true));
                if ($options['available']) {
                    $qb->andWhere($qb->expr()->eq('m.available', true));
                }
                return $qb;
            };
        };
        $resolver
            ->setDefaults(array(
                'label'         => false,
                'expanded'      => true,
                'available'     => true,
                'class'         => $this->dataClass,
                'query_builder' => $queryBuilder,
            ))
            ->setAllowedTypes('available', 'bool')
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
