<?php

namespace App\Controller\Admin\Setting;

use App\Entity\Setting;
use App\Entity\User;
use App\Form\AdminSettingForm;
use App\Repository\SettingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/admin')]
final class SettingController extends AbstractController
{
    #[Route('/setting/index', name: 'app_admin_setting_index', methods: ['GET'])]
    public function index(SettingRepository $settingRepository): Response
    {
        $settings = $settingRepository->findAll();
        $setting = $settings[0];

        return $this->render('pages/admin/setting/index.html.twig', [
            'setting' => $setting,
        ]);
    }

    #[Route('/setting/edit/{id<\d+>}', name: 'app_admin_setting_edit', methods: ['GET', 'POST'])]
    public function edit(Setting $setting, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdminSettingForm::class, $setting);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var User
             */
            $user = $this->getUser();

            $setting->setUser($user);
            $setting->setCreatedAt(new \DateTimeImmutable());
            $setting->setUpdatedAt(new \DateTimeImmutable());

            $entityManager->persist($setting);
            $entityManager->flush();

            $this->addFlash('success', 'Les paramètres du site ont été mis à jour.');

            return $this->redirectToRoute('app_admin_setting_index');
        }

        return $this->render('pages/admin/setting/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
