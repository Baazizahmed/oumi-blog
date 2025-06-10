<?php

namespace App\Controller\Admin\User;

use App\Entity\User;
use App\Form\EditRolesForm;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class UserController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private EntityManagerInterface $entityManager,
    ) {
    }

    #[Route('/user/index', name: 'app_admin_user_index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('pages/admin/user/index.html.twig', [
            'users' => $this->userRepository->findAll(),
        ]);
    }

    #[Route('/user/edit_roles/{id<\d+>}', name: 'app_admin_user_edit_roles', methods: ['GET', 'POST'])]
    public function editRoles(User $user, Request $request): Response
    {
        $form = $this->createForm(EditRolesForm::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setUpdatedAt(new \DateTimeImmutable());

            $this->entityManager->persist($user);
            $this->entityManager->flush();

            $this->addFlash('success', "Les roles de {$user->getFirstName()} {$user->getLastName()} ont été modifié.}");

            return $this->redirectToRoute('app_admin_user_index');
        }

        return $this->render('pages/admin/user/edit_roles.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/user/delete/{id<\d+>}', name: 'app_admin_user_delete', methods: ['POST'])]
    public function delete(User $user, Request $request): Response
    {
        if ($this->isCsrfTokenValid("delete-user-{$user->getId()}", $request->request->get('csrf_token'))) {
            $userFullName = "{$user->getFirstName()} {$user->getLastName()}";

            $userPosts = $user->getPosts();

            if (count($userPosts) > 0) {
                foreach ($userPosts as $post) {
                    $post->setUser(null);
                }
            }

            if ($this->getUser() == $user) {
                $this->container->get('security.token_storage')->setToken(null);
            }

            $this->entityManager->remove($user);
            $this->entityManager->flush();

            $this->addFlash('success', "{$userFullName} a été supprimé.");
        }

        return $this->redirectToRoute('app_admin_user_index');
    }
}
