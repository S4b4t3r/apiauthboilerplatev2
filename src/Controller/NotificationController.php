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
     * @Route(methods={"GET"}, name="get_unread")
     */
    public function getNotifications(NotificationRepository $notificationRepository)
    {
        $user = $this->getUser();

        $data['notifications'] = [];
        foreach ($notificationRepository->findNotRead() as $n) {
            array_push($data['notifications'], $n->serialize);
        }
        return new JsonResponse(json_encode($data));
    }
}
