<?php

namespace App\Service;

use App\Entity\User;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Doctrine\ORM\EntityManagerInterface;
use App\Exception\InvalidModelException;

class UserService
{

    /**
     *
     * @var PasswordEncoderInterface
     */
    private $passwordEncoder;

    /**
     * 
     * @var ValidatorInterface
     */
    private $validator;

    /**
     *
     * @var EntityManagerInterface
     */
    private $entityManager;

    /**
     * 
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @param ValidatorInterface $validator
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(
            UserPasswordEncoderInterface $passwordEncoder,
            ValidatorInterface $validator,
            EntityManagerInterface $entityManager
    )
    {
        $this->passwordEncoder = $passwordEncoder;
        $this->validator = $validator;
        $this->entityManager = $entityManager;
    }

    /**
     * 
     * @param User $user
     * @return User
     */
    public function register(User $user)
    {
        $password = $this->passwordEncoder->encodePassword($user, $user->getPassword());
        $user->setPassword($password);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        return $user;
    }

    /**
     * 
     * @param User $user
     * @param type $groups
     * @return type
     */
    public function validate(User $user, $groups = null)
    {
        $violations = $this->validator->validate($user, null, $groups);
        if ($violations->count() > 0) {
            throw (new InvalidModelException())->setViolations($violations);
        }

        return $user;
    }

}
