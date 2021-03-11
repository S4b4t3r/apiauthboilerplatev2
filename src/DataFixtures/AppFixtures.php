<?php

namespace App\DataFixtures;

use App\Entity\Assessment;
use App\Entity\Category;
use App\Entity\Admin;
use App\Entity\MediaObject;
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
        $user->setEmail("abcd@ab.cd");
        $password = $this->encoder->encodePassword($user, 'abcd');
        $user->setPassword($password);
        $user->setNom("Admin");
        $user->setPrenom("Abcd");
        $user->setAdmin($admin);

        $user2 = new User();
        $user2->setEmail("jules@ab.cd");
        $password = $this->encoder->encodePassword($user2, 'abcd');
        $user2->setPassword($password);
        $user2->setNom("Sabater");
        $user2->setPrenom("Jules");

        for ($i = 1; $i < 3; $i++) {
            $category = new Category();
            $category->setTitle('Test category ' . $i);
            $category->setDescription('Test description ' . $i);
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
                    $work->setIsPublic($k == 1); // (une entité sera true, l'autre false)
                    $work->setAssessment($assessment);
                    $work->setUser($j % 2 == 0 ? $user : $user2);
                    $work->setCreatedAt(new DateTime('now'));
                    $work->setUpdatedAt(new DateTime('now'));

                    for ($l = 1; $l < 3; $l++) {
                        $file = new MediaObject();
                        $file->setWork($work);
                        /*
                        $file->setFile();
                        $file->setFilename('test_file_'.$k.$l);
                        */

                        $manager->persist($file);
                    }

                    $manager->persist($work);
                }

                $manager->persist($assessment);
            }
            $manager->persist($category);
        }

        for ($i = 1; $i < 7; $i++) {
            $notification = new Notification();
            $notification->setUser($user);
            $notification->setType('newupload');
            $notification->setIsRead($i%2 == 0 ? true : false); // Pour la clarté (une entité sera true, l'autre false)
            $notification->setText('Un nouveau travail à été uploadé sur Assessment 1');
            $manager->persist($notification);
        }

        $manager->persist($admin);
        $manager->persist($user);
        $manager->persist($user2);

        $manager->flush();
    }
}


