<?php

namespace App\Controller\Admin\Contact;

use App\Entity\Contact;
use App\Repository\ContactRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class ContactController extends AbstractController
{
    public function __construct(
        private ContactRepository $contactRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/contact/index', name: 'app_admin_contact_index', methods: ['GET'])]
    public function index(): Response
    {
        $contacts = $this->contactRepository->findAll();

        return $this->render('pages/admin/contact/index.html.twig', [
            'contacts' => $contacts,
        ]);
    }

    #[Route('/contact/delete/{id<\d+>}', name: 'app_admin_contact_delete', methods: ['POST'])]
    public function delete(Contact $contact, Request $request): Response
    {
        if ($this->isCsrfTokenValid("delete-contact-{$contact->getId()}", $request->request->get('csrf_token'))) {
            $contactFullName = "{$contact->getFirstName()} {$contact->getLastName()}";
            $this->entityManager->remove($contact);
            $this->entityManager->flush();

            $this->addFlash('success', "Le contact {$contactFullName} a été supprimer.");
        }

        return $this->redirectToRoute('app_admin_contact_index');
    }
}
