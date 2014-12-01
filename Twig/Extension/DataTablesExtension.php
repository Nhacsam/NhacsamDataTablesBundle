<?php

namespace Nhacsam\DataTablesBundle\Twig\Extension;

use Nhacsam\DataTablesBundle\Columns\AbstractColumn;

/**
 * Description of dataTablesExtension
 *
 * @author Nhacsam
 */
class DataTablesExtension extends \Twig_Extension
{


    /*
     * The DataTables Container
     * @var \NhacsamDataTablesBundle\Services\DataTablesContainer
     */
    private $dtContainer;

    /**
     * The Templating component
     * @var TODO
     */
    private $templating;


    /**
     * Ccnstruct
     * @param type $factory
     * @param type $templating
     */
    public function __construct($dtContainer)
    {
        $this->dtContainer = $dtContainer;
    }

    /**
     * {@inheritdoc}
     */
    public function initRuntime(\Twig_Environment $templating)
    {
        $this->templating = $templating;
    }


    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        return array(
            'dataTable' => new \Twig_Function_Method($this, 'dataTable', array('is_safe' => array('html'))),
            'dataTableJs' => new \Twig_Function_Method($this, 'dataTableJs', array('is_safe' => array('html'))),
            'dataTableCss' => new \Twig_Function_Method($this, 'dataTableCss', array('is_safe' => array('html'))),
            'dataTableType' => new \Twig_Function_Method($this, 'dataTableType', array('is_safe' => array('html'))),
        );
    }


    public function dataTable($entity_name)
    {
        $builder = $this->dtContainer->getDTBuilder($entity_name);
        $template = 'NhacsamDataTablesBundle:DataTables:table.html.twig';
        return $this->templating->render($template, array(
            'builder' => $builder
        ));
    }

    public function dataTableJs($entity_name, $includeRequiredJs = false)
    {
        $builder = $this->dtContainer->getDTBuilder($entity_name);
        $template = 'NhacsamDataTablesBundle:DataTables:tableJs.html.twig';
        return $this->templating->render($template, array(
            'builder' => $builder,
            'includeRequiredJs' => $includeRequiredJs
        ));
    }

    public function dataTableCss()
    {
        $template = 'NhacsamDataTablesBundle:DataTables:tableCss.html.twig';
        return $this->templating->render($template);
    }


    public function dataTableType($type, $dateFormat) {
        switch ($type) {
            case AbstractColumn::TYPE_STRING:
                return 'null';
            case AbstractColumn::TYPE_INTEGER:
                return '"num"';

            case AbstractColumn::TYPE_TIME:
                return '"time"';

            case AbstractColumn::TYPE_DATE:
                if ($dateFormat == 'd-m-Y') {
                    return '"date-dd-MMM-yyyy"';
                } else if ($dateFormat == 'd.m.Y') {
                    return '"date-de"';
                } else if ($dateFormat == 'd/m/Y' || $dateFormat == 'd/m/y') {
                    return '"date-eu"';
                } else {
                    return '"date"';
                }
            case AbstractColumn::TYPE_DATETIME:
                if ($dateFormat == 'd/m/Y H:i:s') {
                    return '"date-euro"';
                } else if ($dateFormat == 'd.m.Y H:i') {
                    return '"date-de"';
                } else {
                    return '"date"';
                }
            default:
                return '"null"';
        }
    }




    public function getName()
    {
        return 'dataTables.extension';
    }
}