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
    #[Route('/user_findall', name: 'app_user_index')]
    public function index(UserRepository $userRepository): Response
    {
        $users = $userRepository->findAll();
        return $this->render('user/hh.html.twig', [
        'users' => $users,
        ]);
    }
    #[Route('/new', name: 'app_new')]
    public function new(Request $request, EntityManagerInterface $entity): Response
    {
    $user = new User();
    $form = $this->createForm(UserType::class, $user);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entity->persist($user);
        $entity->flush();
        return $this->redirectToRoute('app_login', []);
    }

    return $this->renderForm('user/signup.html.twig', [
        'user' => $user,
        'form' => $form,
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
        return $this->redirectToRoute('app_user_index');

    }

}
