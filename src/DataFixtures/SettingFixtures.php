<?php

namespace App\DataFixtures;

use App\Entity\Setting;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class SettingFixtures extends Fixture
{
    public function load(ObjectManager $manager): void
    {
        $setting = $this->createSetting();

        $manager->persist($setting);
        $manager->flush();
    }

    /**
     * cette méthode permet de créer le super admin.
     */
    private function createSetting(): Setting
    {
        $setting = new Setting();

        return $setting
            ->setEmail('plantes-du-monde@gmail.com')
            ->setPhone('0707070707')
            ->setCreatedAt(new \DateTimeImmutable())
            ->setUpdatedAt(new \DateTimeImmutable())
        ;
    }
}
