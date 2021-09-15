<?php 

namespace App\Entity;
class Usuario {
    protected $username;
    protected $password;

    public function getUsername():string {
        return $this->username;
    }
    public function setUsername(string $username){
        $this->username =$username;
    }

    public function getPassword():string {
        return $this->password;
    }
    public function setPassword(string $password){
        $this->password =$password;
    }

}
