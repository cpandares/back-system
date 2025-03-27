<?php

namespace App\Dtos\clients;

use Error;

class CreateClientDto
{
    private $name;
    private $last_name;
    private $email;
    private $country;
    private $city;
    private $address;
    private $phone;
    private $state;

    public function __construct($name, $last_name, $email,$country,$city,$address,$phone,$state)
    {
        $this->name = $name;
        $this->last_name = $last_name;
        $this->email = $email;
        $this->country = $country;
        $this->city = $city;
        $this->address = $address;
        $this->phone = $phone;
        $this->state = $state;
      
        
    }

    public function registerClientDto(): Error | null
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
        
        if(!$this->email){
            return new Error('Email is required');
        }
        if(!$this->country){
            return new Error('Country is required');
        }
        if(!$this->city){
            return new Error('City is required');
        }
        if(!$this->address){
            return new Error('Address is required');
        }
        if(!$this->phone){
            return new Error('Phone is required');
        }
        if(!$this->state){
            return new Error('State is required');
        }
        return null;

    }
}