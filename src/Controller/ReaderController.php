<?php

namespace App\Controller;

use App\Entity\Reader;
use App\Form\ReaderType;
use App\Repository\ReaderRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class ReaderController extends AbstractController
{
    #[Route('/reader', name: 'app_reader')]
    public function index(): Response
    {
        return $this->render('reader/index.html.twig', [
            'controller_name' => 'ReaderController',
        ]);
    }

    #[Route('/showreader/{username}', name: 'app_show_reader')]
    public function showReader($username): Response
    {
        return $this->render('reader/show.html.twig', [
            'username' => $username,
        ]);
    }

    #[Route('/listreaders', name: 'app_list_readers')]
    public function listReaders(): Response
    {
        $readers = array(
            array('id' => 1, 'username' => 'john_doe'),
            array('id' => 2, 'username' => 'jane_smith'),
            array('id' => 3, 'username' => 'mike_wilson'),
        );

        return $this->render('reader/listreaders.html.twig', ['readers' => $readers]);
    }

    #[Route('/showAllReaders', name: 'showAllReaders')]
    public function showAll(ReaderRepository $repo): Response
    {
        $readers = $repo->findAll();

        return $this->render('reader/showAll.html.twig', ['list' => $readers]);
    }

    #[Route('/addReader', name: 'addReader')]
    public function add(ManagerRegistry $doctrine)
    {
        $reader = new Reader();
        $reader->setUsername('New Reader');
        
        $em = $doctrine->getManager();
        $em->persist($reader);
        $em->flush();
        
        return $this->redirectToRoute('showAllReaders');
    }

    #[Route('/addReaderForm', name: 'addReaderForm')]
    public function addForm(Request $request, ManagerRegistry $doctrine)
    {
        $reader = new Reader();
        $form = $this->createForm(ReaderType::class, $reader);
        $form->add('Add', SubmitType::class, [
            'attr' => ['class' => 'btn btn-primary mt-3']
        ]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $doctrine->getManager();
            $em->persist($reader);
            $em->flush();
            
            $this->addFlash('success', 'Reader added successfully!');
            return $this->redirectToRoute('showAllReaders');
        }
        
        return $this->render('reader/add.html.twig', ['formulaire' => $form->createView()]);
    }

    #[Route('/deleteReader/{id}', name: 'deleteReader')]
    public function deleteReader($id, ReaderRepository $repo, ManagerRegistry $doctrine)
    {
        $reader = $repo->find($id);
        
        if ($reader) {
            $em = $doctrine->getManager();
            $em->remove($reader);
            $em->flush();
            $this->addFlash('success', 'Reader deleted successfully!');
        } else {
            $this->addFlash('error', 'Reader not found!');
        }
        
        return $this->redirectToRoute('showAllReaders');
    }

    #[Route('/showReaderDetails/{id}', name: 'showReaderDetails')]
    public function showDetails($id, ReaderRepository $repo)
    {
        $reader = $repo->find($id);
        
        if (!$reader) {
            throw $this->createNotFoundException('Reader not found');
        }
        
        return $this->render('reader/showDetails.html.twig', ['reader' => $reader]);
    }

    #[Route('/updateReader/{id}', name: 'updateReader')]
    public function updateReader($id, ReaderRepository $repo, Request $request, ManagerRegistry $doctrine): Response
    {
        $reader = $repo->find($id);
        
        if (!$reader) {
            throw $this->createNotFoundException('Reader not found');
        }
    
        if ($request->isMethod('POST')) {
            $username = $request->request->get('username');
    
            $reader->setUsername($username);
    
            $em = $doctrine->getManager();
            $em->flush();
    
            $this->addFlash('success', 'Reader updated successfully!');
            return $this->redirectToRoute('showAllReaders');
        }
    
        return $this->render('reader/update.html.twig', [
            'reader' => $reader
        ]);
    }
}