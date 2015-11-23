<?php
return array(
    'mail' => array(
        'name' => 'smtp.googlemail.com',
        'host' => 'smtp.googlemail.com',
        'connection_class' => 'login',
        'connection_config' => array(
            'username' => 'teste@gmail.com',
            'password' => '123',
            'ssl'   =>  'tls',
            'port'  =>  465,
            'from'  => 'nilton_even@hotmail.com'            
        )
    )
);
