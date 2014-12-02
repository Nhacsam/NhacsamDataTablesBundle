<?php
namespace Nhacsam\DataTablesBundle\Columns;

/**
 * Column which rendered with templating
 * @author Nhacsam
 */
class TemplateColumn extends AbstractColumn
{
    /**
     * Template to show
     * @var string
     */
    private $template;
    
    /**
     * The templating component
     * @var Symfony\Component\Templating\EngineInterface
     */
    private $templating;
    
    public function __construct(
        $name,
        $template,
        $label = null,
        $inDatabase = false,
        $sortable = true,
        $searchable = true
    ) {
        $this->template = $template;
        parent::__construct(
            $name, 
            $label, 
            self::TYPE_STRING, 
            $inDatabase, 
            $sortable,
            $searchable
        );
    }    
    
    /**
     * @param Symfony\Component\Templating\EngineInterface
     * @return TemplateColumn
     */
    public function setTemplating($templating)
    {
        $this->templating = $templating;
        return $this;
    }
    
    
    /**
     * {@inheritdoc}
     */
    public function getValue($entity)
    {
        if (! $this->templating || ! $this->template) {
            return '';
        } else {
             return $this->templating->render($this->template, array(
                'entity' => $entity
            ));
        }
    }

}
