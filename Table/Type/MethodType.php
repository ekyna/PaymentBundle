<?php

namespace Ekyna\Bundle\PaymentBundle\Table\Type;

use Ekyna\Bundle\AdminBundle\Table\Type\ResourceTableType;
use Ekyna\Component\Table\TableBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class MethodType
 * @package Ekyna\Bundle\PaymentBundle\Table\Type
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class MethodType extends ResourceTableType
{
    /**
     * {@inheritdoc}
     */
    public function buildTable(TableBuilderInterface $builder, array $options)
    {
        $builder
            ->addColumn('gatewayName', 'anchor', [
                'label' => 'ekyna_core.field.name',
                'route_name' => 'ekyna_payment_method_admin_show',
                'route_parameters_map' => [
                    'methodId' => 'id'
                ],
            ])
            ->addColumn('enabled', 'boolean', [
                'label' => 'ekyna_core.field.enabled',
                'route_name' => 'ekyna_payment_method_admin_toggle',
                'route_parameters' => ['field' => 'enabled'],
                'route_parameters_map' => ['methodId' => 'id'],
            ])
            ->addColumn('actions', 'admin_actions', [
                'buttons' => [
                    [
                        'label' => 'ekyna_core.button.move_up',
                        'icon' => 'arrow-up',
                        'class' => 'primary',
                        'route_name' => 'ekyna_payment_method_admin_move_up',
                        'route_parameters_map' => [
                            'methodId' => 'id'
                        ],
                        'permission' => 'edit',
                    ], [
                        'label' => 'ekyna_core.button.move_down',
                        'icon' => 'arrow-down',
                        'class' => 'primary',
                        'route_name' => 'ekyna_payment_method_admin_move_down',
                        'route_parameters_map' => [
                            'methodId' => 'id'
                        ],
                        'permission' => 'edit',
                    ], [
                        'label' => 'ekyna_core.button.edit',
                        'class' => 'warning',
                        'route_name' => 'ekyna_payment_method_admin_edit',
                        'route_parameters_map' => [
                            'methodId' => 'id'
                        ],
                        'permission' => 'edit',
                    ], [
                        'label' => 'ekyna_core.button.remove',
                        'class' => 'danger',
                        'route_name' => 'ekyna_payment_method_admin_remove',
                        'route_parameters_map' => [
                            'methodId' => 'id'
                        ],
                        'permission' => 'delete',
                    ],
                ],
            ])
            /*->addFilter('name', 'text', array(
                'label' => 'ekyna_core.field.name',
            ))
            ->addFilter('reference', 'text', array(
                'label' => 'ekyna_core.field.reference',
            ))
            ->addFilter('enabled', 'boolean', array(
                'label' => 'ekyna_core.field.enabled',
            ))*/
        ;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefaults([
            'default_sorts' => ['position asc'],
            'max_per_page' => 100,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'ekyna_payment_method';
    }
}
