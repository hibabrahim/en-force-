<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\LoginType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Response;

class AuthentificationController extends AbstractController
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    #[Route("/login", name:"app_login")]
    public function login(Request $request)
    {
        $form = $this->createForm(LoginType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();

            $userRepository = $this->entityManager->getRepository(User::class);
            $user = $userRepository->findOneBy([
                'email' => $data['email']
            ]);

            if (!$user) {
                $this->addFlash('error', 'Invalid email or password');
                return $this->render('user/signin.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            if ($user->getPassword() !== $data['password']) {
                // Password mismatch
                $this->addFlash('error', 'Invalid email or password. Please try again.');
                return $this->render('user/signin.html.twig', [
                    'form' => $form->createView(),
                ]);
            }

            // Authentication successful
            // Create token and set in token storage
            $token = new UsernamePasswordToken($user, null, 'main', $user->getRoles());
            $this->get('security.token_storage')->setToken($token);

            // Redirect based on user role
            if (in_array('ROLE_admin', $user->getRoles(), true)) {
                return $this->redirectToRoute('app_admin');
            } else {
                return $this->redirectToRoute('index');
            }
        }

        // If form is not submitted or invalid, render login form
        $flashMessages = $this->get('session')->getFlashBag()->all();

        return $this->render('user/signin.html.twig', [
            'form' => $form->createView(),
            'flashMessages' => $flashMessages,
        ]);
    }
    
    #[Route("/logout", name: "app_logout")]
    public function logout(): void
    {
        // The logout route is handled by Symfony's security system
        //throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}