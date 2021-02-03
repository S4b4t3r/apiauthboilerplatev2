<?php

namespace App\Controller;

use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/notifications", name="api_notifications_")
 */
class NotificationController extends AbstractController
{
    /**
     * @Route(methods={"GET"}, name="get")
     */
    public function getNotifications(NotificationRepository $notificationRepository)
    {
        $user = $this->getUser();

        $data['notifications'] = [];
        foreach ($notificationRepository->findAll() as $c) {
            $data['categories'] = $c;
        }
        return new JsonResponse(json_encode($data));
    }
}
