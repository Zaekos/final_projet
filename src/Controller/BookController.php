<?php
namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

class BookController extends AbstractController
{

    /**
     * @Route("/book", name="book")
     */
    //méthode qui permet de faire "un select" en BDD de l'ensemble de mes champs dans ma table Book
    public function BookDbList(BookRepository $bookRepository)
    {
        $title = 'Liste des livres';
        //J'utilise le repository de book pour pouvoir selectionner tous les élèments de ma tablebook
        //Les repositorys en général servent à faire les requêtes select dans les tables
        $book = $bookRepository->findAll();
        //méthode render qui permet d'afficher mon fichier html.twig, et le résultat de ma requëte SQL
        return $this->render('book/book.html.twig', [
            'book' => $book,
            'title' => $title
        ]);
    }
        /**
         * @Route("/book/show/{id}" , name="bookid")
         */

    public function BookId(BookRepository $bookRepository, $id)
    {
        $title = 'Description du livre';
        $bookid = $bookRepository -> find($id);
        return $this->render('book/bookId.html.twig', [
            'bookid' => $bookid,
            'title' => $title

        ]);
    }

    /**
     * @Route("/books_by_style", name="books_by_style")
     */
    public function getBooksByStyle(BookRepository $bookRepository)
    {

        $books = $bookRepository->getByStyle();

        dump($books); die;
        // Appelle le bookRepository (en le passant en parametre de la méthode
        // Appelle la méthode qu'on a créée dans le bookRepository ("getByStyle")
        // Cette méthode est censée nous retourner tous les livres en fonction d'un genre
        // Elle va donc excecuter une requete SELECT en base de données
    }

    /**
     * @Route("/book/insert", name="book_insert")
     */
    public function insertBook(EntityManagerInterface $entityManager)
    {
        //insérer dans la table book un nouveau livre
        $title = 'Insertion du livre';



        $book = new Book();
        $book->setTitle('La biterie');
        $book->setStyle('escroquerie');
        $book->setInStock(true);
        $book->setNbpages(223);

        $entityManager->persist($book);
        $entityManager->flush();

        return $this->render('book/insertbook.html.twig',[
            'title' => $title,
            'book' => $book
        ]);



    }
    // pouvoir supprimer un book en bdd
    /**
     * @Route("/book/delete", name="book_delete")
     */
    public function deleteBook(BookRepository $bookRepository, EntityManagerInterface $entityManager)
    {
        $title = 'Suppression du livre';
        // Je récupère un enregistrement book en BDD grâce au repository de book
        $book = $bookRepository->find(2);
        // j'utilise l'entity manager avec la méthode remove pour enregistrer
        // la suppression du book dans l'unité de travail
        $entityManager->remove($book);
        // je valide la suppression en bdd avec la méthode flush
        $entityManager->flush();

        return $this->render('book/deletebook.html.twig', [
            'book' => $book,
            'title' => $title
        ]);
    }
    /**
     * @Route("/book/update{id}", name="book_update")
     */
    public function updateBook(BookRepository $bookRepository, EntityManagerInterface $entityManager, $id)
    {
        // j'utilise le Repository de l'entité Book pour récupérer un livre
        // en fonction de son id
        $book = $bookRepository->find($id);
        // Je donne un nouveau titre à mon entité Book
        $book->setTitle('Les 11 clés du succès');
        $book->setStyle('Magie');
        // je re-enregistre mon livre en BDD avec l'entité manager
        $entityManager->persist($book);
        $entityManager->flush();

        return $this->redirectToRoute('book');
    }
    /**
     * @Route("/book/insert_form", name="book_insert_form")
     */
    public function insertBookForm()
    {
        // J'utilise le gabarit de formulaire pour créer mon formulaire
        // j'envoie mon formulaire à un fichier twig
        // et je l'affiche
        // je crée un nouveau Book,
        // en créant une nouvelle instance de l'entité Book
        $title = 'Formulaire de livre';
        $book = new Book();
        // J'utilise la méthode createForm pour créer le gabarit / le constructeur de
        // formulaire pour le Book : BookType (que j'ai généré en ligne de commandes)
        // Et je lui associe mon entité Book vide
        $bookForm = $this->createForm(BookType::class, $book);
        // à partir de mon gabarit, je crée la vue de mon formulaire
        $bookFormView = $bookForm->createView();
        // je retourne un fichier twig, et je lui envoie ma variable qui contient
        // mon formulaire
        return $this->render('book/insert_book_form.html.twig', [
            'bookFormView' => $bookFormView,
            'title' => $title

        ]);
    }

}