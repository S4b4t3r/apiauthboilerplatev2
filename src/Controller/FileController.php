<?php

namespace App\Controller;

use App\Entity\File;
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
     * @param File $file
     */
    public function editFile(File $file): JsonResponse
    {
        return new JsonResponse("Not Implemented", 501);
    }

    /**
     * @Route("/file}", methods={"DELETE"}, name="delete")
     * @param File $file
     */
    public function deleteFile(File $file): JsonResponse
    {
        return new JsonResponse("Not Implemented", 501);
    }
}
