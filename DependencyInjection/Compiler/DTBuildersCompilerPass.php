<?php
namespace Nhacsam\DataTablesBundle\DependencyInjection\Compiler;


use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

/**
 * User to pass taged DataTable Builders to the dataTable container
 *
 * @author Nhacsam
 */
class DTBuildersCompilerPass implements CompilerPassInterface
{

    public function process(ContainerBuilder $container)
    {
        if (false === $container->hasDefinition('datatables.container')) {
            return;
        }

        $definition = $container->getDefinition('datatables.container');

        foreach ($container->findTaggedServiceIds('datatables.builder') as $id => $attributes) {
            $definition->addMethodCall('addDTBuilder', array(new Reference($id)));
        }
    }


}
