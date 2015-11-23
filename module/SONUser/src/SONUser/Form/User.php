<?php

namespace SONUser\Form;

use Zend\Form\Form;

class User extends Form{
    
    public function __construct($name = null, $options = array()) {
        parent::__construct("user", $options);
        
        $this->setAttribute('method', "post");
        
        $id = new Zend\Form\Element\Hidden('id');
        $this->add($id);
        
        $nome = new Zend\Form\Element\Text("nome");
        $nome->setLabel("Nome: ")
                ->setAttribute("placeholder", "Entre com um nome");
        $this->add($nome);        
        
        $email = new Zend\Form\Element\Email("email");
        $email->setLabel("Email: ")
                ->setAttribute("placeholder", "Entre com um email");
        $this->add($email);
        
        $password = new Zend\Form\Element\Password("password");
        $password->setLabel("Senha: ")
                ->setAttribute("placeholder", "Entre com uma senha");
        $this->add($password);
        
        $confirmation = new Zend\Form\Element\Password("confirmation");
        $confirmation->setLabel("Confirmar: ")
                ->setAttribute("placeholder", "Confirme sua senha");
        $this->add($confirmation);
        
        $csrf = new Zend\Form\Element\Csrf("security");
        $this->add($csrf);
        
        $this->add(array(
            'name'  => "submit",
            'type'  => "Zend\Form\Element\Submit",
            'attributes' => array(
                'value' => "Salvar",
                'class' => "btn-success"
            )
        ));
        
        
    }
}