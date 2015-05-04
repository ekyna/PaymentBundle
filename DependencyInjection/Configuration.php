<?php

namespace Ekyna\Bundle\PaymentBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\ArrayNodeDefinition;
use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * Class Configuration
 * @package Ekyna\Bundle\PaymentBundle\DependencyInjection
 * @author Étienne Dauvergne <contact@ekyna.com>
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('ekyna_payment');

        $this->addPoolsSection($rootNode);

        return $treeBuilder;
    }

	/**
     * Adds admin pool sections.
     *
     * @param ArrayNodeDefinition $node
     */
    private function addPoolsSection(ArrayNodeDefinition $node)
    {
        $node
            ->children()
                ->arrayNode('pools')
                    ->addDefaultsIfNotSet()
                    ->children()
                        ->arrayNode('method')
                            ->addDefaultsIfNotSet()
                            ->children()
                                ->variableNode('templates')->defaultValue(array(
                                    '_form.html' => 'EkynaPaymentBundle:Admin/Method:_form.html',
                                    'show.html'  => 'EkynaPaymentBundle:Admin/Method:show.html',
                                    'new.html'   => 'EkynaPaymentBundle:Admin/Method:new.html',
                                ))->end()
                                ->scalarNode('parent')->end()
                                ->scalarNode('entity')->defaultValue('Ekyna\Bundle\PaymentBundle\Entity\Method')->end()
                                ->scalarNode('controller')->defaultValue('Ekyna\Bundle\PaymentBundle\Controller\Admin\MethodController')->end()
                                ->scalarNode('operator')->end()
                                ->scalarNode('repository')->end()
                                ->scalarNode('form')->defaultValue('Ekyna\Bundle\PaymentBundle\Form\Type\MethodType')->end()
                                ->scalarNode('table')->defaultValue('Ekyna\Bundle\PaymentBundle\Table\Type\MethodType')->end()
                                ->scalarNode('event')->end()
                            ->end()
                        ->end()
                    ->end()
                ->end()
            ->end()
        ;
    }
}
