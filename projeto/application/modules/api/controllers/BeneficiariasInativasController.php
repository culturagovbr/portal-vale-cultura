<?php

include_once 'GenericController.php';

class Api_BeneficiariasInativasController extends ValeCultura_Controller_Rest_Abstract
{

    /**
     * The index action handles index/list requests; it should respond with a
     * list of the requested resources.
     */
    public function indexAction()
    {
        $mdl = new Application_Model_VwBeneficiariaCargaUltima();
        $this->getResponse()->setBody(Zend_Json::encode($mdl->getTotalInativos()));
    }

    /**
     * The get action handles GET requests and receives an 'id' parameter; it
     * should respond with the server resource state of the resource identified
     * by the 'id' value.
     */
    public function getAction(){}

    /**
     * The head action handles HEAD requests and receives an 'id' parameter; it
     * should respond with the server resource state of the resource identified
     * by the 'id' value.
     */
    public function headAction(){}

    /**
     * The post action handles POST requests; it should accept and digest a
     * POSTed resource representation and persist the resource state.
     */
    public function postAction(){}

    /**
     * The put action handles PUT requests and receives an 'id' parameter; it
     * should update the server resource state of the resource identified by
     * the 'id' value.
     */
    public function putAction(){}

    /**
     * The delete action handles DELETE requests and receives an 'id'
     * parameter; it should update the server resource state of the resource
     * identified by the 'id' value.
     */
    public function deleteAction(){}
}

