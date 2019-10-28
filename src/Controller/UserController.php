<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\User;
use App\Service\UserService;
use Symfony\Component\HttpFoundation\Response;

class UserController extends AbstractController
{

    /**
     * @Route("/register", methods={"POST"})
     * 
     * @return JsonResponse
     */
    public function register(
        Request $request,
        SerializerInterface $serializer,
        UserService $userService
    ) {
        $data = $request->getContent();

        $user = $serializer->deserialize($data, User::class, 'json');

        $errors = $userService->validate($user);
        if (count($errors) > 0) {
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        $registered = $userService->register($user);

        return new JsonResponse([
            'message' => sprintf(
                "User %s successfully created. You may login.",
                $registered->getEmail()
            )
        ]);
    }

    /**
     * @Route("/unique-email", methods={"POST"})
     * 
     * @return JsonResponse
     */
    public function uniqueEmail(
        Request $request,
        SerializerInterface $serializer,
        UserService $userService
    ) {
        $data = $request->getContent();

        $user = $serializer->deserialize($data, User::class, 'json');

        $errors = $userService->validate($user, 'email');
        if (count($errors) > 0) {
            return new JsonResponse($errors, Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse([
            'message' => 'Email available for registration'
        ]);
    }

}
