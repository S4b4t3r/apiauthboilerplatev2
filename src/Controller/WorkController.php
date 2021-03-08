<?php

namespace App\Controller;

use App\Entity\MediaObject;
use App\Entity\Work;
use App\Service\Base64FileExtractor;
use App\Service\UploadedBase64File;
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
            $data['files'] = [];
            foreach ($work->getMediaObjects() as $file) {
                array_push($data['files'], $file->serialize());
            }

            return new JsonResponse(json_encode($data));
        }

        return new JsonResponse(['error' => "Work id:".$work->getId()." doesn't belong to the User or is not an admin!"], 400);
    }

    /**
     * @Route("/{work}/file", methods={"POST"}, name="files_create")
     * @param Work $work
     */
    public function createFile(Work $work, Request $request, Base64FileExtractor $base64FileExtractor): JsonResponse
    {
        $user = $this->getUser();
        $manager = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);

        if ($work->getUser()->getId() === $user->getId() || !is_null($user->getAdmin()))
        {
            if (isset($data['filename'], $data['file'])) // TODO : Droits de création, Bulk create ?
            {
                $mediaObject = new MediaObject();

                $base64Image = $data['file'];
                // $base64Image = $base64FileExtractor->extractBase64String($base64Image); // TODO : preprocess les données en fonction de ce que Alice m'envoie
                $imageFile = new UploadedBase64File($base64Image, $data['filename']);

                $mediaObject->setFile($imageFile);
                $mediaObject->setWork($work);

                /*
                $file = new File();
                $file->setWork($work);
                $file->setFilename($data['filename']);
                $file->setFile($mediaObject);
                /*
                $file->setCreatedAt(new \DateTime());
                $file->setUpdatedAt(new \DateTime());
                */
                $manager->persist($mediaObject);
                $manager->flush();
                return new JsonResponse("File created!", 200);
            }
            return new JsonResponse(['error' => "Missing data : 'filename', 'file' needed to create File," .
                (!isset($data['filename']) ?: " 'filename'") .
                (!isset($data['file']) ?: " 'file'") .
                " given."], 400);
        }
        return new JsonResponse(['error' => "Work id:".$work->getId()." doesn't belong to the User or is not an admin!"], 400);


    }
}
