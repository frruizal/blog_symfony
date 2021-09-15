<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType; 
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use App\Entity\Contacto;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\String\Slugger\SluggerInterface;



class ContactoController extends AbstractController {
    /**
     * @Route("/contacto", name="contacto")
     */
    public function index(Request $request, SluggerInterface $slugger): Response {

        $newContacto = null;
        $contacto = new Contacto();
        $newFilename="";
        $form = $this->createFormBuilder($contacto) 
            ->add('nombre', TextType::class, ['required' => false]) 
            ->add('email', TextType::class, ['required' => false]) 
            ->add('asunto', TextType::class, ['required' => false]) 
            ->add('mensaje', TextareaType::class, ['required' => false]) 
            
            ->add('imagen', FileType::class, [ 
                'mapped' => false,
                'required' => false,
                'constraints' => [ new File([
                'maxSize' => '1024k', 
                'mimeTypes' => [ 'image/png', 'image/jpeg', 'image/gif',], 
                'mimeTypesMessage' => 'El documento tiene que ser una imagen en PNG/JPEG/GIF', 
                        ]) 
                    ], 
                ])
            ->add('save', SubmitType::class,['label' => 'Enviar'])
            ->getForm();
        //$form = $this->createForm(ContactoType::class, $contacto);
        
        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) {
            $newContacto = $form->getData();
            $imagen = $form->get('imagen')->getData();
            
            if ($imagen) { 
                $originalFilename = pathinfo($imagen->getClientOriginalName(), 
                PATHINFO_FILENAME); 
                $safeFilename = $slugger->slug($originalFilename); 
              //$safeFilename = $originalFilename; 
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imagen->guessExtension(); 
                try { 
                    $imagen->move("uploads/".date('n'), $newFilename ); 
                } catch (FileException $e) { 
                    echo "Excepción: {$e->getMessage()}\n";
                }
            }
        }    
        
        return $this->render('contacto/index.html.twig', [
            'controller_name' => 'ContactoController',
            'frmContacto' => $form->createView(),
            'newContacto' => $newContacto,
            'newFilename' => $newFilename
        ]);
    }
}