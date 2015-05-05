<?php

namespace Ekyna\Bundle\PaymentBundle\DependencyInjection\Compiler;

use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class ChangePayumPathPass
 * @package Ekyna\Bundle\PaymentBundle\DependencyInjection\Compiler
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class ChangePayumPathPass implements CompilerPassInterface
{
    /**
     * {@inheritdoc}
     */
    public function process(ContainerBuilder $container)
    {
        if (!$container->hasDefinition('payum.security.token_factory')) {
            return;
        }

        $payumTokenFactory = $container->getDefinition('payum.security.token_factory');

        $paths = $payumTokenFactory->getArgument(1);
        $paths['notify'] = 'ekyna_payment_notify';
        $payumTokenFactory->replaceArgument(1, $paths);
    }
}
