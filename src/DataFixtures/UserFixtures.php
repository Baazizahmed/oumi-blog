<?php

namespace App\DataFixtures;

use App\Entity\User;
use DateTimeImmutable;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    public function __construct(
        private UserPasswordHasherInterface $hasher,
    ) {
    }
    
    public function load(ObjectManager $manager): void
    {
        $user1 = $this->createUser1();
        $user2 = $this->createUser2();
        $user3 = $this->createUser3();

        $manager->persist($user1);
        $manager->persist($user2);
        $manager->persist($user3);
        
        $manager->flush();
    }

    /**
     * cette methode permet de créer l'utilisateur1.
     */
    private function createUser1(): User
    {
        $user = new User();

        $passwordHashed = $this->hasher->hashPassword($user, 'azerty1234A*');

        $user->setFirstName('ahmed');
        $user->setLastName('BAAZIZ');
        $user->setEmail('baazizahmed@gmail.com');
        $user->setRoles(['ROLE_USER']);
        $user->setIsVerified(true);
        $user->setPassword($passwordHashed);
        $user->setCreatedAt(new DateTimeImmutable());
        $user->setVerifiedAt(new \DateTimeImmutable());
        $user->setUpdatedAt(new \DateTimeImmutable());

        return $user;
    }

    /**
     * cette methode permet de créer l'utilisateur2.
     */
    private function createUser2(): User
    {
        $user = new User();

        $passwordHashed = $this->hasher->hashPassword($user, 'azerty1234A*');

        $user->setFirstName('Mohamed');
        $user->setLastName('BAAZIZ');
        $user->setEmail('baazizmohamed@gmail.com');
        $user->setRoles(['ROLE_USER']);
        $user->setIsVerified(true);
        $user->setPassword($passwordHashed);
        $user->setCreatedAt(new DateTimeImmutable());
        $user->setVerifiedAt(new \DateTimeImmutable());
        $user->setUpdatedAt(new \DateTimeImmutable());

        return $user;
    }

    /**
     * cette methode permet de créer l'utilisateur3.
     */
    private function createUser3(): User
    {
        $user = new User();

        $passwordHashed = $this->hasher->hashPassword($user, 'azerty1234A*');

        $user->setFirstName('ibrahim');
        $user->setLastName('BAAZIZ');
        $user->setEmail('baazizibrahim@gmail.com');
        $user->setRoles(['ROLE_USER']);
        $user->setIsVerified(true);
        $user->setPassword($passwordHashed);
        $user->setCreatedAt(new DateTimeImmutable());
        $user->setVerifiedAt(new \DateTimeImmutable());
        $user->setUpdatedAt(new \DateTimeImmutable());

        return $user;
    }
}
