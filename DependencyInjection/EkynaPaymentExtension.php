<?php

namespace Ekyna\Bundle\PaymentBundle\DependencyInjection;

use Ekyna\Bundle\AdminBundle\DependencyInjection\AbstractExtension;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader;

/**
 * Class EkynaPaymentExtension
 * @package Ekyna\Bundle\PaymentBundle\DependencyInjection
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class EkynaPaymentExtension extends AbstractExtension
{
    /**
     * {@inheritDoc}
     */
    public function load(array $configs, ContainerBuilder $container)
    {
        $this->configure($configs, 'ekyna_payment', new Configuration(), $container);
    }
}
