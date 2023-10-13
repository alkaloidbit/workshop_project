<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/quizz')]
class QuizzController extends AbstractController
{


    #[Route('/', name: 'quizz_index', defaults: ['page' => '1', '_format' => 'html'], methods: ['GET'])]
    public function index(Request $request)
    {
        return $this->render('quizz/index.html.twig', []);
    }
}

