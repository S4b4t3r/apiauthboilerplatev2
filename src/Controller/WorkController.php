<?php

namespace App\Controller;

use App\Entity\File;
use App\Entity\Work;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/works", name="api_works_")
 */
class WorkController extends AbstractController
{
    /**
     * @Route(methods={"GET"}, name="get")
     */
    public function getWorks(): JsonResponse
    {
        $user = $this->getUser();
        $data['user'] = ['username' => $user->getUsername()];
        $data['works'] = $user->getWorksSerialized();

        return new JsonResponse(json_encode($data));
    }

    /**
     * @Route("/{work}", methods={"PUT"}, name="edit")
     * @param Work $work
     */
    public function editWork(Work $work, Request $request): JsonResponse
    {
        $manager = $this->getDoctrine()->getManager();

        $data = json_decode($request->getContent(), true);
        $user = $this->getUser();

        foreach($user->getWork() as $e)
        {
            if ($e->getId() == $work->getId())
            {
                $work->setTitle($data['title']);
                $work->setDescription($data['description']);
                $startDate = \DateTime::createFromFormat('Y-m-d H:i:s.u', $data['startDate']);
                $endDate = \DateTime::createFromFormat('Y-m-d H:i:s.u', $data['endDate']);

                $work->setStartDate($startDate? $startDate: null);
                $work->setEndDate($endDate? $endDate: null);

                $manager->flush();
                return new JsonResponse("Work edited!", 200);
            }
        }
        return new JsonResponse(['error' => "Work id:".$work->getId()." doesn't belong to the User!"], 400);
    }


    /**
     * @Route("/{work}", methods={"DELETE"}, name="delete")
     * @param Work $work
     */
    public function deleteWork(Work $work, Request $request): JsonResponse
    {
        $manager = $this->getDoctrine()->getManager();

        $user = $this->getUser();
        $data = json_decode($request->getContent(), true);

        foreach($user->getWork() as $e)
        {
            if ($e->getId() == $work->getId())
            {
                $manager->remove($work);
                $manager->flush();
                return new JsonResponse("Work deleted!", 200);
            }
        }
        return new JsonResponse(['error' => "Work id:".$work->getId()." doesn't belong to the User!"], 400);
    }

    /**
     * @Route("/{work}/files", methods={"GET"}, name="files_get")
     * @param Work $work
     * Returns the files of x work
     */
    public function getFiles(Work $work): JsonResponse
    {
        return new JsonResponse("Not Implemented", 501);
    }

    /**
     * @Route("/{work}/files", methods={"POST"}, name="files_create")
     * @param Work $work
     */
    public function createFile(Work $work): JsonResponse
    {
        return new JsonResponse("Not Implemented", 501);
    }
}
