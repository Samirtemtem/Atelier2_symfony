<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Author;
use App\Entity\Book;
use App\Form\BookType;
use App\Repository\AuthorRepository;
use App\Repository\BookRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
class BookController extends AbstractController
{
    #[Route('/book', name: 'app_book')]
    public function index(BookRepository $repo,AuthorRepository $authorRepository): Response
    {
        $books = $repo->findAll();
        foreach( $books as $book ) {
            $author = $authorRepository->findById($book->getAuthor()->getId());
          $book->setAuthor($author[0]);
        }
        $unpublished = 0;
        foreach($books as $book) {
            if (!($book->isPublished()))
             {
                $unpublished++;
             }

        }
        return $this->render('book/index.html.twig', [
            'controller_name' => 'BookController',
            'books' => $books,
            'unpublished' => $unpublished
        ]);
    }
    public function new(Request $request, AuthorRepository $repo, ManagerRegistry $manager): Response
    {
        $book = new Book();
        $form = $this->createForm(BookType::class,$book);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
           $author = $book->getAuthor();
           $book->setPublished(true);
           $author->setNbBooks($author->getNbBooks()+1);
        $book->setPublished(true);
        
        $manager->getManager()->persist($book);
        $manager->getManager()->flush();
        return $this->redirectToRoute('index');
        }
        
        return $this->render('book/add.html.twig', [
            'f' => $form->createView(),
        ]); 
}

public function edit(Request $request, BookRepository $repo, ManagerRegistry $manager, $id): Response
{
    $book = $repo->findOneBy(['id'=> $id]);
    $form = $this->createForm(BookType::class, $book,
        );
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
        $manager->getManager()->persist($book);
        $manager->getManager()->flush();
        return $this->redirectToRoute('book_index');
        }
        return $this->render('book/add.html.twig', [  
            'book'=> $book,
            'f'=> $form->createView(),
            ]);

        }
public function delete(Request $request,BookRepository $repo, ManagerRegistry $manager, $id): Response
{ 
    $entityManager = $manager->getManager();    $book = $repo->find($id);
    $entityManager->remove($book);
    $entityManager->flush();

    return $this->redirectToRoute('book_index');
 
}
public function show(Request $request, BookRepository $repo, ManagerRegistry $manager, $id): Response
{
    $book = $repo->findOneBy(['id'=> $id]);
    return $this->render('book/show.html.twig',[
        'book' => $book
    ]);
}
}