<?php
namespace Nhacsam\DataTablesBundle\Columns;

/**
 * Column which are entity attribute with no foreign keys and which return string
 * @author Nhacsam
 */
class SimpleAttributeColumn extends AttributeColumn
{
    /**
     * {@inheritdoc}
     */
    public function getValue($entity)
    {
        $attr = $this->getName();
        $method ='get'. ucfirst($attr);
        return $entity->$method();
    }

}