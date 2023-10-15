<?php

namespace App\Controller;

use App\Repository\SituationRepository;
use setasign\Fpdi\Fpdi;
use setasign\Fpdi\PdfReader\PageBoundaries;
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

    #[Route('/certificate', name: 'certificate_form', methods: ['GET'])]
    public function certificationForm(): Response
    {
        return $this->render('quizz/certificate_form.html.twig', []);
    }

    #[Route('/generate_pdf', name: 'generate_pdf')]
    public function generatePdf(Request $request): void
    {
        $student_name = $request->request->get('student_name', 'etudiant_lambda');
        $file = 'uploads/blank_certificate.pdf';
        $pdf = new Fpdi();
        $pageCount = $pdf->setSourceFile($file);
        $pageId = $pdf->importPage(1, PageBoundaries::MEDIA_BOX);
        $size = $pdf->getTemplateSize($pageId);

        $pdf->AddPage($size['orientation'], array($size['width'], $size['height']));

        $pdf->useTemplate($pageId);

        $pdf->SetFont('Arial', '', 35);
        $pdf->SetXY(100, 80);
        $text = utf8_decode($student_name);
        $pdf->Write(0, $text);
        $pdf->Output('I', 'generated.pdf');
    }
}
