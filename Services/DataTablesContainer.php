<?php

namespace Nhacsam\DataTablesBundle\Services;

/**
 * Factory which handle dataTables creation and view
 * @author Nhacsam
 */
class DataTablesContainer
{
   
    private $dtBuilders = array();
    
    
    private $configuration;
    
    /**
     * The doctrine component
     * @var \Doctrine\Bundle\DoctrineBundle\Registry
     */
    private $doctrine;

     /**
     * The router component
     * @var \Symfony\Component\Routing\Router
     */
    private $router;

    
    public function __construct(
            array $configuration,
            \Doctrine\Bundle\DoctrineBundle\Registry $doctrine,
            \Symfony\Component\Routing\Router $router
    ) {
        $this->configuration = $configuration;
        $this->doctrine = $doctrine;
        $this->router = $router;
    } 
    
    
    
    public function addDTBuilder(\Nhacsam\DataTablesBundle\Builder\DataTableAbstractBuilder $dtBuilder)
    {
        $dtBuilder->setManager($this->doctrine->getManager());
        $dtBuilder->setRouter($this->router);
        $dtBuilder->setBundleConfiguration($this->configuration);
        $this->dtBuilders[$dtBuilder->getName()] = $dtBuilder;
    }

    public function getDTBuilder($name)
    {
        if (!is_string($name) || ! array_key_exists($name, $this->dtBuilders)) {
            throw new \InvalidArgumentException('Unknow DataTable name.');
        }
        return $this->dtBuilders[$name];
    }
    
}
