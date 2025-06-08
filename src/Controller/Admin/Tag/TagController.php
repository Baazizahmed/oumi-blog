<?php

namespace App\Controller\Admin\Tag;

use App\Entity\Tag;
use App\Form\AdminTagForm;
use App\Repository\TagRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class TagController extends AbstractController
{
    public function __construct(
        private EntityManagerInterface $entityManager,
        private TagRepository $tagRepository,
    ) {
    }

    #[Route('/tag/index', name: 'app_admin_tag_index', methods: ['GET'])]
    public function index(): Response
    {
        $tags = $this->tagRepository->findall();

        return $this->render('pages/admin/tag/index.html.twig', [
            'tags' => $tags,
        ]);
    }

    #[Route('/tag/create', name: 'app_admin_tag_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $tag = new Tag();

        $form = $this->createForm(AdminTagForm::class, $tag);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tag->setCreatedAt(new \DateTimeImmutable());
            $tag->setUpdatedAt(new \DateTimeImmutable());

            $this->entityManager->persist($tag);
            $this->entityManager->flush();

            $this->addFlash('success', 'Le tag a été ajouté avec succés.');

            return $this->redirectToRoute('app_admin_tag_index');
        }

        return $this->render('pages/admin/tag/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/tag/edit/{id<\d+>}', name: 'app_admin_tag_edit', methods: ['GET', 'POST'])]
    public function edit(Tag $tag, Request $request): Response
    {
        $form = $this->createForm(AdminTagForm::class, $tag);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $tag->setUpdatedAt(new \DateTimeImmutable());

            $this->entityManager->persist($tag);
            $this->entityManager->flush();

            $this->addFlash('success', 'Le tag a été modifié avec succés.');

            return $this->redirectToRoute('app_admin_tag_index');
        }

        return $this->render('pages/admin/tag/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/tag/delete/{id<\d+>}', name: 'app_admin_tag_delete', methods: ['POST'])]
    public function delete(Tag $tag, Request $request): Response
    {
        if ($this->isCsrfTokenValid("delete-tag-{$tag->getId()}", $request->request->get('csrf_token'))) {
            $tagName = $tag->getName();
            $this->entityManager->remove($tag);
            $this->entityManager->flush();

            $this->addFlash('success', "Le tag {$tagName} a été supprimer.");
        }

        return $this->redirectToRoute('app_admin_tag_index');
    }
}
