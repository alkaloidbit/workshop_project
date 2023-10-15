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
    public function index(): Response
    {
        return $this->render('quizz/index.html.twig', []);
    }

    #[Route('/getJsonSituation', name: 'situation_json', methods: ['GET'])]
    public function getSituation(SituationRepository $situationRepository, Request $request): Response
    {
        $id_situation = $request->query->get('id_situation', 1);
        $situation = $situationRepository->find($id_situation);
        foreach ($situation->getAnswers() as $key => &$answer) {
            if ($answer->isValid()) {
                $situation->setCorrectAnswer($key);
            }
        }
        return $this->json([
            'situation' => $situation
        ], 200, [], ['groups' => ['situation:read']]);
    }
}
