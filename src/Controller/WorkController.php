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

        if ($work->getUser()->getId() === $user->getId() || !is_null($user->getAdmin()))
        {
            if (isset($data['title'], $data['description'], $data['is_public']))
            {
                $work->setTitle($data['title']);
                $work->setDescription($data['description']);
                $work->setUpdatedAt(new \DateTime());

                $manager->flush();
                return new JsonResponse("Work edited!", 200);
            } else return new JsonResponse(['error' => "Missing data : 'title', 'description' & 'is_public' needed to create Work," . (!isset($data['title'])?:" 'title'") . (!isset($data['description'])?:" 'description'") . (!isset($data['is_public'])?:" 'is_public'") . " given."], 400);
        }

        return new JsonResponse(['error' => "Work id:".$work->getId()." doesn't belong to the User or is not an admin!"], 400);
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

        if ($work->getUser()->getId() === $user->getId() || !is_null($user->getAdmin()))
        {
            $manager->remove($work);
            $manager->flush();
            return new JsonResponse("Work deleted!", 200);
        }
        return new JsonResponse(['error' => "Work id:".$work->getId()." doesn't belong to the User or is not an admin!"], 400);
    }

    /**
     * @Route("/{work}/files", methods={"GET"}, name="files_get")
     * @param Work $work
     * Returns the files of x work
     */
    public function getFiles(Work $work): JsonResponse
    {
        $user = $this->getUser();
        $data['work'] = $work->serialize();
        
        if ($work->getUser()->getId() === $user->getId() || !is_null($user->getAdmin()))
        {
            foreach ($work->getFiles() as $file) {
                array_push($data['files'], $file->serialize());
            }

            return new JsonResponse(json_encode($data));
        }

        return new JsonResponse(['error' => "Work id:".$work->getId()." doesn't belong to the User or is not an admin!"], 400);
    }

    /**
     * @Route("/{work}/files", methods={"POST"}, name="files_create")
     * @param Work $work
     */
    public function createFile(Work $work, Request $request): JsonResponse
    {
        $user = $this->getUser();
        $manager = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);

        if ($work->getUser()->getId() === $user->getId() || !is_null($user->getAdmin()))
        {
            if (isset($data['filename'], $data['file'], $data['created_at'])) // TODO : Droits de création, Bulk create ?
            {
                $file = new File();
                $file->setWork($work);
                $file->setFilename();
                $file->setFile();
                $file->setCreatedAt(new \DateTime());
                $file->setUpdatedAt(null);
                $manager->persist($file);
            }
            return new JsonResponse(['error' => "Missing data : 'filename', 'file & 'created_at' needed to create File," .
                (!isset($data['filename']) ?: " 'filename'") .
                (!isset($data['file']) ?: " 'file'") .
                (!isset($data['created_at']) ?: " 'created_at'") .
                " given."], 400);
        }
        return new JsonResponse(['error' => "Work id:".$work->getId()." doesn't belong to the User or is not an admin!"], 400);


    }
}
