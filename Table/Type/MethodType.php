<?php

namespace Ekyna\Bundle\PaymentBundle\Table\Type;

use Ekyna\Bundle\AdminBundle\Table\Type\ResourceTableType;
use Ekyna\Component\Table\TableBuilderInterface;

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
            ->addColumn('paymentName', 'anchor', [
                'label' => 'ekyna_core.field.name',
                'sortable' => true,
                'route_name' => 'ekyna_payment_method_admin_show',
                'route_parameters_map' => [
                    'methodId' => 'id'
                ],
            ])
            ->addColumn('enabled', 'boolean', [
                'label' => 'ekyna_core.field.enabled',
                'sortable' => true,
                'route_name' => 'ekyna_payment_method_admin_toggle',
                'route_parameters' => ['field' => 'enabled'],
                'route_parameters_map' => ['methodId' => 'id'],
            ])
            ->addColumn('actions', 'admin_actions', [
                'buttons' => [
                    [
                        'label' => 'ekyna_core.button.edit',
                        'class' => 'warning',
                        'route_name' => 'ekyna_payment_method_admin_edit',
                        'route_parameters_map' => [
                            'methodId' => 'id'
                        ],
                        'permission' => 'edit',
                    ],
                    [
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
    public function getName()
    {
        return 'ekyna_payment_method';
    }
}
