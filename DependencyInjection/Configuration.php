<?php

namespace Nhacsam\DataTablesBundle\DependencyInjection;

use Symfony\Component\Config\Definition\Builder\TreeBuilder;
use Symfony\Component\Config\Definition\ConfigurationInterface;

/**
 * This is the class that validates and merges configuration from your app/config files
 *
 * To learn more see {@link http://symfony.com/doc/current/cookbook/bundles/extension.html#cookbook-bundles-extension-config-class}
 */
class Configuration implements ConfigurationInterface
{
    /**
     * {@inheritDoc}
     */
    public function getConfigTreeBuilder()
    {
        $treeBuilder = new TreeBuilder();
        $rootNode = $treeBuilder->root('nhacsam_datatables');
        
        $rootNode
        	->children()
        		->booleanNode('responsive')
					->defaultValue(false)
				->end()
        		->booleanNode('bootstrap')
					->defaultValue(false)
				->end()
        		->booleanNode('use_remote_libs')
					->defaultValue(true)
				->end()
        		->booleanNode('default_include_js')
					->defaultValue(true)
				->end()
        		->scalarNode('min_server_side')
					->defaultValue('1000')
				->end()
        	->end()
        ;
        return $treeBuilder;
    }
}
