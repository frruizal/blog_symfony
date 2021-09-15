<?php

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use App\Form\Type\UsuarioRegistroType;

class RegistroController extends AbstractController
{
    /**
     * @Route("/registro", name="registro")
     */
    public function index(Request $request,UserPasswordEncoderInterface $passwordEncoder): Response
    {
        $usuario = new User();
        $form = $this->createForm(UsuarioRegistroType::class, $usuario);
        //handle y procesado submit
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usuario = $form->getData(); //para mostrar los datos enviados
            $password = $passwordEncoder
            ->encodePassword($usuario, $usuario->getPassword());
            $usuario->setPassword($password);
            $this->getDoctrine()->getManager()->persist($usuario);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("home");
        }

        return $this->render('registro/index.html.twig', [
            'frmRegistro' => $form->createView(),
            'usuario' => $usuario,
        ]);
    }
}
