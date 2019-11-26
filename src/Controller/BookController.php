<?php
namespace App\Controller;

use App\Repository\BookRepository;
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
        return $this->render('book.html.twig', [
            'book' => $book,
            'title' => $title
        ]);
    }
        /**
         * @Route("/book/{id}" , name="bookid")
         */

    public function BookId(BookRepository $bookRepository, $id)
    {
        $title = 'Description du livre';
        $bookid = $bookRepository -> find($id);
        return $this->render('bookId.html.twig', [
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



}