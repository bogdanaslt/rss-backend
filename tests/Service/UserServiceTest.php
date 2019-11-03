<?php

namespace App\Tests\Service;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use App\Service\UserService;
use App\Entity\User;
use App\Exception\InvalidModelException;
use App\Repository\UserRepository;
use Symfony\Component\DependencyInjection\ContainerInterface;

class UserServiceTest extends KernelTestCase
{
    
    /** @var UserService */
    private $userService;
    
    public function setUp()
    {
        self::bootKernel();
        $container = self::$container;

        $this->userService = $container->get(UserService::class);
    }
    
    public function tearDown()
    {
        $container = self::$container;
        $container->get(UserRepository::class)
            ->createQueryBuilder('u')
            ->delete()
            ->getQuery()
            ->execute();
            parent::tearDown();
    }
    
    public function testUserEmailValidation()
    {
        $user = new User();
        $user->setEmail('valid@email.com');
        
        $result = $this->userService->validate($user, 'email');
        
        $this->assertInstanceOf(User::class, $result);
    }
    
    public function testUserInvalidEmailValidation()
    {
        $this->expectException(InvalidModelException::class);
        
        $user = new User();
        $user->setEmail('no-a-valid-email-address');
        
        $this->userService->validate($user, 'email');
    }
    
    public function testUserValidation()
    {
        $this->expectException(InvalidModelException::class);

        $user = new User();
        $user->setEmail('valid@email.com');
        
        $this->userService->validate($user);
    }
    
    public function testUserRegistrationWithEmptyPassword()
    {
        $this->expectException(InvalidModelException::class);
        $user = new User();
        $user->setEmail('valid@email.com');
        
        $this->userService->register($user);
    }
    
    public function testUserRegistrationWithInvalidEmail()
    {
        $this->expectException(InvalidModelException::class);
        $user = new User();
        $user
            ->setEmail('invalid-email')
            ->setPassword('s3cr3t');
        
        $this->userService->register($user);
    }
    
    public function testUserRegistration()
    {
        $user = new User();
        $user
            ->setEmail('valid@email.com')
            ->setPassword('s3cr3t');
        
        $result = $this->userService->register($user);
        
        $this->assertInstanceOf(User::class, $result);
    }
    
    public function validateUniqueEmail()
    {
        $this->expectException(InvalidModelException::class);
         
        $user = new User();
        $user
            ->setEmail('valid@email.com')
            ->setPassword('s3cr3t');
        
        $this->userService->register($user);
    }
    
    
}
