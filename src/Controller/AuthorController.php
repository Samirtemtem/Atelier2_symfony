<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\{TextType,SubmitType};

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
  /*  public $authors = [
        [
            'id' => 1,
            'picture' => '/images/Victor-Hugo.jpg',
            'username' => 'Victor Hugo',
            'email' => 'victor.hugo@gmail.com',
            'nb_books' => 100,
        ],
        [
            'id' => 2,
            'picture' => '/images/william-shakespeare.jpg',
            'username' => 'William Shakespeare',
            'email' => 'william.shakespeare@gmail.com',
            'nb_books' => 200,
        ],
        [
            'id' => 3,
            'picture' => '/images/Taha_Hussein.jpg',
            'username' => 'Taha Hussein',
            'email' => 'taha.hussein@gmail.com',
            'nb_books' => 300,
        ],
    ];
  */
    public function list(AuthorRepository $repo){
     /*   $sumOfBooks = 0;
    foreach ($this->authors as $author) {
        $sumOfBooks += $author['nb_books'];
    } */
    $authors = $repo->findAll();
    $sumOfBooks = 0;
    return $this->render('author/list.html.twig', [
        'authors' => $authors,
        'sumOfBooks' => $sumOfBooks,

    ]);
}
/*
public function authorDetails(int $id)
{
    $id--;
    $author = $this->authors[$id];

    if (!$author) {
        throw new NotFoundHttpException('Auteur non trouvé');
    }
    
    return $this->render('author/showAuthor.html.twig', [
        'author' => $author,
        
    ]);
}*/
#[Route('/addauthor', name: 'add_dummy_author')]
public function Addauthor(ManagerRegistry $manager){
    $author = new Author();
    $author->setUsername('Dummy Author');
    $author->setEmail('DummyAuthor@localhost.com');
    $manager->getManager()->persist($author);
    $manager->getManager()->flush();
    return $this->redirectToRoute('index');
}
public function new(Request $request,ManagerRegistry $manager): Response
{
   // $author->setUsername();
   // $author->setEmail();
    
   $author= new Author();
        $form = $this->createForm(AuthorType::class,$author);
        $form->handleRequest($request);
        if($form->isSubmitted())
        {
            $manager->getManager()->persist($author);
            $manager->getManager()->flush();
            return $this->redirectToRoute('index');
        }
        return $this->renderForm('author/add.html.twig',['f'=>$form]);
}


public function edit($id,ManagerRegistry $manager,AuthorRepository $repo,Request $req){
    $author = $repo->find($id);
    $form = $this->createForm(AuthorType::class,$author);
    $form->handleRequest($req);
    if($form->isSubmitted())
    {
        $manager->getManager()->persist($author);
        $manager->getManager()->flush();
        return $this->redirectToRoute('index');
    }
    return $this->renderForm('author/add.html.twig',['f'=>$form]);
}
public function delete(AuthorRepository $repo, ManagerRegistry $manager, $id): Response
{
    $entityManager = $manager->getManager();
    $author = $repo->find($id);
    $entityManager->remove($author);
    $entityManager->flush();

    return $this->redirectToRoute('index');
}
}
