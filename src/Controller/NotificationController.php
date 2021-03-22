<?php

namespace App\Controller;

use App\Entity\Notification;
use App\Repository\NotificationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/api/notifications", name="api_notifications_")
 */
class NotificationController extends AbstractController
{
    /**
     * @Route(methods={"GET"}, name="get_unread")
     */
    public function getNotifications()
    {
        $user = $this->getUser();

        $data['notifications'] = [];
        foreach ($user->getNotifications() as $n) {
            array_push($data['notifications'], $n->serialize());
        }
        return new JsonResponse($data);
    }

    /**
     * @Route("/{notification}", methods={"POST"}, name="mark_as_read")
     * @param Notification $notification
     */
    public function markAsReadNotifications(Notification $notification): JsonResponse
    {
        $manager = $this->getDoctrine()->getManager();
        $user = $this->getUser();

        if ($notification->getUser()->getId() === $user->getId())
        {
            $manager->remove($notification);
            // $notification->setIsRead(true);
            $manager->flush();
            return new JsonResponse(["Notification read!"]);
        } else {
            return new JsonResponse(['error' => "Notification id:".$notification->getId()." doesn't belong to the User!"], 400);
        }
    }
}
