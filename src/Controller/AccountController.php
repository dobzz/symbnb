<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\AccountType;
use App\Entity\PasswordUpdate;
use App\Form\RegistrationType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class AccountController extends AbstractController
{
    /**
     * Permet d'afficher et de génerer le formulaire de connexion
     * @Route("/login", name="account_login")
     * 
     * @return Response
     */
    public function login(AuthenticationUtils $utils)
    {
        $error = $utils->getLastAuthenticationError();
        $username = $utils->getLastUsername();

        return $this->render('account/login.html.twig', [
            'hasError' => $error !== null,
            'username' => $username
        ]);
    }

    /**
     * Permet de se deconnecter
     * @Route("/logout", name="account_logout")
     *
     * @return void
     */
    public function logout(){
        //ne fait rien !! géré par symfony
    }

    /**
     * Permet d'afficher le formulaire de connexion
     *
     * @Route("/register", name="account_register")
     * 
     * @return void
     */
    public function register(Request $request, ObjectManager $manager, UserPasswordEncoderInterface $encoder){
       $user = new User();

       $form = $this->createForm(RegistrationType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user, $user->getHash());
            $user->setHash($hash);

            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                "Votre Compte a bien été créé ! Vous pouvez maintenant vous connecter !"
            );
            return $this->redirectToRoute('account_login');
        }

       return $this->render('account/registration.html.twig', [
            'form' => $form->createView()
       ]);
    }

    /**
     * Permet d'afficher et de raiter le formulaire de modification de profil
     * 
     * @Route("/account/profile", name="account_profil")
     * @IsGranted("ROLE_USER")
     *
     * @return Response
     */
    public function profile(Request $request, ObjectManager $manager){
        $user = $this->getUser();

        $form = $this->createForm(AccountType::class, $user);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $manager->persist($user);
            $manager->flush();

            $this->addFlash(
                'success',
                'Les données du profil ont été enregistrées avec succès !'
            );

        }

        return $this->render('account/profile.html.twig',[
            'form' => $form->createview()
        ]);
    }


    /**
     * permet de modifier le mot de passe
     *
     * @Route("/account/password-update", name="account_password")
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function updatePassword(Request $request,UserPasswordEncoderInterface $encoder ,ObjectManager $manager){
        $passwordUpdate = new PasswordUpdate();

        $user = $this->getUser();

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            // 1. vérifier que le old mdp est le même que le mdp en bdd
            if(!password_verify($passwordUpdate->getOldPassword(), $user->getHash())){
                //gérer l'erreur
                $form->get('oldPassword')->addError(new FormError("Il ne s'agit pas de votre mot de passe actuel ! "));
            }else{
                $newPassword = $passwordUpdate->getNewPassword();
                $hash = $encoder->encodePassword($user, $newPassword);

                $user->setHash($hash);

                $manager->persist($user);
                $manager->flush();

                $this->addFlash(
                    'success',
                    "Votre mot de passe a bien été modifié"
                );

                return $this->redirectToRoute('homepage');
            }
        }

        return $this->render('account/password.html.twig',[
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher le profil de l'utilisateur connecté
     *
     * @Route("/account", name="account_index")
     * 
     * @IsGranted("ROLE_USER")
     * @return Response
     */
    public function myAccount(){

        $user = $this->getUser();
        return $this->render('user/index.html.twig', [
            'user' => $user
        ]);
    }
}
