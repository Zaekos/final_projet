<?php
namespace App\Controller;

use App\Entity\Author;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
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
     * @Route("/author/show/{id}" , name="authorid")
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
    /**
     * @Route("/author/insert", name="author_insert")
     */
    public function insertBook(EntityManagerInterface $entityManager)
    {
        //insérer dans la table author un nouveau auteur
        $title = 'Insertion de l\'auteur';



        $author = new Author();
        $author->setName('Gourcuff');
        $author->setFirstName('Yohan');
        $author->setBirthDate(new \DateTime('2009-04-10'));
        $author->setDeathDate(new \DateTime('2010-10-08'));
        $author->setBiography('Salut tout le monde');

        $entityManager->persist($author);
        $entityManager->flush();

        return $this->render('insertauthor.html.twig',[
            'title' => $title,
            'author' => $author
        ]);



    }
}

