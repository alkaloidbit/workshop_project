<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Answer;
use App\Form\AnswerType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/admin/answer')]
class AdminAnswerController extends AbstractController
{
    #[Route('/', name: 'admin_answer_index')]
    public function index(): Response
    {
        return $this->render('admin_answer/index.html.twig', [
            'controller_name' => 'AdminAnswerController',
        ]);
    }


    #[Route('/new', name: 'admin_answer_new', methods: ['GET', 'POST'])]
    public function new(
        #[CurrentUser] User $user,
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
        $answer = new Answer();
        $form = $this->createForm(AnswerType::class, $answer)
            ->add('saveAndCreateNew', SubmitType::class);

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // See https://symfony.com/doc/current/forms.html#processing-forms
        // However, we explicitly add it to improve code readability.
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($answer);
            $entityManager->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See https://symfony.com/doc/current/controller.html#flash-messages
            $this->addFlash('success', 'Answer enregistrée');

            /** @var SubmitButton $submit */
            $submit = $form->get('saveAndCreateNew');

            if ($submit->isClicked()) {
                return $this->redirectToRoute('admin_answer_index');
            }

            return $this->redirectToRoute('admin_situation_index');
        }

        return $this->render('admin/answer/new.html.twig', [
            'answer' => $answer,
            'form' => $form,
        ]);
    }
}
