<?php
namespace Nhacsam\DataTablesBundle\Columns;

/**
 * Base class to represent columns in a dataTable
 * @author Nhacsam
 */
class AttributeColumn extends AbstractColumn
{
    /**
     * {@inheritdoc}
     */
    public function getValue($entity)
    {
        $name = $this->getName();
        $subNames = explode('.', $name);

        $currentEntity = $entity;
        foreach ($subNames as $attr) {

            if (is_array($currentEntity)) {
                $currentEntity = $currentEntity[$attr];

            } else {
                $method ='get'. ucfirst($attr);
                $currentEntity = $currentEntity->$method();
            }
        }

        return $this->stringify($currentEntity);
    }

}