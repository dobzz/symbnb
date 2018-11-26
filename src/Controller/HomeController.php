<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller{

    /**
     * @Route("/hello", name="hello_base")
     * @Route("/hello/{prenom}", name="hello_prenom")
     * Montre la page qui dit bonjour
     * 
     * @return void
     */
    public function hello($prenom = null){
        return $this->render(
            'hello.html.twig',
            [
                'prenom' => $prenom
            ]
        );
    }

    /** 
    * @Route("/", name="homepage")
    */
    public function home(){
        $prenoms = ["François" => 31, "Guillame" => 12 , "Rodolphe" => 55];

        //return new Response(""); // Reponse de base Symfony
        return $this->render(
            'home.html.twig',
            [
                'title' => "dfdf",
                'age' => "17",
                'prenoms' => $prenoms
            ]

        );
    }


}

?>