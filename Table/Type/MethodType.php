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
            ->addColumn('gatewayName', 'anchor', array(
                'label' => 'ekyna_core.field.name',
                'sortable' => true,
                'route_name' => 'ekyna_payment_method_admin_show',
                'route_parameters_map' => array(
                    'methodId' => 'id'
                ),
            ))
            ->addColumn('enabled', 'boolean', array(
                'label' => 'ekyna_core.field.enabled',
                'sortable' => true,
                'route_name' => 'ekyna_payment_method_admin_toggle',
                'route_parameters' => array('field' => 'enabled'),
                'route_parameters_map' => array('methodId' => 'id'),
            ))
            ->addColumn('actions', 'admin_actions', array(
                'buttons' => array(
                    array(
                        'label' => 'ekyna_core.button.edit',
                        'class' => 'warning',
                        'route_name' => 'ekyna_payment_method_admin_edit',
                        'route_parameters_map' => array(
                            'methodId' => 'id'
                        ),
                        'permission' => 'edit',
                    ),
                    array(
                        'label' => 'ekyna_core.button.remove',
                        'class' => 'danger',
                        'route_name' => 'ekyna_payment_method_admin_remove',
                        'route_parameters_map' => array(
                            'methodId' => 'id'
                        ),
                        'permission' => 'delete',
                    ),
                ),
            ))
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
