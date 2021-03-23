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
        $categoryTitles = ['Mathématiques', 'Infographie'];
        $assessmentTitles = ['TP 1 : Introduction à l\'addition', 'TP 2 : Produit de matrice Jacobienne en 4 dimensions', "Design d'une enseigne de baignoires", "Photoshop : Votre Abbey Road"];
        $workTitles = ['My assessment', 'Rendu TP', 'Preuve par l\'inverse', 'Démonstration par l\'absurde', "BainCorp : la baignoire du businessman", "Batsu", "Abbey Highway to Hell", "Abbey Road Godzilla"];


        $admin = new Admin();

        $user = new User();
        $user->setEmail("abcd@ab.cd");
        $password = $this->encoder->encodePassword($user, 'abcd');
        $user->setPassword($password);
        $user->setNom("Admin");
        $user->setPrenom("Abcd");
        $user->setAdmin($admin);

        $user2 = new User();
        $user2->setEmail("alice@ab.cd");
        $password = $this->encoder->encodePassword($user2, 'abcd');
        $user2->setPassword($password);
        $user2->setNom("Pristchepa");
        $user2->setPrenom("Alice");

        $user3 = new User();
        $user3->setEmail("jules@ab.cd");
        $password = $this->encoder->encodePassword($user2, 'abcd');
        $user3->setPassword($password);
        $user3->setNom("Sabater");
        $user3->setPrenom("Jules");

        for ($i = 0; $i < 2; $i++) {
            $category = new Category();
            $category->setTitle($categoryTitles[$i]);
            $category->setDescription('La description de la catégorie');
            $category->setAdmin($admin);

            for ($j = 0; $j < 2; $j++) {
                $assessment = new Assessment();
                $assessment->setTitle($assessmentTitles[(2*$i)+$j]);
                $assessment->setDescription('La description du travail à rendre');
                $assessment->setCategory($category);
                $date = new DateTime('now');
                $date->modify($j == 1 ? '-7 day' : '+7 day');
                $assessment->setDueDate($date);

                for ($k = 0; $k < 2; $k++) {
                    $work = new Work();
                    $work->setTitle($workTitles[4*$i+2*$j+$k]);
                    $work->setDescription('Mon travail');
                    $work->setIsPublic($k == 1); // (une entité sera true, l'autre false)
                    $work->setAssessment($assessment);
                    $work->setUser($j % 2 == 0 ? $user3 : $user2);
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

        for ($i = 0; $i < 3; $i++) {
            $notification = new Notification();
            $notification->setUser($user);
            $notification->setType('newupload');
            $notification->setIsRead(false); // Pour la clarté (une entité sera true, l'autre false)
            $notification->setText('Un utilisateur a liké votre travail !');
            $manager->persist($notification);
        }

        $manager->persist($admin);
        $manager->persist($user);
        $manager->persist($user2);
        $manager->persist($user3);

        $manager->flush();
    }
}


