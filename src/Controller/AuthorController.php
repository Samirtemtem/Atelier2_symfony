<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController
{
    #[Route('/author', name: 'app_author')]
    public $authors = [
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
    public function list(){
        $sumOfBooks = 0;
    foreach ($this->authors as $author) {
        $sumOfBooks += $author['nb_books'];
    }
    return $this->render('author/list.html.twig', [
        'authors' => $this->authors,
        'sumOfBooks' => $sumOfBooks,

    ]);
}

public function authorDetails(int $id)
{
    $author = $this->authors[$id];

    if (!$author) {
        throw new NotFoundHttpException('Auteur non trouvé');
    }
    
    return $this->render('author/showAuthor.html.twig', [
        'author' => $author,
        
    ]);
}
}
