<?php
// src/Controller/AppController.php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AppController extends AbstractController
{
    /**
     * @Route("/", name="app")
     */
    public function default()
    {
        return $this->render('app.html.twig', [
        ]);
    }
}