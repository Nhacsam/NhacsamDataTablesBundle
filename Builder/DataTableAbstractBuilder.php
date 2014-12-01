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

    /**
     * Return true if the table has a view column
     * @return boolean
     */
    public function hasViewColumn()
    {
        return ($this->getViewRouteName() != null);
    }


    /**
     * Get all the entities to show in the dataTable
     * @return mixed[]
     */
    public function getAllEntities()
    {
        return $this->getRepository()->findAll();
    }


    /**
     * Count all the entities to show in the dataTable
     * @return integer
     */
    public function countAllEntities()
    {
        $qb = $this->getQueryBuilder()->select('COUNT(e)');
        return $qb->getQuery()->getSingleScalarResult();
    }

    /**
     * Get the data to response form the Datatables Ajax Data
     * @see http://datatables.net/manual/server-side
     * @param array $params
     * @return array
     */
    public function getDataTableAjaxData($params)
    {
        return array();
    }

    /**
     * Count the data to response form the Datatables Ajax Data
     * @see http://datatables.net/manual/server-side
     * @param array $params
     * @return integer
     */
    public function countDataTableAjaxData($params)
    {
        return 0;
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

    final public function getIndexedColumns()
    {
        $hashTable = array();
        foreach ($this->getColumns() as $column) {
            $hashTable[$column->getName()] = $column ;
        }
        return $hashTable;
    }




     /**
     *  Transform array of entities to the data needs by Datatables
     *  @param array $entities
     *  @param array $params The Datatable params
     *  @retun array
     */
    final function getDatatablesDatas($entities, $params)
    {
        $aDatas = array();
        $columns = $this->getIndexedColumns();
        foreach ($entities as $entity) {
            $aDataArray = array();
            $url = $this->getViewUrl($entity);

            foreach ($params['columns'] as $col) {
                $name = $col['name'];
                if (!$name) {
                    $aDataArray[] = "";
                } else if ($name == 'nhacsam_dt_view') {
                    $aDataArray[] = $this->getViewLink($url);
                } else {
                    $aDataArray[] = $columns[$name]->getValue();
                }
            }

            $aDatas[] = $aDataArray;
        }
        return $aDatas;
    }
    
    //////////////////////////////////////////
    ///////////////// Utils //////////////////
    //////////////////////////////////////////

    /**
     * Get the repository of the entity
     * @return \Doctrine\ORM\EntityRepository
     */
    final protected function getRepository()
    {
        return $this->em->getRepository($this->getEntityName());
    }


    /**
     * Get a query builder for the entity
     * Entity alias is 'e'
     * @return \Doctrine\ORM\EntityRepository
     */
    final protected function getQueryBuilder()
    {
        $qb =  $this->em->createQueryBuilder();
        $qb->from($this->getEntityName(), 'e');
        return $qb;
    }
    
}
