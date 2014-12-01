<?php

namespace Nhacsam\DataTablesBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Nhacsam\DataTablesBundle\DependencyInjection\Compiler\DTBuildersCompilerPass;


class NhacsamDataTablesBundle extends Bundle
{
    
    
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new DTBuildersCompilerPass());
    }
    
    
}
