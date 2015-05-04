<?php

namespace Ekyna\Bundle\PaymentBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;

/**
 * Class AdminMenuPass
 * @package Ekyna\Bundle\PaymentBundle\DependencyInjection\Compiler
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class AdminMenuPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('ekyna_admin.menu.pool')) {
            return;
        }

        $pool = $container->getDefinition('ekyna_admin.menu.pool');

        $pool->addMethodCall('createGroup', array(array(
            'name'     => 'setting',
            'label'    => 'ekyna_setting.label',
            'icon'     => 'cogs',
            'position' => 100,
        )));
        $pool->addMethodCall('createEntry', array('setting', array(
            'name'     => 'method',
            'route'    => 'ekyna_payment_method_admin_home',
            'label'    => 'ekyna_payment.method.label.plural',
            'resource' => 'ekyna_payment_method',
            'position' => 0,
        )));
    }
}
