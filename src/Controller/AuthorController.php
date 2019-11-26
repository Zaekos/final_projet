<?php
namespace App\Controller;

use App\Repository\AuthorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class AuthorController extends AbstractController
{

    /**
     * @Route("/author", name="author")
     */

    public function AuthorDbList(AuthorRepository $authorRepository)
    {
        $title = 'Liste des auteurs';
        $author = $authorRepository->findAll();
        return $this->render('author.html.twig',[
            'author' => $author,
            'title' => $title
        
        ]);
    }

    /**
     * @Route("/author/{id}" , name="authorid")
     */

    public function AuthorID(AuthorRepository $authorRepository, $id)
    {
        $title = 'Description de l\' auteur ';
        $authorid = $authorRepository -> find($id);
        return $this->render('authorId.html.twig', [
            'title' => $title,
            'authorid' => $authorid

    ]);
    }


    /**
     * @Route("/author_by_biography/{word}" , name="author_by_biography")
     */

    public function getAuthorsByBiography(AuthorRepository $authorRepository, $word)
    {
        // authorRepository contient une instance de la classe 'AuthorRepository'
        // Generalement on obtient une instance de la classe (ou un objet) en utilisant les mot clé "new"
        // Ici, grace à symfony, on obtient l'instance de la classe repository en la passant simplement en parametre
        //j'appelle la méthode getauthorbybiography de mon repository avec en parametre les mot entré dans l'url par l'uilisateur
        $authors = $authorRepository->getByBiography($word);

        return $this->render('authorbybiography.html.twig' , [
            'author' => $authors,
            'title' => $word
        ]);

    }
}

