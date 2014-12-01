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
    private $_em;
    
    
    
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
     */
    public function getViewRouteName()
    {
        return null;
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
        $this->_em = $em;
    }

    ///////////////////////////////////////////
    //////// Advanced API Functions ///////////
    ///////////////////////////////////////////

    public function getAllEntities()
    {
        return $this->getRepository()->findAll();
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
        return $this->_em->getRepository($this->getEntityName());
    }


    
}
