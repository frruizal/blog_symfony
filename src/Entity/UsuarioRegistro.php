<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class UsuarioRegistro 
{
    /**
     * @Assert\NotBlank
     */
    protected $username;

    /**
     * @Assert\NotBlank
     */
    protected $roles;

    /**
     * @Assert\NotBlank
     */
    protected $password;

    /**
     * @Assert\NotBlank
     */
    protected $nombre;

    /**
     * @Assert\NotBlank
     */
    protected $email;

    public function getUsername(): string
    {
        return $this->username;
    }
    public function setUsername(string $username)
    {
        $this->username = $username;
    }

    public function getRoles(): string
    {
        return $this->roles;
    }
    public function setRoles(string $roles)
    {
        $this->roles = $roles;
    }

    public function getPassword()
    {
        return $this->password;
    }
    public function setPassword($password)
    {
        $this->password = $password;
    }
    public function getNombre()
    {
        return $this->nombre;
    }
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;
    }

    public function getEmail()
    {
        return $this->email;
    }
    public function setEmail($email)
    {
        $this->email = $email;
    }
    
}
