<?php

namespace App\Controller;

use App\Entity\MediaObject;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
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
    public function editMediaObject(MediaObject $file): JsonResponse
    {
        return new JsonResponse("Not Implemented", 501);
    }

    /**
     * @Route("/file}", methods={"DELETE"}, name="delete")
     * @param MediaObject $file
     */
    public function deleteMediaObject(MediaObject $file): JsonResponse
    {
        return new JsonResponse("Not Implemented", 501);
    }
}
