<?php

namespace App\Controller;

use App\Entity\Assessment;
use App\Entity\Category;
use App\Repository\AssessmentRepository;
use App\Repository\CategoryRepository;
use phpDocumentor\Reflection\DocBlock\Tags\Reference\Reference;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Json;

/**
 * @Route("/api/categories", name="api_categories_")
 */
class CategoryController extends AbstractController
{
    /**
     * @Route(methods={"GET"}, name="get")
     */
    public function getCategories(CategoryRepository $categoryRepository): JsonResponse
    {
        $data['categories'] = [];
        foreach ($categoryRepository->findAll() as $c) {
            array_push($data['categories'], $c->serialize());
        }
        return new JsonResponse(json_encode($data));
    }

    /**
     * @Route(methods={"POST"}, name="create")
    */
    public function createCategory(Request $request): JsonResponse
    {
        $admin = $this->getUser()->getAdmin();
        if ($admin != null)
        {
            $manager = $this->getDoctrine()->getManager();
            $data = json_decode($request->getContent(), true);

            $category = new Category();
            $category->setAdmin($admin);
            $category->setTitle($data['title']);
            $manager->persist($category);
            $manager->flush();
            return new JsonResponse("Category created!", 200);

        } else return new JsonResponse(['error' => "User is not an admin !"], 400);
    }

    /**
     * @Route("/{category}", methods={"PUT"}, name="edit")
     * @param Category $category
    */
    public function editCategory(Category $category, Request $request): JsonResponse
    {
        $admin = $this->getUser()->getAdmin();
        if ($admin != null) {
            $manager = $this->getDoctrine()->getManager();
            $data = json_decode($request->getContent(), true);

            $category = new Category();
            $category->setAdmin($admin);
            $category->setTitle($data['title']);
            $manager->flush();
            return new JsonResponse("Category edited!", 200);

        } else return new JsonResponse(['error' => "User is not an admin !"], 400);
    }


    /**
     * @Route("/{category}", methods={"DELETE"}, name="delete")
     * @param Category $category
    */
    public function deleteCategory(Category $category): JsonResponse
    {
        $admin = $this->getUser()->getAdmin();
        if ($admin != null) {
            $manager = $this->getDoctrine()->getManager();
            $manager->remove($category);
            $manager->flush();
            return new JsonResponse("Category deleted!", 200);

        } else return new JsonResponse(['error' => "User is not an admin !"], 400);
    }

    /**
     * @Route("/{category}/assessments", methods={"GET"}, name="assessments_get")
     * @param Category $category
     * Returns the assessments of x category
     */
    public function getAssessments(Category $category, AssessmentRepository $assessmentRepository): JsonResponse
    {
        $data['category'] = $category->serialize();
        $data['assessments'] = [];
        foreach ($assessmentRepository->findBy(['category' => $category->getId()]) as $a) {
            array_push($data['assessments'], $a->serialize());
        }

        return new JsonResponse(json_encode($data));
    }

    /**
     * @Route("/{category}/assessments", methods={"POST"}, name="assessments_create")
     * @param Category $category
     */
    public function createAssessment(Category $category, Request $request): JsonResponse
    {
        $admin = $this->getUser()->getAdmin();
        if ($admin != null)
        {
            $manager = $this->getDoctrine()->getManager();
            $data = json_decode($request->getContent(), true);

            $assessment = new Assessment();
            $assessment->setCategory($category);
            $assessment->setTitle($data['title']);
            $assessment->setDescription($data['description']);
            $dueDate = \DateTime::createFromFormat('Y-m-d H:i:s.u', $data['dueDate']);
            $assessment->setDueDate($dueDate? $dueDate: null);

            $manager->persist($assessment);
            $manager->flush();
            return new JsonResponse("Assessment created!", 200);

        } else return new JsonResponse(['error' => "User is not an admin !"], 400);
    }
}
