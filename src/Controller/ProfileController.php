<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\UserProfileType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class ProfileController extends AbstractController
{
        #[Route('profile', name:"user_profile")]
        
        public function profile(Request $request): Response
        {
            // Get the currently logged-in user
            $user = $this->getUser();
    
            // Create a form instance for the User entity
            $form = $this->createForm(UserProfileType::class, $user);
    
            // Handle form submission and validation
            $form->handleRequest($request);
            if ($form->isSubmitted() && $form->isValid()) {
                // Save the changes to the database
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();
    
                // Redirect to the profile page after successful form submission
                return $this->redirectToRoute('user_profile');
            }
    
            // Render the profile template with the form
            return $this->render('user/profile.html.twig', [
                'form' => $form->createView(),
            ]);
        }
    
    #[Route('/admin',name:"app_admin")]
    public function admin():Response
    {
        $users = $this->getUser();
        return $this->render('admintemplate/base.html.twig',
        ['users' => $users,]);
    }
    
}
