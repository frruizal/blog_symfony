<?php

namespace App\Controller;

use App\Entity\Categoria;
use App\Repository\CategoriaRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\Type\CategoriaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoriaController extends AbstractController
{
    /**
     * @Route("/admin/categoria", name="categoria")
     */
    public function index(CategoriaRepository $categoriaRepositorio): Response
    {
        /*
        $limit=5;
        $offset=($page-1)* $limit;
        $nMax="";
        $nMaxPages=\Math.ceil($nMax / $limit);
    */
        $lst = $categoriaRepositorio->findAll();
        return $this->render('categoria/index.html.twig', [
            'controller_name' => 'CategoriaController',
            'lst' => $lst
        ]);
    }

    /**
     * @Route("/admin/categoria/delete/{id}", name="categoria_delete")
     */
    public function eliminar($id, CategoriaRepository $categoriaRepositorio)
    {
        $categoria = $categoriaRepositorio->find($id);
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($categoria);
        $entityManager->flush();
        return $this->redirectToRoute("categoria");
    }

    /**
     * @Route("/admin/categoria/add", name="categoria_add")
     * @Route("/admin/categoria/edit/{id}", name="categoria_edit")
     */
    public function edit($id = 0, CategoriaRepository $categoriaRepositorio, Request $request, SluggerInterface $slugger)
    {

        $categoria = new Categoria();
        if ($id != 0) {
            $categoria = $categoriaRepositorio->find($id);
            if ($categoria == null) {
                //flash error
                return $this->redirectToRoute("categoria");
            }
        }
        $form = $this->createForm(CategoriaType::class, $categoria);
        //handle y procesado submit
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $categoria = $form->getData(); //para mostrar los datos enviados

            if ($categoria->getSlug() == null || $categoria->getSlug() == "") {
                $categoria->setSlug(\strtolower($slugger->slug($categoria->getNombre())));
            }

            $this->getDoctrine()->getManager()->persist($categoria);
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute("categoria");
        }

        return $this->render('categoria/edit.html.twig', [
            'frmCategoria' => $form->createView(),
            'categoria' => $categoria,
        ]);
    }
}
