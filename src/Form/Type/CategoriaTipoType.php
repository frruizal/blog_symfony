<?php 

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;

use App\Repository\CategoriaRepository;

class CategoriaTipoType extends AbstractType {

    private $repo;
    public function __construct(CategoriaRepository $repo) {
        $this->repo = $repo;
    }

    public function configureOptions(OptionsResolver $resolver) {
        $lst = $this->repo->findAll();

        $lstChoices = [];
        $lstChoices["Todas las noticias"] = null;
        foreach($lst as $item) {
            $lstChoices[$item->getNombre()] = $item;
        }

        $resolver->setDefaults([ 'choices' => $lstChoices, ]);
    }

    public function getParent() {
        return ChoiceType::class;
    }
}