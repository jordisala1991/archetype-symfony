<?php

namespace Runroom\AdminUserBundle\Security;

use Runroom\AdminUserBundle\Entity\User;
use Runroom\AdminUserBundle\Repository\UserRepository;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class UserManipulator
{
    private $passwordEncoder;
    private $userRepository;

    public function __construct(
        UserPasswordEncoderInterface $passwordEncoder,
        UserRepository $userRepository
    ) {
        $this->passwordEncoder = $passwordEncoder;
        $this->userRepository = $userRepository;
    }

    public function create($email, $password, $superAdmin)
    {
        $user = new User();
        $user->setEmail($email);
        $user->setPlainPassword($password);
        $user->setEnabled(true);

        if ($superAdmin) {
            $user->setRoles(['ROLE_SUPER_ADMIN']);
        } else {
            $user->setRoles(['ROLE_ADMIN']);
        }

        $this->setCreatedAt($user);
        $this->hashPassword($user);
        $this->userRepository->persist($user);

        return $user;
    }

    public function hashPassword(UserInterface $user)
    {
        $plainPassword = $user->getPlainPassword();

        if (0 === \strlen($plainPassword)) {
            return;
        }

        $user->setSalt(\base64_encode(\random_bytes(32)));

        $hashedPassword = $this->passwordEncoder->encodePassword($user, $plainPassword);

        $user->setPassword($hashedPassword);
        $user->eraseCredentials();
    }

    public function isPasswordValid(UserInterface $user, string $password)
    {
        return $this->passwordEncoder->isPasswordValid($user, $password);
    }

    public function setCreatedAt(UserInterface $user): void
    {
        $user->setCreatedAt(new \DateTime());
    }

    public function updateLastLogin(UserInterface $user): void
    {
        $user->setLastLogin(new \DateTime());

        $this->userRepository->persist($user);
    }
}
