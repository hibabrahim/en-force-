<?php

namespace App\Controller;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;


class UserController extends AbstractController
{
    #[Route('/admin/user_findall', name: 'app_user_index')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('user/hh.html.twig', [
        'users' => $users,
        ]);
    }
    #[Route('/new', name: 'app_new')]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
    $user = new User();
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        // Set the role based on email domain
        $email = $user->getEmail();
        $domain = explode('@', $email)[1] ?? '';
        $role = (strpos($domain, 'esprit.tn') !== false) ? 'ROLE_admin' : 'ROLE_tourist';
        
        // Set the role for the user
        $user->setRole($role);

        // Persist and flush the user entity
        $entityManager->persist($user);
        $entityManager->flush();

        return $this->redirectToRoute('app_login');
    }

    return $this->render('user/signup.html.twig', [
        'form' => $form->createView(),
    ]);
}



    #[Route('show/{id}',name:'app_showuser')]
    public function show(User $user):Response
    {
        return$this->render('user/show.html.twig',[
            'user'=>$user,
        ]);
    }
    #[Route('update/{id}',name:'update_user')]
    public function edit (Request $request,EntityManagerInterface $entityManager,$id,UserRepository $userRepository):Response 
    {   
        
        $user=$userRepository->find($id);
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()){
            $entityManager->flush();
            return $this->redirectToRoute('app_user_index');
        
        }
        return $this->renderForm('user/update.html.twig',[
            'user'=>$user,
            'form'=>$form,
        ]);
    }
    #[Route('/deleteUser/{id}',name:'delete_user')]
    public function delete($id,UserRepository $userRepository,EntityManagerInterface $entityManager):Response
    {
        $user=$userRepository->find($id);
        $entityManager->remove($user);
        $entityManager->flush();
        return $this->redirectToRoute('app_admin');

    }

}
