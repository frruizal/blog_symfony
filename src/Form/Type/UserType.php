<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\User;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class)
            ->add('roles', TipoUsuarioType::class,['mapped' => false])
            ->add('password', RepeatedType::class, array( 
                'type' => PasswordType::class, 
                'first_options' => array('label' => 'Password'),
                'second_options' => array('label' => 'Repeat Password'), 
                ))
            ->add('email', EmailType::class)
          //  ->add('numero', NumberType::class)
            //->add('tipoUsuario', TextType::class)
            ->add('nombre', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Registrar']);
    }

    //Metodo recomendable de implementar. Permiete configurar el type
    public function configureOptions(OptionsResolver $resolver)
    {
        //con el data_class forzamos que las porpiedades a mapear pertenezcan a una entidad de tipo data_class
        $resolver->setDefaults(['data_class' => User::class,]);
    }
    //Tipo usuario type
    /* public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['choices' => [
            'admin' => 'Administrador',
            'user' => 'Usuario',
            'vip' => 'Usuario VIP',
        ],]);
    }
    public function getParent()
    {
        return ChoiceType::class;
    }*/
}
