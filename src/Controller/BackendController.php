<?php

namespace App\Controller;


use App\Entity\User;
use App\Form\RegistrationFormType;

use App\Repository\UserRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;




class BackendController extends AbstractController
{

    

//*****************************************************************************************user back ***********************************************************************


    /**
     * @return Response
     * @route ("/afficheC" , name ="afficheC")
     */
    public function AffichUser (userRepository $repo   ) {
        $abo=$repo->findAll() ;
        return $this->render('backend/afficheruser.html.twig' , [
            'User' => $abo ,
            'ajoutC' => $abo
        ]) ;
    }

    /**
     * @param Request $request
     * @param UserPasswordHasherInterface $userPasswordEncoder
     * @param ManagerRegistry $doctrine
     * @return Response
     * @Route ("/ajoutC" , name="ajoutC")
     */
    function adduser (Request  $request , UserPasswordHasherInterface $userPasswordEncoder , ManagerRegistry $doctrine) {
        $user =  new User () ;
        $formC =  $this->createForm(RegistrationFormType::class,$user) ;
        $formC->add('Ajouter' , SubmitType::class) ;
        $formC->handleRequest($request) ;
        if($formC->isSubmitted()&& $formC->isValid()){
            $user->setPassword(
                $userPasswordEncoder->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $em =  $doctrine->getManager() ;
            $em->persist($user);
            $em->flush();
            return $this ->redirectToRoute('afficheC') ;
        }
        return $this->render('backend/ajouteruser.html.twig' , [
            'formC' => $formC->createView()
        ]) ;
    }
    /**
     * @return void
     * @route ("/updatC{id}" , name="upc")
     */
    function updatec(UserRepository $repo,$id,Request $request,ManagerRegistry $doctrine){
        $user = $repo->find($id) ;
        $formC=$this->createForm(RegistrationFormType::class,$user) ;
        $formC->add('update' , SubmitType::class) ;
        $formC->handleRequest($request) ;
        if($formC->isSubmitted()&& $formC->isValid()){

            $em =  $doctrine->getManager() ;
            $em->flush();
            return $this ->redirectToRoute('afficheC') ;
        }
        return $this->render('backend/updateuser.html.twig' , [
            'formC' => $formC->createView()
        ]) ;

    }

    /**
     * @return void
     * @route ("/deleteC/{id}" ,name ="deleteC" )
     */
    function Deletec($id,UserRepository $repository,ManagerRegistry $doctrine) {
        $user=$repository->find($id) ;
        $em =  $doctrine->getManager() ;
        $em->remove($user);
        $em->flush();
        return $this->redirectToRoute("afficheC") ;

    }
}
