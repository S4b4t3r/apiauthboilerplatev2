<?php

namespace App\Controller;

use App\Entity\Assessment;
use App\Entity\Work;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/categories", name="api_assessments_")
 */
class AssessmentController extends AbstractController
{
    /**
     * @Route("/{assessment}/works", methods={"GET"}, name="works_get")
     * @param Assessment $assessment
     * Returns the works of x assessment
     */
    public function getWorks(Assessment $assessment): JsonResponse
    {
        return new JsonResponse("Not Implemented", 501);
    }

    /**
     * @Route("/{assessment}/works", methods={"POST"}, name="works_create")
     * @param Assessment $assessment
     */
    public function createWork(Assessment $assessment): JsonResponse
    {
        return new JsonResponse("Not Implemented", 501);
    }

    /**
     * @Route("/{assessment}/{work}", methods={"PUT"}, name="works_edit")
     * @param Assessment $assessment
     * @param Work $work
     */
    public function editWork(Assessment $assessment, Work $work): JsonResponse
    {
        return new JsonResponse("Not Implemented", 501);
    }

    /**
     * @Route("/{assessment}/{work}", methods={"DELETE"}, name="works_delete")
     * @param Assessment $assessment
     * @param Work $work
     */
    public function deleteWork(Assessment $assessment, Work $work): JsonResponse
    {
        return new JsonResponse("Not Implemented", 501);
    }
}
