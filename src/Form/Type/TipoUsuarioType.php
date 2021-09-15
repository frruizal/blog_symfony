<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\UsuarioRegistro;

class TipoUsuarioType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['choices' => [
            'Usuario' => '["ROLE_USER"]',
            'Administrador' => '["ROLE_ADMIN"]',
            'Super Administrador' => '["ROLE_SUPERADMIN"]',
            'Editor' => '["ROLE_EDITOR"]',
        ],]);
    }
    public function getParent()
    {
        return ChoiceType::class;
    }
}
