<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class UserFixtures extends Fixture
{
    /**
     * PasswordHasher
     *
     * @var mixed
     */
    private $passwordHasher;

    /**
     * __construct
     *
     * @param  UserPasswordHasherInterface $passwordHasher passwordHasher
     *
     * @return void
     */
    public function __construct(UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * GetReferenceKey
     *
     * @param mixed $key key
     *
     * @return string
     */
    public static function getReferenceKey($key): string
    {
        return sprintf('user_%s', $key);
    }

    /**
     * Loading
     *
     * @param ObjectManager $manager Manager
     *
     * @return void
     */
    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $faker->seed(2);

        // User admin fixtures.
        $admin = new User();

        $admin->setName('admin')
            ->setEmail('admin@email.com')
            ->setRoles(['ROLE_ADMIN']);

        $password = $this->passwordHasher->hashPassword($admin, 'azertyuiop');
        $admin->setPassword($password);

        $manager->persist($admin);

        // User fixtures
        for ($i = 0; $i < 10; $i++) {
            $user = new User();
            $user->setName($faker->name())
                ->setEmail('user' . $i . '@email.com')
                ->setRoles(['ROLE_USER']);

            $password = $this->passwordHasher->hashPassword($user, 'azertyuiop');
            $user->setPassword($password);

            $manager->persist($user);
            $this->addReference(self::getReferenceKey($i), $user);
        }

        $manager->flush();
    }
}
