<?php

namespace App\DataFixtures;

use App\Service\Slugify;
use Faker;
use App\Entity\Episode;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

class EpisodeFixtures extends Fixture implements DependentFixtureInterface
{
    public function load(ObjectManager $manager)
    {
        $faker = Faker\Factory::create('fr_FR');
        for($i = 0; $i < 50; $i++) {
            $episode = new Episode();
            $slugify = new Slugify();
            $episode->setTitle($faker->sentence(3));
            $slug = $slugify->generate($episode->getTitle());
            $episode->setNumber($faker->numberBetween(1, 24));
            $episode->setSynopsis($faker->text);
            $episode->setSlug($slug);
            $episode->setSeason($this->getReference('season_' . rand(0,49)));
            $manager->persist($episode);
        }
        $manager->flush();
    }

    public function getDependencies()
    {
        return [SeasonFixtures::class];
    }
}
