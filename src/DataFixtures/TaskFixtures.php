<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Task;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class TaskFixtures extends Fixture implements DependentFixtureInterface
{
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

        for ($i = 0; $i < 50; $i++) {
            $task = new Task();

            $user = $this->getReference(UserFixtures::getReferenceKey($i % 10));

            $task->setTitle($faker->word(15, true))
                ->setContent($faker->text(150))
                ->setIsDone($faker->boolean(50))
                ->setCreatedBy($user);

            $manager->persist($task);
        }

        $manager->flush();
    }

    /**
     * GetDependencies
     *
     * @return array
     */
    public function getDependencies(): array
    {
        return [UserFixtures::class];
    }
}
