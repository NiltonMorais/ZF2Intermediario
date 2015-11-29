<?php

namespace SONUser\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel;

use Zend\Paginator\Paginator,
    Zend\Paginator\Adapter\ArrayAdapter;


abstract class CrudController extends AbstractActionController
{
    protected $em;
    protected $service;
    protected $entity;
    protected $form;
    protected $route;
    protected $controller;
    
    public function indexAction()
    {
        $list = $this->getEm()
                ->getRepository($this->entity)
                ->findAll();
        
        $page = $this->params()->fromRoute("page");
        $paginator = new Paginator(new ArrayAdapter($list));
        $paginator->setCurrentPageNumber($page)
                ->setDefaultItemCountPerPage(2);
        
        return new ViewModel(array("data" => $paginator, "page" => $page));
    }
    
    /**
     * 
     * @return EntityManager
     */
    protected function getEm()
    {
        if($this->em === null){
            $this->em = $this->getServiceLocator()->get ("Doctrine\ORM\EntityManager");
        }
        
        return $this->em;
    }
}
