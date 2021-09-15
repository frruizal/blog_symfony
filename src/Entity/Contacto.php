<?php 

namespace App\Entity;
use Symfony\Component\Validator\Constraints as Assert;

class Contacto {
    /**
    * @Assert\NotBlank(message="El nombre del contacto no puede ser vacio")
    */
    protected $nombre;

    /**
    * @Assert\NotBlank(message="El email no puede ser vacio")
    * @Assert\Email(message="The email no es un email valido")
    */
    protected $email;

    /**
    * @Assert\NotBlank(message="El asunto no puede ser vacio")
    * @Assert\Length(max=40)
    */
    protected $asunto;

    /**
    * @Assert\NotBlank(message="El mensaje no puede ser vacio")
    * @Assert\Length(min=50, max=400)
    */
    protected $mensaje;

    public function getNombre():string {
        return $this->nombre;
    }
    public function setNombre(string $nombre){
        $this->nombre =$nombre;
    }

    public function getEmail():string {
        return $this->email;
    }
    public function setEmail(string $email){
        $this->email =$email;
    }

    public function getAsunto():string {
        return $this->asunto;
    }
    public function setAsunto(string $asunto){
        $this->asunto =$asunto;
    }

    public function getMensaje():string {
        return $this->mensaje;
    }
    public function setMensaje(string $mensaje){
        $this->mensaje =$mensaje;
    }

}
