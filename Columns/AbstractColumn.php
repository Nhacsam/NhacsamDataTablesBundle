<?php
namespace Nhacsam\DataTablesBundle\Columns;

/**
 * Base class to represent columns in a dataTable
 * @author Nhacsam
 */
abstract class AbstractColumn
{

    /**
     * Name of the column
     * if is in database, use dots to represent foreign keys
     * @var string
     */
    protected $name;
    
    /*
     * Label of the column
     * @var string
     */
    protected $label;

    /**
     * If the rows correspond to a field in the Database
     * @var boolean
     */
    protected $inDatabase;

    /**
     * If the column is sortable
     * @var boolean
     */
    protected $sortable;
    
    
    /**
     * If the column is searchable
     * @var boolean
     */
    protected $searchable;


    /**
     * The type of the column
     * @var integer
     */
    protected $type;

    
    /**
     * The date format
     * @var string
     */
    protected $dateFormat;

    /**
     * The translation domain
     * @var string
     */
    protected $translationDomain;



    /**
     * Available type for the column
     */
    const TYPE_STRING = 1;
    const TYPE_DATE = 2;
    const TYPE_DATETIME = 3;
    const TYPE_INTEGER = 4;
    const TYPE_TIME = 5;
    

    /**
     *
     * @param string $name Name of the column
     * @param string $label Label of the column
     * @param boolean $inDatabase If the rows correspond to a field in the Database
     * @param boolean $sortable If the column is sortable
     * @param boolean $searchable If the column is searchable
     * @param integer $type The type of the column
     * @param string $dateFormat The date format
     */
    public function __construct(
        $name,
        $label = null,
        $type = self::TYPE_STRING,
        $inDatabase = true,
        $sortable = null,
        $searchable = null,
        $dateFormat = 'm-d-Y H:i:s',
        $translationDomain = 'messages'
    ){
        $this->setName($name);
        $this->setLabel($label);
        $this->setType($type);
        $this->setInDatabase($inDatabase);
        $this->setSortable($sortable);
        $this->setSearchable($searchable);
        $this->setDateFormat($dateFormat);
        $this->setTranslationDomain($translationDomain);
    }


    /**
     * Get the name of the column
     * if is in database, use dots to represent foreign keys
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /*
     * Get the label of the column
     * @return string
     */
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * Get if the rows correspond to a field in the Database
     * @return boolean
     */
    public function isInDatabase()
    {
        return $this->inDatabase;
    }

    /**
     * If the column is sortable
     * @return boolean
     */
    public function isSortable()
    {
        return $this->sortable;
    }


    /**
     * If the column is searchable
     * @var boolean
     */
    public function isSearchable()
    {
        return $this->searchable;
    }

    /**
     * Get the type of the column
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get the date format
     * @return string
     */
    public function getDateFormat()
    {
        return $this->dateFormat;
    }

    /**
     * Get The translation domain
     * @return string
     */
    public function getTranslationDomain()
    {
        return $this->translationDomain;
    }

    /**
     * Set the name of the column
     * if is in database, use dots to represent foreign keys
     * @param string $name Name of the column
     * @return AbstractColumn
     */
    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /*
     * Set the label of the column
     * @param string $label Label of the column
     * @return AbstractColumn
     */
    public function setLabel($label)
    {
        if ($label === null) {
            $name = $this->getName();
            $parts = explode('_', $name);
            foreach ($parts as $key => $part) {
                $parts[$key] = ucfirst($part);
            }
            $label = implode(' ', $parts);
        }

        $this->label = $label;
        return $this;
    }

    /**
     * Set if the rows correspond to a field in the Database
     * @param boolean $inDatabase
     * @return AbstractColumn
     */
    public function setInDatabase($inDatabase)
    {
        $this->inDatabase = $inDatabase;
        return $this;
    }

    /**
     * Set if the column is sortable
     * @param boolean $sortable
     * @return AbstractColumn
     */
    public function setSortable($sortable)
    {
        $this->sortable = ($sortable !== null)
            ? $sortable
            : $this->isInDatabase()
        ;
        return $this;
    }

    /**
     * Set If the column is searchable
     * @param boolean $searchable
     * @return AbstractColumn
     */
    public function setSearchable($searchable)
    {
        $this->searchable = ($searchable !== null)
            ? $searchable
            : $this->isInDatabase()
        ;
        return $this;
    }

    /**
     * Set the type of the column
     * @param integer $type
     * @return AbstractColumn
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Set the date format if the field is a date
     * Use the same standart of the date() function 
     * @param string $dateFormat
     * @return AbstractColumn
     *
     */
    public function setDateFormat($dateFormat)
    {
        $this->dateFormat = $dateFormat;
        return $this;
    }

    /**
     * Set The translation domain
     * @var string $translationDomain
     * @return AbstractColumn
     */
    public function setTranslationDomain($translationDomain)
    {
        $this->translationDomain = $translationDomain;
        return $this;
    }


    /**
     * Get the value of the field using the basic entity
     * @return mixed
     */
    abstract public function getValue($entity);


    /**
     * Transform to string according to the given type
     * @param mixed $col Attribute to tranform
     * @return string
     */
    protected function stringify($col)
    {
        $string = '';
        $type = $this->getType();

        if (is_scalar($col)) {
            $string = $col;

        } else if (
            ($type == self::TYPE_DATE || $type == self::TYPE_DATETIME) &&
            $col instanceof \DateTime
        ) {
            $string = $col->format($this->getDateFormat());

        } else if (is_array($col)) {
            $string = implode(', ', $col);

        } else {
            $string = $this->stringifyObj($col);
        }
        return $string;
    }


    /**
     * Stringify an object
     * @param mixed $obj
     * @return string
     */
    private function stringifyObj($obj)
    {
        $reflection = new \ReflectionClass($obj);
        $string = '';

        if ($reflection->hasMethod('__toString')) {
            $string = $obj->__toString();
        } else if ($reflection->hasMethod('getName')) {
            $string = $obj->getName();
        } else if ($reflection->hasMethod('getId')) {
            $string = $obj->getId();
        } else {
            throw new \InvalidArgumentException('Unable to transform an '.  get_class($obj).' to string.');
        }
        return $string;
    }


}

