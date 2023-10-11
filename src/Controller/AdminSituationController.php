<?php

namespace App\Controller;

use App\Entity\Situation;
use App\Entity\User;
use App\Form\SituationType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\SituationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/admin/situation')]
final class AdminSituationController extends AbstractController
{
    #[Route('/', name: 'admin_situation_index')]
    public function index(SituationRepository $situationRepository): Response
    {
				$situationsList = $situationRepository->findAll();
					return $this->render('admin/situation/index.html.twig', [
							'situations' => $situationsList,
        ]);
    }

    #[Route('/new', name: 'admin_situation_new', methods: ['GET', 'POST'])]
    public function new(
        #[CurrentUser] User $user,
        Request $request,
        EntityManagerInterface $entityManager,
    ): Response {
        $situation = new Situation();
        // See https://symfony.com/doc/current/form/multiple_buttons.html
        $form = $this->createForm(SituationType::class, $situation)
            ->add('saveAndCreateNew', SubmitType::class)
        ;

        $form->handleRequest($request);

        // the isSubmitted() method is completely optional because the other
        // isValid() method already checks whether the form is submitted.
        // However, we explicitly add it to improve code readability.
        // See https://symfony.com/doc/current/forms.html#processing-forms
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($situation);
            $entityManager->flush();

            // Flash messages are used to notify the user about the result of the
            // actions. They are deleted automatically from the session as soon
            // as they are accessed.
            // See https://symfony.com/doc/current/controller.html#flash-messages
            $this->addFlash('success', 'Situation enregistrée');

            /** @var SubmitButton $submit */
            $submit = $form->get('saveAndCreateNew');

            if ($submit->isClicked()) {
                return $this->redirectToRoute('admin_situation_index');
            }

            return $this->redirectToRoute('admin_situation_index');
        }

        return $this->render('admin/situation/new.html.twig', [
            'situation' => $situation,
            'form' => $form,
        ]);
    }
}
