<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\User;
use App\Form\UserType;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class UserController extends AbstractController
{
    #[Route('/', name: 'list_user')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $users = $em->getRepository(User::class)->findAll();
        return $this->render('user/index.html.twig', [
            'users' => $users,
        ]);
    }

    #[Route('/user/new',name:'new_user')]
    public function new(Request $request,ManagerRegistry $doctrine,ValidatorInterface $validator) {
        $user = new User();
        $em = $doctrine->getManager();
        $form = $this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();
            $user->setActive(true);
            $em->persist($user);
            $em->flush();
            $this->addFlash(
                'notice',
                'User has  been created'
            );
            return $this->redirectToRoute('list_user');
        }//else{dd($user->get);}
        return $this->render('user/new.html.twig', [
            'form' =>$form->createView(),
        ]);
    }

    #[Route('/user/edit/{id}',name:'edit_user')]
    public function edit(User $user,Request $request,ManagerRegistry $doctrine,ValidatorInterface $validator) {
        $em = $doctrine->getManager();
        $form = $this->createForm(UserType::class,$user);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash(
                'notice',
                'User has been updated.'
            );
            return $this->redirectToRoute('list_user');
        }
        return $this->render('user/new.html.twig', [
            'form' =>$form->createView(),
        ]);
    }

    #[Route('/user/del/{id}',name:'del_user')]
    public function del(User $user,ManagerRegistry $doctrine) {
        $em = $doctrine->getManager();
        if($user) {
            $user->setActive(false) ;
            $em->persist($user);
            $em->flush();
        }

        $this->addFlash(
            'notice',
            'User has been deleted.'
        );

        return $this->redirectToRoute('list_user');
    }
   
}
