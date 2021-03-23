<?php

namespace App\Controller;

use App\Entity\MediaObject;
use App\Service\Base64FileExtractor;
use App\Service\UploadedBase64File;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/files", name="api_files_")
 */
class FileController extends AbstractController
{
    /**
     * @Route("/{file}", methods={"PUT"}, name="edit")
     * @param MediaObject $file
     */
    public function editMediaObject(MediaObject $file, Request $request, Base64FileExtractor $base64FileExtractor): JsonResponse
    {
        $user = $this->getUser();
        $manager = $this->getDoctrine()->getManager();
        $data = json_decode($request->getContent(), true);

        if ($file->getWork()->getUser()->getId() === $user->getId() || !is_null($user->getAdmin()))
        {
            if (isset($data['filename'], $data['file']))
            {
                $mediaObject = new MediaObject();

                $base64Image = $data['file'];
                $base64Image = $base64FileExtractor->extractBase64String($base64Image);
                $imageFile = new UploadedBase64File($base64Image, $data['filename']);

                $mediaObject->setFile($imageFile);
                $mediaObject->setUpdatedAt(new \DateTime('now'));

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
                return new JsonResponse("File created!");
            }
            return new JsonResponse(['error' => "Missing data : 'filename', 'file' needed to create File," .
                (!isset($data['filename']) ?: " 'filename'") .
                (!isset($data['file']) ?: " 'file'") .
                " given."], 400);
        }
        return new JsonResponse(['error' => "File id:".$file->getId()." doesn't belong to the User or is not an admin!"], 400);
    }

    /**
     * @Route("/{file}", methods={"DELETE"}, name="delete")
     * @param MediaObject $file
     */
    public function deleteMediaObject(MediaObject $file): JsonResponse
    {
        $user = $this->getUser();
        $manager = $this->getDoctrine()->getManager();

        if ($file->getWork()->getUser()->getId() === $user->getId() || !is_null($user->getAdmin()))
        {
            $manager->remove($file);
            $manager->flush();
            return new JsonResponse("File deleted!", 200);
        }
        return new JsonResponse(['error' => "File id:".$file->getId()." doesn't belong to the User or is not an admin!"], 400);
    }
}
