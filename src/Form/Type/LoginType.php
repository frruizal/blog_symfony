<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; 
use Symfony\Component\Form\Extension\Core\Type\TextType; 
use Symfony\Component\Form\Extension\Core\Type\PasswordType; 
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Entity\Usuario;

class LoginType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('username', TextType::class, ['required' => true])
            ->add('password', PasswordType::class, ['required' => true])
            ->add('save', SubmitType::class, ['label' => 'Iniciar sesion']);
    }

    //Metodo recomendable de implementar. Permiete configurar el type
    public function configureOptions(OptionsResolver $resolver) {
        //con el data_class forzamos que las porpiedades a mapear pertenezcan a una entidad de tipo data_class
        $resolver->setDefaults([ 'data_class' => Usuario::class, ]); 
    }
    
}