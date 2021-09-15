<?php

namespace App\Security;

use App\Entity\Noticia;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;

class NoticiaVoter extends Voter {

    const EDIT = "edit";
    const DELETE = "delete";

    private $security;

    public function __construct(Security $security){
        $this->security = $security;    
    }

    protected function supports(string $attribute, $subject) {
        if(!in_array($attribute, array(self::EDIT, self::DELETE))) {
            return false;
        }

        if(!$subject instanceof Noticia) {
            return false;
        }

        return true;
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token) {
        if($this->security->isGranted('ROLE_ADMIN')) {
            return true;
        } else if(!$this->security->isGranted('ROLE_EDITOR')) {
            return false;
        }

        $username = $token->getUser()->getUserIdentifier();
        if($subject->getAutor()==null || $subject->getAutor() == "" || $subject->getAutor() == $username) {
            return true;
        }

        return false;
    }
}
