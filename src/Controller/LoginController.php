<?php

namespace App\Controller;

use App\Application\Security\Token\TokenEncoder;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;

class LoginController extends AbstractController
{
    #[Route('/do-login', name: 'post_api_login', methods: ['POST'])]
    public function postLogin(#[CurrentUser] ?User $user, TokenEncoder $tokenEncoder): Response
    {
        if (null === $user) {
            return $this->json([
                'message' => 'missing credentials',
            ], Response::HTTP_UNAUTHORIZED);
        }

        return $this->json([
            'user' => $user->getUserIdentifier(),
            'token' => $tokenEncoder->encode($user),
            'role' => $user->getRoles()[0],
            'name' => $user->getName(),
            'role_type' => $user->getUserRoleType(),
        ]);
    }

    #[Route(path: '/api/v1/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}
