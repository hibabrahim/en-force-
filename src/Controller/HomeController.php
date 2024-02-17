<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Repository\EmployeRepository;
use App\Repository\CongeRepository;
use Doctrine\ORM\EntityManagerInterface;


class HomeController extends AbstractController
{
    #[Route('/index',name:"index")]
    public function index():Response
    {
        
        return $this->render('index.html.twig');
    }
    #[Route('/back',name:"back")]
    public function back():Response
    {
        
        return $this->render('admin/home.html.twig');
    }
	
	
	
	    #[Route('/dashboard', name: 'app_dash')]
    public function index2(): Response
    {
        return $this->render('home/index2.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
	


	
	
	
	
}
