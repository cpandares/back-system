<?php

namespace App\Dtos\users;

use Error;

class LoginUserDto
{
    
    private $email;    
    private $password;

    public function __construct($email,$password)
    {
        
        $this->email = $email;       
        $this->password = $password;
    }

    public function loginUserDto(): Error | null
    {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return new Error('Invalid email');
        }
      
        if(!$this->password){
            return new Error('Password is required');
        }
       /*  if(!preg_match('/[0-9]{3}\.[0-9]{3}\.[0-9]{3}-[0-9]{2}/', $this->document)){
            return new Error('Invalid document');
        } */
        if(!$this->email){
            return new Error('Email is required');
        }
        return null;

    }
}