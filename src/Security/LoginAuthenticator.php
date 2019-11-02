<?php

namespace App\Security;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;
use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;
use Symfony\Component\Security\Http\Util\TargetPathTrait;
use Symfony\Component\Serializer\SerializerInterface;
use App\Entity\Credentials;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\HttpFoundation\Response;

class LoginAuthenticator extends AbstractGuardAuthenticator {

    use TargetPathTrait;

    private $entityManager;
    private $router;
    private $passwordEncoder;
    private $serializer;
    
    private $userRepository;

    public function __construct(
        EntityManagerInterface $entityManager,
        RouterInterface $router, 
        UserPasswordEncoderInterface $passwordEncoder, 
        SerializerInterface $serializer,
        UserRepository $userRepository
    ) {
        $this->entityManager = $entityManager;
        $this->router = $router;
        $this->passwordEncoder = $passwordEncoder;
        $this->serializer = $serializer;
        $this->userRepository = $userRepository;
    }

    public function supports(Request $request) 
    {
        return 'login' === $request->attributes->get('_route') && $request->isMethod('POST');
    }

    public function getCredentials(Request $request) 
    {
        $credentials = $this->serializer->deserialize($request->getContent(), Credentials::class, 'json');

        $request->getSession()->set(
                Security::LAST_USERNAME,
                $credentials->getEmail()
        );

        return $credentials;
    }

    public function getUser($credentials, UserProviderInterface $userProvider) 
    {
        $user = $this->userRepository->findOneBy(['email' => $credentials->getEmail()]);
        if (!$user) {
            // fail authentication with a custom error
            throw new CustomUserMessageAuthenticationException('Bad credentials or user does not exists');
        }

        return $user;
    }

    public function checkCredentials($credentials, UserInterface $user) 
    {
        return $this->passwordEncoder->isPasswordValid($user, $credentials->getPassword());
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey) 
    {
        return new JsonResponse([
            'message' => 'Login successful'
        ]);
    }

    protected function getLoginUrl() {}

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception) {
        if ($request->hasSession()) {
            $request->getSession()->set(Security::AUTHENTICATION_ERROR, $exception);
        }

        return new JsonResponse(['message' => $exception->getMessage()], Response::HTTP_BAD_REQUEST);
    }

    public function start(Request $request, \Symfony\Component\Security\Core\Exception\AuthenticationException $authException = null): Response 
    {
        return new JsonResponse([], Response::HTTP_UNAUTHORIZED);
    }

    public function supportsRememberMe(): bool 
    {
        return false;
    }

}
