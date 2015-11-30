<?php
namespace SONUser;

use Zend\Mvc\MvcEvent;

use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

use SONUser\Auth\Adapter as AuthAdapter;

use Zend\Authentication\AuthenticationService,
 Zend\Authentication\Storage\Session as SessionStorage;

use Zend\ModuleManager\ModuleManager;

class Module
{
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
    
    public function init(ModuleManager $moduleManager)
    {
        $sharedEvents = $moduleManager->getEventManager()->getSharedManager();
        $sharedEvents->attach("Zend\Mvc\Controller\AbstractActionController", 
                MvcEvent::EVENT_DISPATCH, 
                array($this, 'validaAuth'), 100);
    }
    
    public function validaAuth($e)
    {
        $auth = new AuthenticationService;
        $auth->setStorage(new SessionStorage("SONUser"));
        
        $controller = $e->getTarget();
        $matchedRoute = $controller->getEvent()->getRouteMatch()->getMatchedRouteName();
        
        if(!$auth->hasIdentity() and ($matchedRoute == "sonuser-admin" || $matchedRoute == "sonuser-admin/paginator")){
            return $controller->redirect()->toRoute("sonuser-auth");
        }
    }
    
    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'SONUser\Mail\Transport' => function($sm){
                    $config = $sm->get('Config');
                    $transport = new SmtpTransport;
                    $options = new SmtpOptions($config['mail']);
                    $transport->setOptions($options);
                    
                    return $transport;
                },
                 'SONUser\Service\User' => function($sm){
                    return new Service\User($sm->get('Doctrine\ORM\EntityManager'),
                                               $sm->get('SONUser\Mail\Transport'),
                                               $sm->get('View'));
                 },
                 'SONUser\Auth\Adapter' => function($sm){
                     return new AuthAdapter($sm->get('Doctrine\ORM\EntityManager'));
                 }
            )
        );
    }
    
    public function getViewHelperConfig()
    {
        return array(
            'invokables' => array(
                'UserIdentity' => 'SONUser\View\Helper\UserIdentity'
            )
        );
    }
}
