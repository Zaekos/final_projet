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
        return $this->render('author/author.html.twig',[
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
        return $this->render('author/authorId.html.twig', [
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

        return $this->render('author/authorbybiography.html.twig' , [
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

        return $this->render('author/insertauthor.html.twig',[
            'title' => $title,
            'author' => $author
        ]);



    }
    // pouvoir supprimer un book en bdd
    /**
     * @Route("/author/delete{id}", name="author_delete")
     */
    public function deleteBook(AuthorRepository $authorRepository, EntityManagerInterface $entityManager, $id)
    {
        $title = 'Suppression de l\'auteur';
        // Je récupère un enregistrement author en BDD grâce au repository de author
        $author = $authorRepository->find($id);
        // j'utilise l'entity manager avec la méthode remove pour enregistrer
        // la suppression de l'author dans l'unité de travail
        $entityManager->remove($author);
        // je valide la suppression en bdd avec la méthode flush
        $entityManager->flush();

        return $this->redirectToRoute('author');
    }
    /**
     * @Route("/author/update{id}", name="author_update")
     */
    public function updateAuthor(AuthorRepository $authorRepository, EntityManagerInterface $entityManager, $id)
    {
        // j'utilise le Repository de l'entité Book pour récupérer un livre
        // en fonction de son id
        $author = $authorRepository->find($id);
        // Je donne un nouveau titre à mon entité Book
        $author->setName('Jean-Claude');
        $author->setFirstName('Van Dammmn');
        // je re-enregistre mon livre en BDD avec l'entité manager
        $entityManager->persist($author);
        $entityManager->flush();

        return $this->redirectToRoute('author');
    }
}


