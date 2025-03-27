<?php

namespace App\Dtos\users;

use Error;

class RegisterUserDto
{
    private $name;
    private $last_name;
    private $email;
    private $document;
    private $password;

    public function __construct($name, $last_name, $email, $document, $password)
    {
        $this->name = $name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->document = $document;
        $this->password = $password;
    }

    public function registerUserDto(): Error | null
    {
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            return new Error('Invalid email');
        }
        
        if(!$this->name){
            return new Error('Name is required');
        }
        if(!$this->last_name){
            return new Error('Last name is required');
        }
        if(!$this->document){
            return new Error('Document is required');
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