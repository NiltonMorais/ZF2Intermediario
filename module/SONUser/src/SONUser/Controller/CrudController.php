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
                ->setDefaultItemCountPerPage(3);
        
        return new ViewModel(array("data" => $paginator, "page" => $page));
    }
    
    public function newAction()
    {
        $form = new $this->form();
        $request = $this->getRequest();
        
        if($request->isPost()){
            $form->setData($request->getPost());
            
            if($form->isValid()){
                $service = $this->getServiceLocator()->get($this->service);
                $service->insert($request->getPost()->toArray());
                
                return $this->redirect()->toRoute($this->route, array("controller" => $this->controller));
            }
        }
        
        return new ViewModel(array("form" => $form));
    }
    
    public function editAction()
    {
        $id = $this->params()->fromRoute('id', 0);
        $form = new $this->form();
        $request = $this->getRequest();        
        
        if($id){
            $repository = $this->getEm()->getRepository($this->entity);
            $entity = $repository->find($id);
            $form->setData($entity->toArray());
        }
        
         if($request->isPost()){
            $form->setData($request->getPost());
            
            if($form->isValid()){
                $service = $this->getServiceLocator()->get($this->service);
                $service->update($request->getPost()->toArray());
                
                return $this->redirect()->toRoute($this->route, array("controller" => $this->controller));
            }
        }
        
        return new ViewModel(array("form" => $form));
    }
    
    public function deleteAction()
    {
        $id = $this->params()->fromRoute('id',0);
        $service = $this->getServiceLocator()->get($this->service);
        if($service->delete($id)){
            return $this->redirect()->toRoute($this->route, array("controller"=>$this->controller));
        }
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
