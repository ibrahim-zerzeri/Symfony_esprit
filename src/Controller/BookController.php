<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(): Response
    {
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
        ]);
    }

    #[Route('/showbook/{title}', name: 'app_show_book')]
    public function showBook($title): Response
    {
        return $this->render('book/show.html.twig', [
            'title' => $title,
        ]);
    }

    #[Route('/listbooks', name: 'app_list_books')]
    public function listBooks(): Response
    {
        $books = array(
            array('id' => 1, 'title' => 'Les Misérables', 'publication_date' => '1862-01-01', 'enabled' => true, 'author' => 'Victor Hugo'),
            array('id' => 2, 'title' => 'Hamlet', 'publication_date' => '1603-01-01', 'enabled' => true, 'author' => 'William Shakespeare'),
            array('id' => 3, 'title' => 'The Days', 'publication_date' => '1929-01-01', 'enabled' => true, 'author' => 'Taha Hussein'),
        );

        return $this->render('book/listbooks.html.twig', ['books' => $books]);
    }
    #[Route('/showAllBooks', name: 'showAllBooks')]
    public function showAll(BookRepository $repo): Response
    {
        try {
            $books = $repo->findAll();
            return $this->render('book/showAll.html.twig', ['list' => $books]);
        } catch (\Error $e) {
            // This will show you exactly where the error occurs
            dd($e->getMessage(), $e->getFile(), $e->getLine());
        }
    }

    #[Route('/addBook', name: 'addBook')]
    public function add(ManagerRegistry $doctrine)
    {
        $book = new Book();
        $book->setTitle('New Book Title');
        $book->setPublicationDate(new \DateTime());
        $book->setEnabled(true);
        $book->setCategory('Fiction'); // Add default category
        
        $em = $doctrine->getManager();
        $em->persist($book);
        $em->flush();
        
        return $this->redirectToRoute('showAllBooks');
    }

    #[Route('/addBookForm', name: 'addBookForm')]
    public function addForm(Request $request, ManagerRegistry $doctrine)
    {
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->add('Add', SubmitType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($book);
            $em->flush();
            return $this->redirectToRoute('showAllBooks');
        }
        
        return $this->render('book/add.html.twig', ['formulaire' => $form->createView()]);
    }

    #[Route('/deleteBook/{id}', name: 'deleteBook')]
    public function deleteBook($id, BookRepository $repo, ManagerRegistry $doctrine)
    {
        $book = $repo->find($id);
        
        if ($book) {
            $em = $doctrine->getManager();
            $em->remove($book);
            $em->flush();
            $this->addFlash('success', 'Book deleted successfully!');
        } else {
            $this->addFlash('error', 'Book not found!');
        }
        
        return $this->redirectToRoute('showAllBooks');
    }

    #[Route('/showBookDetails/{id}', name: 'showBookDetails')]
    public function showDetails($id, BookRepository $repo)
    {
        $book = $repo->find($id);
        
        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }
        
        return $this->render('book/showDetails.html.twig', ['book' => $book]);
    }

    #[Route('/updateBook/{id}', name: 'updateBook')]
    public function updateBook($id, BookRepository $repo, Request $request, ManagerRegistry $doctrine): Response
    {
        $book = $repo->find($id);
        
        if (!$book) {
            throw $this->createNotFoundException('Book not found');
        }
    
        if ($request->isMethod('POST')) {
            $title = $request->request->get('title');
            $publicationDate = $request->request->get('publication_date');
            $enabled = $request->request->get('enabled');
            $category = $request->request->get('category');
    
            $book->setTitle($title);
            $book->setPublicationDate(new \DateTime($publicationDate));
            $book->setEnabled($enabled === '1');
            $book->setCategory($category);
    
            $em = $doctrine->getManager();
            $em->flush();
    
            $this->addFlash('success', 'Book updated successfully!');
            return $this->redirectToRoute('showAllBooks');
        }
    
        return $this->render('book/update.html.twig', [
            'book' => $book
        ]);
    }
    #[Route('/bookStats', name: 'book_stats')]
public function bookStats(BookRepository $bookRepo): Response
{
    // 1. Nombre des livres de catégorie "Romance"
    $romanceBookCount = $bookRepo->CountBooksRomance();
    
    // 2. Livres publiés entre 2014-01-01 et 2018-12-31
    $booksBetweenDates = $bookRepo->findBooksBetweenDates();

    return $this->render('book/stats.html.twig', [
        'romanceBookCount' => $romanceBookCount,
        'booksBetweenDates' => $booksBetweenDates,
    
    ]);
}
}