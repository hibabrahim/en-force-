<?php
namespace App\Controller;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController

    {
         #[Route('/index',name:"index")]
         public function index():Response
         {
             
             return $this->render('index.html.twig');
         }
          #[Route('/authentificated',name:"auth_app")]
          public function index1():Response 
          {
            $user = $this->getUser();
            return $this->render('authentificateduser/index.html.twig',['user' => $user,]);
          }
        
}