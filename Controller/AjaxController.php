<?php
namespace Nhacsam\DataTablesBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Controller of ajax request from the dataTables
 *
 * @author Nhacsam
 */
class AjaxController  extends Controller
{

    /**
     * Get the dataTAbles ajax request tho process the server side filtering
     *
     * @param Request $request
     * @param string $builder_name The name the builder to use
     * @return JsonResponse
     */
    public function serverSideAction(Request $request, $builder_name)
    {
        $params = $request->query->all();

        $builder = $this->get('datatables.container')->getDTBuilder($builder_name);
        $builder->setTemplating($this->get('templating'));

        $return['draw'] = intval($params['draw']);
        $return['recordsTotal'] = $builder->countAllEntities();

        try {
            $return['data'] = $builder->getDataTableAjaxData($params);
            $return['recordsFiltered'] = $builder->countDataTableAjaxData($params);
        } catch (\Exception $e) {
            $return['data'] = array();
            $return['recordsFiltered'] = 0 ;
            $return['error'] = $e->getMessage() ;
        }
        return new JsonResponse($return);
    }

}
