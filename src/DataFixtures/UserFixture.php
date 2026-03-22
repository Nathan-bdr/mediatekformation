<?php
namespace App\DataFixtures;

use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Fixture pour créer l'utilisateur administrateur
 * @author Nathan Boudier
 */
class UserFixture extends Fixture {

    /**
     * @var UserPasswordHasherInterface
     */
    private $passwordHasher;

    /**
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher) {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Crée l'utilisateur administrateur en base de données
     * @param ObjectManager $manager
     */
    public function load(ObjectManager $manager): void {
        $user = new User();
        $user->setUsername("admin");
        $plaintextPassword = "Sio1234*";
        $hashedPassword = $this->passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        $user->setPassword($hashedPassword);
        $user->setRoles(['ROLE_ADMIN']);
        $manager->persist($user);
        $manager->flush();
    }
}