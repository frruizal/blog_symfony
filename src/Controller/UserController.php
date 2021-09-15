<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Type\UsuarioRegistroType;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserController extends AbstractController
{
    /**
     * @Route("/admin/user/{page<\d>}", name="user")
     */
    public function index($page=1,UserRepository $userRepositorio): Response
    {
        $arr = $userRepositorio->getAllPaginado(null, null, $page);
        //$lst = $userRepositorio->findAll();
        if($page>1 && count($arr["res"])==0) {
            return $this->redirectToRoute("user", ["page"=>1]);
        }

        return $this->render('user/index.html.twig', [
            'controller_name' => 'CategoriaController',
            'lst' => $arr["res"],
            'nmaxPages' => $arr["nmaxPages"]
        ]);
    }
    /**
     * @Route("/admin/user/add", name="user_add")
     * @Route("/admin/user/edit/{id}", name="user_edit")
     */
    public function edit($id = 0, UserRepository $userRepositorio,Request $request,UserPasswordHasherInterface $hasher): Response
    {
        $usuario = new User();
        if ($id != 0) {
            $usuario = $userRepositorio->find($id);
            if ($usuario == null) {
                //flash error
                return $this->redirectToRoute("user");
            }
        }
        $form = $this->createForm(UsuarioRegistroType::class, $usuario, ['isPanelControl'=>true]);

        $rol = "ROLE_USER";
        foreach($usuario->getRoles() as $role) {
            if($role!="ROLE_USER") {
                $rol = $role;    
            }
        }
        $form->get('role')->setData($rol);

        //handle y procesado submit
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $usuario = $form->getData(); //para mostrar los datos enviados

            $roles = [];
            $rol = $form->get('role')->getData();
            if($rol != "ROLE_USER") {
                $roles[] = $rol;
            }
            $usuario->setRoles($roles);

            $password = $form->get('password')->getData();
            if($password!="") {
                $usuario->setPassword($hasher->hashPassword($usuario, $password));
            }

            $this->getDoctrine()->getManager()->persist($usuario);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("user");
        }

        return $this->render('user/edit.html.twig', [
            'frmUser' => $form->createView(),
            'usuario' => $usuario,
        ]);
    }

     /**
     * @Route("/admin/user/delete/{id}", name="user_delete")
     */
    public function eliminar($id, UserRepository $userRepositorio)
    {
        $user = $userRepositorio->find($id);
        if ($user != null){
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($user);
            $entityManager->flush();
        }
        return $this->redirectToRoute("user");
    }
}
