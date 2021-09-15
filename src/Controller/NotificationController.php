<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\NotificationService;
use Symfony\Component\Mailer\Mailer;

class NotificationController extends AbstractController
{
    /**
     * @Route("/email")
     */
    public function sendEmail(NotificationService $notificationService)
    {
        $from="franciscoruizalejos@gmail.com";
        $to="franciscoruizalejos@gmail.com";
        $subject="Hola";
        $text="Hola franciscoruizalejos@gmail.com";
        $notificationService->sendEmail($from,$to,$subject,$text);
        return $this->redirectToRoute("noticia");
    }
}