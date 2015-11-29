<?php

namespace SONUser\Controller;

class UsersController extends CrudController {
    
    public function __construct()
    {
       $this->entity = "SONUser\Entity\User";
    }
    
}
