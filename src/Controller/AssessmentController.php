<?php

namespace App\Controller;

use App\Entity\Assessment;
use App\Entity\Notification;
use App\Entity\Work;
use App\Repository\LikeRepository;
use App\Repository\NotificationRepository;
use App\Repository\WorkRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/assessments", name="api_assessments_")
 */
class AssessmentController extends AbstractController
{
    /**
     * @Route("/{assessment}", methods={"PUT"}, name="edit")
     * @param Assessment $assessment
     */
    public function editAssessment(Assessment $assessment, Request $request): JsonResponse
    {
        $admin = $this->getUser()->getAdmin();
        if ($admin != null)
        {
            $manager = $this->getDoctrine()->getManager();
            $data = json_decode($request->getContent(), true);

            $assessment->setTitle($data['title']);
            $assessment->setDescription($data['description']);
            $dueDate = \DateTime::createFromFormat('Y-m-d H:i:s.u', $data['dueDate']);
            $assessment->setDueDate($dueDate? $dueDate: null);

            $manager->flush();
            return new JsonResponse($assessment->serialize());

        } else return new JsonResponse(['error' => "User is not an admin !"], 400);
    }

    /**
     * @Route("/{assessment}", methods={"DELETE"}, name="delete")
     * @param Assessment $assessment
     */
    public function deleteAssessment(Assessment $assessment): JsonResponse
    {
        $admin = $this->getUser()->getAdmin();
        if ($admin != null) {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($assessment);
            $manager->flush();
            return new JsonResponse("Assessment deleted!");

        } else return new JsonResponse(['error' => "User is not an admin !"], 400);
    }

    /**
     * @Route("/{assessment}/works", methods={"GET"}, name="works_get")
     * @param Assessment $assessment
     * Returns the works of x assessment
     */
    public function getWorks(Assessment $assessment, LikeRepository $likeRepository): JsonResponse
    {
        $user = $this->getUser();
        $data['assessment'] = $assessment->serialize();
        $data['works'] = [];

        foreach ($assessment->getWorks() as $w) {
            $work = $w->serialize();
            $work['is_liked'] = (!is_null($likeRepository->findOneBy(['user' => $user->getId(), 'work' => $w->getId()])));
            array_push($data['works'], $work);
        }

        return new JsonResponse($data);
    }

    /**
     * @Route("/{assessment}/work", methods={"POST"}, name="works_create")
     * @param Assessment $assessment
     */
    public function createWork(Assessment $assessment, Request $request, NotificationRepository $notificationRepository): JsonResponse
    {
        // $admin = $this->getUser()->getAdmin();
        $manager = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);
        $user = $this->getUser();

        if (isset($data['title']) && isset($data['description']) && isset($data['is_public'])) // TODO : Droits de création
        {
            $work = new Work();
            $work->setAssessment($assessment);
            $work->setUser($user);
            $work->setTitle($data['title']);
            $work->setDescription($data['description']);
            $work->setIsPublic($data['is_public']);

            if ($notificationRepository->findOneBy(["type" => "worksubmitted_" . $work->getId()]) != null) {
                $notification = new Notification();
                $notification->setUser($user);
                $notification->setType("worksubmitted_" . $work->getId()); // TODO : il devrait il y avoir un champ "extra-data"
                $notification->setText("<a data-workid='" . $work->getId() . "'>" . $work->getTitle() . "</a> a bien été envoyé !");
                $notification->setIsRead(false);

                $manager->persist($notification);
            }

            $manager->persist($work);
            $manager->flush();
            return new JsonResponse($work->serialize());

        }  else return new JsonResponse(['error' => "Missing data : 'title', 'description' & 'is_public' needed to create Work," . (!isset($data['title'])?"":" 'title'") . (!isset($data['description'])?"":" 'description'") . (!isset($data['is_public'])?"":" 'is_public'") . " given."], 400);
    }
}
