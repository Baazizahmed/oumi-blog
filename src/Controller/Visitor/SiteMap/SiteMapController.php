<?php

namespace App\Controller\Visitor\SiteMap;

use App\Repository\PostRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class SiteMapController extends AbstractController
{
    #[Route('/sitemap.xml', name: 'app_visitor_sitemap_show', methods: ['GET'])]
    public function show(Request $request, PostRepository $postRepository): Response
    {
        $hostname = $request->getSchemeAndHttpHost();

        $urls = [];

        $urls[] = [
            'loc' => $this->generateUrl('app_visitor_welcome'),
        ];

        $postsPlublished = $postRepository->findBy(['isPublished' => true], ['publishedAt' => 'DESC']);

        foreach ($postsPlublished as $postsPlublished) {
            $urls[] = [
                'loc' => $this->generateUrl('app_visitor_blog_post_show', ['id' => $postsPlublished->getId(), 'slug' => $postsPlublished->getSlug()]),
                'lastmod' => $postsPlublished->getUpdatedAt()->format('y-m-d'),
                'changefreq' => 'weekly',
                'priority' => 0.9,
            ];
        }

        $response = $this->render('sitemap/show.html.twig', [
            'hostname' => $hostname,
            'urls' => $urls,
        ]);

        $response->headers->set('Content-Type', 'text/xml');

        return $response;
    }
}
