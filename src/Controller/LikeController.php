<?php

namespace App\Controller;

use App\Entity\Like;
use App\Entity\MediaObject;
use App\Entity\Work;
use App\Repository\LikeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/like", name="api_likes_")
 */
class LikeController extends AbstractController
{
    /**
     * @Route(methods={"GET"}, name="get")
     */
    public function getLikes(): JsonResponse
    {
        $user = $this->getUser();
        $data['user'] = ['username' => $user->getUsername()];
        $data['likes'] = $user->getLikesSerialized();

        return new JsonResponse($data);
    }

    /**
     * @Route("/{work}", methods={"POST"}, name="add")
     * @param Work $work
     */
    public function addLike(Work $work, LikeRepository $likeRepository): JsonResponse
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $like = $likeRepository->findOneBy(['user' => $user->getId(), 'work' => $work->getId()]);

        if (is_null($like))
        {
            $like = new Like();
            $like->setWork($work);
            $like->setUser($user);
            $manager->persist($like);
            $manager->flush();
            return new JsonResponse("Like added!");
        }
        return new JsonResponse(['error' => "Already liked!"], 400);

    }


    /**
     * @Route("/{work}", methods={"DELETE"}, name="delete")
     * @param Work $work
     */
    public function deleteLike(Work $work, LikeRepository $likeRepository): JsonResponse
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        $like = $likeRepository->findOneBy(['user' => $user->getId(), 'work' => $work->getId()]);

        if (!is_null($like))
        {
            $manager->remove($like);
            $manager->flush();
            return new JsonResponse("Like deleted!");
        }
        return new JsonResponse(['error' => "Not liked!"], 400);

    }
}
