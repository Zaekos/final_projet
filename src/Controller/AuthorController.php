<?php
namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
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

    public function AuthorList(AuthorRepository $authorRepository)
    {
        $title = 'Liste des auteurs';
        $author = $authorRepository->findAll();
        return $this->render('author/author_public.html.twig',[
            'author' => $author,
            'title' => $title

        ]);
    }
    /**
     * @Route("/admin/author", name="author_admin")
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
     * @Route("/admin/author/insert", name="author_insert")
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
    // Pouvoir supprimer un author en bdd
    /**
     * Commentaire qui est une annotation et qui permet de créer
     * une url "/article" qui appelle la méthode "deleteauthor"
     *
     * @Route("/admin/author/delete{id}", name="author_delete")
     */
    //J'utilise le repository de Author pour pouvoir selectionner tous les élèments de ma table Author
    //Les repositorys en général servent à faire les requêtes select dans les tables
    //EntityManager : Une classe qui contient des methodes pour acceder en ecriture à la base de données
    public function deleteAuthor(AuthorRepository $authorRepository, EntityManagerInterface $entityManager, $id)
    {
        $title = 'Suppression de l\'auteur';
        // Je récupère un enregistrement author en BDD grâce au repository de author
        $author = $authorRepository->find($id);
        // j'utilise l'entity manager avec la méthode remove pour enregistrer
        // la suppression de l'author dans l'unité de travail
        $entityManager->remove($author);
        // je valide la suppression en bdd avec la méthode flush
        $entityManager->flush();

        return $this->redirectToRoute('author_admin');
    }
    /**
     * @Route("/admin/author/update/{id}", name="author_update")
     */
    public function updateAuthor(AuthorRepository $authorRepository, EntityManagerInterface $entityManager, $id)
    {
        // j'utilise le Repository de l'entité Author pour récupérer un auteur
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

    /**
     * @Route("/admin/author/insert_form", name="author_insert_form")
     */
    public function insertAuthorForm(Request $request, EntityManagerInterface $entityManager)
    {
        // J'utilise le gabarit de formulaire pour créer mon formulaire
        // j'envoie mon formulaire à un fichier twig
        // et je l'affiche
        // je crée un nouveau Author,
        // en créant une nouvelle instance de l'entité Author
        $title = 'Formulaire d\'auteur';
        $author = new Author();
        // J'utilise la méthode createForm pour créer le gabarit / le constructeur de
        // formulaire pour le Author : AuthorType (que j'ai généré en ligne de commandes)
        // Et je lui associe mon entité Author vide
        $authorForm = $this->createForm(AuthorType::class, $author);
        // Si je suis sur une méthode POST
        // donc qu'un formulaire a été envoyé
        if ($request->isMethod('Post')) {
            // Je récupère les données de la requête (POST)
            // et je les associe à mon formulaire
            $authorForm->handleRequest($request);
            // Si les données de mon formulaire sont valides
            // (que les types rentrés dans les inputs sont bons,
            // que tous les champs obligatoires sont remplis etc)
            if ($authorForm->isValid()) {
                // J'enregistre en BDD ma variable $author
                // qui n'est plus vide, car elle a été remplie
                // avec les données du formulaire
                $entityManager->persist($author);
                $entityManager->flush();
            }
        }
        // à partir de mon gabarit, je crée la vue de mon formulaire
        $authorFormView = $authorForm->createView();
        // je retourne un fichier twig, et je lui envoie ma variable qui contient
        // mon formulaire
        return $this->render('author/author_form.html.twig', [
            'authorFormView' => $authorFormView,
            'title' => $title

        ]);
    }
    /**
     * @Route("/admin/author/update_form/{id}", name="author_update_form")
     */
    //J'utilise le repository de Author pour pouvoir selectionner tous les élèments de ma table Author
    //Les repositorys en général servent à faire les requêtes select dans les tables
    //Request : info qui transit entre le front et le back
    //EntityManager : Une classe qui contient des methodes pour acceder en ecriture à la base de données

    public function updateAuthorForm(AuthorRepository $authorRepository,
                                     Request $request,
                                     EntityManagerInterface $entityManager, $id)
    {
        $title = 'Formulaire de mis à jour d\un auteur';
        // j'utilise le Repository de l'entité Author pour récupérer un auteur
        // en fonction de son id
        $author = $authorRepository->find($id);
        // J'utilise la méthode createForm pour créer le gabarit / le constructeur de
        // formulaire pour le Author : AuthorType (que j'ai généré en ligne de commandes)
        // Et je lui associe mon entité Author vide
        $authorForm = $this->createForm(AuthorType::class, $author);
        if ($request->isMethod('Post'))
        {
            $authorForm->handleRequest($request);
            if ($authorForm->isValid()) {
                $entityManager->persist($author);
                $entityManager->flush();
            }
        }
        // à partir de mon gabarit, je crée la vue de mon formulaire
        $authorFormView = $authorForm->createView();
        // je retourne un fichier twig, et je lui envoie ma variable qui contient
        // mon formulaire
        return $this->render('author/author_form.html.twig', [
            'authorFormView' => $authorFormView,
            'title' => $title
        ]);
    }
}


