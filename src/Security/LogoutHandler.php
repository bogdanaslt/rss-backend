<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Logout\LogoutSuccessHandlerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

class LogoutHandler implements LogoutSuccessHandlerInterface
{

    public function onLogoutSuccess(Request $request): Response 
    {
        return new JsonResponse([]);
    }

}
