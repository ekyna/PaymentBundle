<?php

namespace Ekyna\Bundle\PaymentBundle;

use Ekyna\Bundle\CoreBundle\AbstractBundle;
use Ekyna\Bundle\PaymentBundle\DependencyInjection\Compiler\AdminMenuPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Class EkynaPaymentBundle
 * @package Ekyna\Bundle\PaymentBundle
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class EkynaPaymentBundle extends AbstractBundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);
        $container->addCompilerPass(new AdminMenuPass());
    }
}
