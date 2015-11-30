<?php

namespace SONUser\Form;

use Zend\Form\Form;

class Login extends Form{
    
    public function __construct($name = null, $options = array()) {
        parent::__construct("login", $options);
        
        $this->setAttribute('method', "post");   
        
        $email = new \Zend\Form\Element\Email("email");
        $email->setLabel("Email: ")
                ->setAttribute("placeholder", "Entre com um email")
                ->setAttribute("class", "form-control");
        $this->add($email);
        
        $password = new \Zend\Form\Element\Password("password");
        $password->setLabel("Senha: ")
                ->setAttribute("placeholder", "Entre com uma senha")
                ->setAttribute("class", "form-control");
        $this->add($password);
        
        $this->add(array(
            'name'  => "submit",
            'type'  => "Zend\Form\Element\Submit",
            'attributes' => array(
                'value' => "Enviar",
                'class' => " btn btn-success"
            )
        ));
        
        
    }
}