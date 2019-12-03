<?php
namespace App\Controller;



use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AccueilController extends AbstractController

{
    /**
     * @Route("/accueil", name="accueil")
     */
    public function AccueilShow()
    {
        $title = 'Bienvenue dans notre bibliothèque';
        return $this->render('accueil_public.html.twig',[

            'title' => $title

        ]);
    }
}