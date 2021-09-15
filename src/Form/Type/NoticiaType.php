<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; 
use Symfony\Component\Form\Extension\Core\Type\TextType; 
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType; 
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Form\Type\CategoriaType;
use App\Entity\Noticia;
use DateTime;

class NoticiaType extends AbstractType {
    public function buildForm(FormBuilderInterface $builder, array $options) {
        $builder
            ->add('titulo', TextType::class, ['required' => true])
            ->add('cuerpo', TextType::class, ['required' => true])
            ->add('categoria', EntityType::class, array(
                'class' => 'App\Entity\Categoria',
                'choice_label' => 'nombre'
            ))
            ->add('autor', TextType::class, ['required' => true])
            ->add('imagen', TextType::class, ['required' => true]) //enlace de la imagen
            ->add('fechaPublicacion', DateTimeType::class, ['required' => true])
            ->add('save', SubmitType::class, ['label' => 'Guardar noticia']);
    }

    //Metodo recomendable de implementar. Permiete configurar el type
    public function configureOptions(OptionsResolver $resolver) {
        //con el data_class forzamos que las porpiedades a mapear pertenezcan a una entidad de tipo data_class
        $resolver->setDefaults([ 'data_class' => Noticia::class, ]); 
    }
    
}