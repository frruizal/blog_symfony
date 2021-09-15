<?php

namespace App\Controller;

use App\Entity\Noticia;
use App\Form\Type\NoticiaType;
use App\Form\Type\CategoriaTipoType;
use App\Repository\NoticiaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Repository\CategoriaRepository;
use Symfony\Component\Security\Core\Security;
use App\Service\FileUploader;
use Symfony\Component\String\Slugger\SluggerInterface;

class NoticiaController extends AbstractController
{

    /**
     * @Route("/admin/noticia/{page<\d+>}", name="noticia")
     */
    public function index($page = 1, NoticiaRepository $repo): Response
    {
        $arr = $repo->getAllPaginado(null, null, $page);
        if ($page > 1 && count($arr["res"]) == 0) { //estar en una pag ej 2 sin results
            //pag no existe
            return $this->redirectToRoute("noticia", ["page" => 1]);
        }

        //
        //$lst = $noticiaRepositorio->findAll();
        return $this->render('noticia/index.html.twig', [
            'controller_name' => 'NoticiaController',
            //'lst' => $lst
            'lst' => $arr["res"],
            'page' => $page,
            'nmaxPages' => $arr["nmaxPages"],
        ]);
    }

    /**
     * @Route("/admin/noticia/delete/{id}", name="noticia_delete")
     */
    public function eliminar($id, NoticiaRepository $noticiaRepositorio)
    {
        $noticia = $noticiaRepositorio->find($id);
        if ($this->isGranted('delete', $noticia)) {
            return $this->redirectToRoute("noticia");
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($noticia);
        $entityManager->flush();
        return $this->redirectToRoute("noticia");
    }

    /**
     * @Route("/admin/noticia/add", name="noticia_add")
     * @Route("/admin/noticia/edit/{id}", name="noticia_edit")
     */
    public function edit($id = 0, NoticiaRepository $noticiaRepositorio,  SluggerInterface $slugger, Request $request, Security
    $security, FileUploader $fileUploader)
    {

        $noticia = new Noticia();
        if ($id != 0) {
            $noticia = $noticiaRepositorio->find($id);
            if ($noticia == null) {
                //flash error
                return $this->redirectToRoute("noticia");
            }
        }
        if ($this->isGranted('edit', $noticia)) {
            return $this->redirectToRoute("noticia");
        }
        if ($noticia->getAutor() != "" && $security->getUser() != null) {
            $noticia->setAutor($security->getUser()->getUserIdentifier());
        }
        $form = $this->createForm(NoticiaType::class, $noticia);
        //handle y procesado submit
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $noticia = $form->getData(); //para mostrar los datos enviados

            $pathToImage = "";
            $imagenSubida = $form->get("imagen")->getData(); //$_FILES["imagen"]
            if ($imagenSubida) {
                $originalFilename = $fileUploader->upload($imagenSubida);/*
                $safeFilename = $slugger->slug($originalFilename); 
                $newFilename = $safeFilename.'-'.uniqid().'.'.$imagenSubida->guessExtension(); 
                $path = "uploads/".date('n');

                $pathToImage = $path."/".$newFilename;
                try {
                    $imagenSubida->move($path, $newFilename); 
                } catch (FileException $e) {  }*/
            }

            if ($pathToImage != null && $pathToImage != "") {
                if ($noticia->getImagen() != "") {
                    \unlink($noticia->getImagen());
                }

                $noticia->setImagen($pathToImage);
            }

            $this->getDoctrine()->getManager()->persist($noticia);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("noticia");
        }

        return $this->render('noticia/edit.html.twig', [
            'frmNoticia' => $form->createView(),
            'noticia' => $noticia,
        ]);
    }
}
