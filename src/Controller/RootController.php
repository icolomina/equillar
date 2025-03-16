<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\User;
use Symfony\Component\HttpKernel\Attribute\MapQueryParameter;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class RootController extends AbstractController
{
    #[Route('/r', name: 'get_root_page', methods: ['GET'])]
    public function getRootPage(): Response
    {
        /**
         * @var User|UserInterface
         */
        $user = $this->getUser();
        if(!$user) {
            return new RedirectResponse($this->generateUrl('app_login'));
        }

        return ($user->isSaver())
            ? new RedirectResponse($this->generateUrl('get_user_contracts_page'))
            : new RedirectResponse($this->generateUrl('get_contracts_page'))
        ;
    }

    #[Route('', name: 'get_landing_page', methods: ['GET'])]
    public function getLandingPage(): Response
    {
        return $this->render('landing.html.twig');
    }

    /*#[Route('/app', name: 'get_app', methods: ['GET'])]
    public function getApp(#[MapQueryParameter] ?string $qslug): Response 
    {
        return $this->render('App.html.twig', ['pathSlug' => $qslug]);
    }*/

    #[Route('/app', name: 'get_app', methods: ['GET'])]
    #[Route('/app/{seg1}', name: 'get_app_seg1', methods: ['GET'])]
    #[Route('/app/{seg1}/{seg2}', name: 'get_app_seg2', methods: ['GET'])]
    #[Route('/app/{seg1}/{seg2}/{seg3}', name: 'get_app_seg3', methods: ['GET'])]
    #[Route('/app/{seg1}/{seg2}/{seg3}/{seg4}', name: 'get_app_seg4', methods: ['GET'])]
    public function getApp(?string $seg1, ?string $seg2, ?string $seg3, ?string $seg4): Response 
    {
        return $this->render('App.html.twig');
    }
}