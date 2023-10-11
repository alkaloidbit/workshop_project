<?php

namespace App\Controller;

use App\Entity\Proposition;
use App\Entity\User;
use App\Form\PropositionType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\SubmitButton;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

#[Route('/admin/proposition')]
class AdminPropositionController extends AbstractController
{
    #[Route('/', name: 'admin_proposition_index')]
    public function index(): Response
    {
        return $this->render('admin_proposition/index.html.twig', [
            'controller_name' => 'AdminPropositionController',
        ]);
    }

    #[Route('/new', name: 'admin_proposition_new', methods: ['GET', 'POST'])]
		public function new(
			#[CurrentUser] User $user,
				Request $request,
				EntityManagerInterface $entityManager,
		):Response {
			$proposition = new Proposition();
        $form = $this->createForm(PropositionType::class, $proposition)
            ->add('saveAndCreateNew', SubmitType::class)
        ;

		$form->handleRequest($request);

		if ($form->isSubmitted() && $form->isValid()) {
				$entityManager->persist($proposition);
				$entityManager->flush();

				// Flash messages are used to notify the user about the result of the
				// actions. They are deleted automatically from the session as soon
				// as they are accessed.
				// See https://symfony.com/doc/current/controller.html#flash-messages
				$this->addFlash('success', 'Proposition enregistrée.');

				/** @var SubmitButton $submit */
				$submit = $form->get('saveAndCreateNew');

				if ($submit->isClicked()) {
						return $this->redirectToRoute('admin_proposition_index');
				}

				return $this->redirectToRoute('admin_proposition_index');
		}
		return $this->render('admin/proposition/new.html.twig', [
				'proposition' => $situation,
				'form' => $form,
		]);

	}
}
