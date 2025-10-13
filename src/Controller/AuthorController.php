<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public function index(): Response
    {
        return $this->render('author/index.html.twig', [
            'controller_name' => 'AuthorController',
        ]);
    }

    #[Route('/showauthor/{name}', name: 'app_show_author')]
    public function showAuthor($name):  Response
    {
        return $this->render('author/show.html.twig', [
            'nom' => $name,
        ]); 
    }

    #[Route('/listauthors', name: 'app_list_authors')]
    public function listAuthors():  Response
    {
        $authors = array(
            array('id' => 1 , 'picture' => 'assets/images/1.jpg' , 'username' => 'Victor Hugo' , 'email' => 'victor.hugo@gmail.com' , 'nb_books' => 100),
            array('id' => 2 , 'picture' => 'assets/images/2.jpg' , 'username' => 'William Shakespear' , 'email' => 'william.shakespear@gmail.com' , 'nb_books' => 200),
            array('id' => 3 , 'picture' => 'assets/images/3.jpg' , 'username' => 'Taha Hussein' , 'email' => 'taha.hussein@gmail.com' , 'nb_books' => 300),
        );


        return $this->render('author/listauthors.html.twig',['authors' => $authors]
        ); 
    }

    #[Route('/showAll', name: 'showAll')]
    public function showAll(AuthorRepository $repo):  Response
    {
        $authors = $repo->findAll();

        return $this->render('author/showAll.html.twig',['list' => $authors]
        ); 
    }

    #[Route('/add', name:'add')]
    public function add(ManagerRegistry $doctrine){
      $author=new Author();
      $author->setEmail('foulen@esprit.tn');
      $author->setUsername('foulen');
      $em=$doctrine->getManager();
      $em->persist($author);
      $em->flush();
      //return new Response("Author added suceesfully");
      return $this->redirectToRoute('showAll');
    }

    #[Route('/addForm',name:'addForm')]
    public function addForm(Request $request, ManagerRegistry $doctrine){
    $author=new Author();
    $form=$this->createForm(AuthorType::class,$author);
    $form->add('Add',SubmitType::class);

    $form->handleRequest($request);
    if($form->isSubmitted()){
     $em=$doctrine->getManager();
     $em->persist($author);
     $em->flush();
     return $this->redirectToRoute('showAll');
    }
    return $this->render('author/add.html.twig',['formulaire'=>$form->createView()]);
    // return $this->renderForm()
    }

    #[Route('/deleteAuthor/{id}',name:'deleteAuthor')]
    public function deleteAuthor($id,AuthorRepository $repo, ManagerRegistry $doctrine){
     // chercher un auteur selon son id
     //find , findAll , findOneby 
     $author=$repo->find($id);
     //procéder à la suppression 
      $em=$doctrine->getManager();
      $em->remove($author);
      $em->flush();// l'ajout , la suppression et la modification
      return $this->redirectToRoute('showAll');
    }

    #[Route('/showDetails/{id}',name:'showDetails')]
    public function showDetails($id,AuthorRepository $repo){
       $author=$repo->find($id);
       return $this->render('author/showDetails.html.twig',['author'=>$author]);
    }

    #[Route('updateAuthor/{id}', name: 'updateAuthor')]
public function updateAuthor($id, AuthorRepository $repo, Request $request): Response
{
    $author = $repo->find($id);
    
    if (!$author) {
        throw $this->createNotFoundException('Author not found');
    }

    if ($request->isMethod('POST')) {
        $username = $request->request->get('username');
        $email = $request->request->get('email');
        $nb_books = $request->request->get('nb_books');

        $author->setUsername($username);
        $author->setEmail($email);
        $author->setNbBooks($nb_books);

        $repo->save($author, true);

        $this->addFlash('success', 'Author updated successfully!');
        return $this->redirectToRoute('showAll');
    }

    return $this->render('author/update.html.twig', [
        'author' => $author
    ]);
}

}
