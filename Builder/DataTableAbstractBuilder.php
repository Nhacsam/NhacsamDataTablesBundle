<?php
namespace Nhacsam\DataTablesBundle\Builder;


/**
 * Base class to build a new dataTable
 * @author Nhacsam
 */
abstract class DataTableAbstractBuilder
{
    /**
     * Side of the dataTable computation :
     * AUTO_SIDE use the entity number value in the configuration
     */
     const AUTO_SIDE = 1;
     const CLIENT_SIDE = 2;
     const SERVER_SIDE = 3;


    /**
     * Entity Manager
     * @var \Doctrine\ORM\EntityManager
     */
    protected $em = null ;

    /**
     * The Routing coponnent
     * @var \Symfony\Component\Routing\Router
     */
    protected $router = null;
    
    
    /** 
     * Name of the DataTable
     * @return string
     */
    abstract public function getName();
    
    /** 
     * Get the class name of the entity to display
     * @return string
     */
    abstract public function getEntityName();
    

    /**
     * Get an array of AbstractColumn
     * Corresponding to available columns for the dataTable
     * @return array
     */
    abstract  public function getAvailableColumns();

    /**
     * Get the default columns for the dataTable
     * @see getAvailableColumn
     * @return array
     */
    public function getDefaultColumns()
    {
        return $this->getAvailableColumns();
    }
    
    /**
     * Get the type of dataTable to use
     * @return integer
     */
    public function getDataTableType()
    {
        return self::AUTO_SIDE;
    }
    
    
    /**
     * Get the routeName for the view button
     * Return null for no view button
     * @return string
     */
    public function getViewRouteName()
    {
        return null;
    }

    /**
     * Get the html view link
     * @param string url Target Url of the link
     * @return string Final html link
     */
    public function getViewLink($url)
    {
        return '<a href="'.$url.'>view</a>';
    }


    //////////////////////////////////////////
    ///////// Dependency Injection ///////////
    //////////////////////////////////////////

    /*
     * Set the default entity manager
     * @param \Doctrine\ORM\EntityManager $em The entity manager
     */
    final public function setManager(\Doctrine\ORM\EntityManager $em)
    {
        if ($this->em == null) {
            $this->em = $em;
        }
    }

    /*
     * Set the routing component
     * @param \Doctrine\ORM\EntityManager $em The entity manager
     */
    final public function setRouter(\Symfony\Component\Routing\Router $router)
    {
        if ($this->router == null) {
            $this->router = $router;
        }
    }

    ///////////////////////////////////////////
    //////// Advanced API Functions ///////////
    ///////////////////////////////////////////

    /**
     * Get all the entities to show in the dataTable
     * @return mixed[]
     */
    public function getAllEntities()
    {
        return $this->getRepository()->findAll();
    }

    /**
     * Get the url for the view button
     * Return null for no view button
     * @param mixed $entity Entity to show on click
     * @return string
     */
    public function getViewUrl($entity)
    {
        $routeName = $this->getViewRouteName();
        if ($routeName === null) {
            return null;
        } else {
            return $this->router->generate($routeName, array(
               'id' => $entity->getId()
            ));
        }
    }


    ///////////////////////////////////////////
    //////////// Finals Functions /////////////
    ///////////////////////////////////////////

    /**
     * Get the final side to use
     * @return integer
     */
    final public function getUseClientSide()
    {
        if ($this->getDataTableType() != self::AUTO_SIDE) {
            return ($this->getDataTableType() == self::CLIENT_SIDE);
        } else {




            return true;
        }
    }

    /**
     * Get the Columns to use according to the current view
     * @return array
     */
    final public function getColumns()
    {
        return $this->getDefaultColumns();
    }


    //////////////////////////////////////////
    ///////////////// Utils //////////////////
    //////////////////////////////////////////

    final protected function getRepository()
    {
        return $this->em->getRepository($this->getEntityName());
    }


    
}
