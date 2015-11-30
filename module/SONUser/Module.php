<?php
namespace SONUser;

use Zend\Mail\Transport\Smtp as SmtpTransport;
use Zend\Mail\Transport\SmtpOptions;

use SONUser\Auth\Adapter as AuthAdapter;

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
}
