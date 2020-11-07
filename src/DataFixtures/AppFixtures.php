<?php

namespace App\DataFixtures;

use App\Entity\Assessment;
use App\Entity\Category;
use App\Entity\Admin;
use App\Entity\Notification;
use App\Entity\User;
use App\Entity\Work;
use DateTime;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Validator\Constraints\Date;

class AppFixtures extends Fixture
{
    private $encoder;

    public function __construct(UserPasswordEncoderInterface $encoder)
    {
        $this->encoder = $encoder;
    }

    public function load(ObjectManager $manager)
    {
        // GENERATE TEST DATA
        $admin = new Admin();

        $user = new User();
        $user->setUsername('abcd');
        $user->setEmail("abcd@ab.cd");
        $password = $this->encoder->encodePassword($user, 'abcd');
        $user->setPassword($password);
        $user->setAdmin($admin);

        $user2 = new User();
        $user2->setUsername('jsabater');
        $user2->setEmail("jules@ab.cd");
        $password = $this->encoder->encodePassword($user2, 'abcd');
        $user2->setPassword($password);

        for ($i = 1; $i < 3; $i++) {
            $category = new Category();
            $category->setTitle('Test category ' . $i);
            $category->setAdmin($admin);

            for ($j = 1; $j < 3; $j++) {
                $assessment = new Assessment();
                $assessment->setTitle('Test assessment ' . $i);
                $assessment->setDescription('Assessment description');
                $assessment->setCategory($category);
                $date = new DateTime('now');
                $date->modify($j == 1 ? '-7 day' : '+7 day');
                $assessment->setDueDate($date);

                for ($k = 1; $k < 3; $k++) {
                    $work = new Work();
                    $work->setTitle('Test work ' . $k);
                    $work->setDescription('Work description');
                    $work->setIsPublic($k == 1 ? true : false); // Pour la clarté (une entité sera true, l'autre false)
                    $work->setAssessment($assessment);
                    $work->setUser($user2);
                    $work->setCreatedAt(new DateTime('now'));
                    $work->setUpdatedAt(new DateTime('now'));

                    $manager->persist($work);
                }

                $manager->persist($assessment);
            }
            $manager->persist($category);
        }

        for ($i = 1; $i < 4; $i++) {
            $notification = new Notification();
            $notification->setUser($user);
            $notification->setType('newupload');
            $notification->setIsRead($i == 1 ? true : false); // Pour la clarté (une entité sera true, l'autre false)
            $notification->setText('Un nouveau travail à été uploadé sur Assessment 1');
            $manager->persist($notification);
        }

        $manager->persist($admin);
        $manager->persist($user);
        $manager->persist($user2);

        $manager->flush();
    }
}
