<?php

namespace App\Controller;

use App\Repository\SituationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/quizz')]
class QuizzController extends AbstractController
{


    #[Route('/', name: 'quizz_index', defaults: ['page' => '1', '_format' => 'html'], methods: ['GET'])]
    public function index(Request $request, SituationRepository $situationRepository): Response
    {
        $situations = $situationRepository->findAll();
        return $this->render('quizz/index.html.twig', [
            'situations' => $situations
        ]);
    }
}
