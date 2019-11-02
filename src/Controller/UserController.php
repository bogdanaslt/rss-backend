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
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Exception\InvalidModelException;

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

        $deserialized = $serializer->deserialize($data, User::class, 'json');

        try {
            $user = $userService->validate($deserialized);
            $registered = $userService->register($user);            
        } catch (InvalidModelException $e) {
            return new JsonResponse($e->getErrors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse([
            'message' => sprintf(
                "User %s successfully created. You may login.",
                $registered->getEmail()
            )
        ]);
    }
    
    /**
     * @Route("/login", name="login", methods={"POST"})
     */
    public function login(AuthenticationUtils $authenticationUtils): JsonResponse
    {
        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return new JsonResponse([
            'last_username' => $lastUsername,
            'error' => $error->getMessage()
        ]);
    }
    
    /**
     * @Route("/logout", name="logout", methods={"GET"})
     */
    public function logout(){}

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

        $deserialized = $serializer->deserialize($data, User::class, 'json');

        try {
            $userService->validate($deserialized, 'email');     
        } catch (InvalidModelException $e) {
            return new JsonResponse($e->getErrors(), Response::HTTP_UNPROCESSABLE_ENTITY);
        } catch (\Exception $e) {
            return new JsonResponse(['email' => $e->getMessage()], Response::HTTP_BAD_REQUEST);
        }

        return new JsonResponse([
            'email' => 'Email available for registration'
        ]);
    }
    
    /**
     * @Route("/user", methods={"GET"})
     * 
     * @return JsonResponse
     */
    public function user(SerializerInterface $serializer)
    {
        return new JsonResponse($serializer->serialize($this->getUser(), 'json', ['groups' => ['public']]), Response::HTTP_OK, [], true);
    }

}
