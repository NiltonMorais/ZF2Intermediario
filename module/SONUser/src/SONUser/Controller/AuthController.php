<?php

namespace SONUser\Controller;

use Zend\Mvc\Controller\AbstractActionController,
    Zend\View\Model\ViewModel;

use Zend\Authentication\AuthenticationService,
    Zend\Authentication\Storage\Session as SessionStorage;

use SONUser\Form\Login as LoginForm;

class AuthController extends AbstractActionController
{
    public function indexAction()
    {
        $error = false;
        $form = new LoginForm();
        $request = $this->getRequest();
        
        if($request->isPost()){
            $form->setData($request->getPost());
            if($form->isValid()){
                $data = $request->getPost()->toArray();
                
                $sessionStorage = new SessionStorage("SONUser");
                $authAdapter = $this->getServiceLocator()->get("SONUser\Auth\Adapter");
                
                $auth = new AuthenticationService;
                $auth->setStorage($sessionStorage);
                $auth->setUsername($data['email']);
                $auth->setPassword($data['password']);
                
                $result = $auth->authenticate($authAdapter);
                
                if($result->isValid()){
                    $user = $auth->getIdentity();
                    $sessionStorage->write($user['user']);
                    return $this->redirect()->toRoute('sonuser-admin/default');
                }
                else{
                    $error = true;
                }              
                
            }
        }
        return new ViewModel(array("form" => $form, "error" => $error));
    }
}
