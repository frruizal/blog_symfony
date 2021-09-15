<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use App\Repository\CategoriaRepository;
use App\Repository\NoticiaRepository;
use App\Form\Type\CategoriaTipoType;

class HomeController extends AbstractController
{
    /**
     * @Route("/", name="raiz")
     */
    public function raiz(): Response
    {
        return $this->redirectToRoute("home");
    }

    /**
     * @Route("/home/{page<\d+>}", name="home")
     * @Route("/home/{slugCategoria}/{page<\d+>}", name="home_cat")
     */
    public function index($page = 1, $slugCategoria = null, Request $req, NoticiaRepository $repo, CategoriaRepository $catRepo): Response
    {
        //$idCategoria = null;

        $form = $this->createFormBuilder(null)
            ->setMethod("get")
            ->setAction($this->generateUrl("home", ["page" => 1]))
            ->add('categoria', CategoriaTipoType::class, ["mapped" => false])
            ->add('buscar', SubmitType::class)
            ->getForm();

        if ($slugCategoria != null) {
            $form->get("categoria")->setData($catRepo->findOneBy(["slug" => $slugCategoria]));
        }

        $form->handleRequest($req);
        if ($form->isSubmitted()) {
            $cat = $form->get("categoria")->getData();
            if ($cat != null) {
                $slugCategoria = $cat->getSlug();

                return $this->redirectToRoute("home_cat", ["page" => 1, "slugCategoria" => $slugCategoria]);
            } else {
                return $this->redirectToRoute("home", ["page" => 1]);
            }
        }

        $arr = $repo->getAllPaginado(null, $slugCategoria, $page);

        if ($page > 1 && count($arr["res"]) == 0) { //estar en una pag ej 2 sin results
            //pag no existe
            return $this->redirectToRoute("home", ["page" => 1]);
        }

        return $this->render('home/index.html.twig', [
            'lstNoticias' => $arr["res"],
            'frmBusqueda' => $form->createView(),
            'page' => $page,
            'nmaxPages' => $arr["nmaxPages"],
            'isBuscando' => ($slugCategoria != null),
            'router_vista_paginacion' => ($slugCategoria!=null) ? "home_cat" : "home",
            'extra_param_paginacion' => ($slugCategoria!=null) ? ["slugCategoria" => $slugCategoria] : []
        ]);
    }
}
