<?php
namespace App\Entity;

class Direccion{
    protected $direccion1;
    protected $direccion2;
    protected $localidad;
    protected $cp;
    protected $provincia;

    public function getDireccion1():string {
        return $this->direccion1;
    }
    public function setDireccion1(string $direccion1){
        $this->direccion1 =$direccion1;
    }

    public function getDireccion2():string {
        return $this->direccion2;
    }
    public function setDireccion2(string $direccion2){
        $this->direccion2 =$direccion2;
    }

    public function getLocalidad():string {
        return $this->localidad;
    }
    public function setLocalidad(string $localidad){
        $this->localidad =$localidad;
    }

    public function getCp():string {
        return $this->cp;
    }
    public function setCp(string $cp){
        $this->cp =$cp;
    }

    public function getProvincia():string {
        return $this->provincia;
    }
    public function setProvincia(string $provincia){
        $this->provincia =$provincia;
    }
}