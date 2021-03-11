<?php

namespace App\Controller;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use ProxyManager\ProxyGenerator\NullObject\MethodGenerator\StaticProxyConstructor;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class RegistrationController extends AbstractController
{
    /** @var UserRepository $userRepository */
    private $userRepository;

    private $encoder;

    /**
     * AuthController Constructor
     *
     * @param UserRepository $userRepository
     */
    public function __construct(UserRepository $userRepository, UserPasswordEncoderInterface $encoder)
    {
        $this->userRepository = $userRepository;
        $this->encoder = $encoder;
    }

    /**
     * @Route("/registration", name="registration")
     * Register new user
     * @param Request $request
     * @param EntityManagerInterface $manager
     *
     * @return Response
     */
    public function register(Request $request, EntityManagerInterface $manager)
    {
        $data = json_decode($request->getContent(), true);

        if (isset($data['email'], $data['password'], $data['prenom'], $data['nom']))
        {
            $user = new User();
            $user->setEmail($data['email']);
            $password = $this->encoder->encodePassword($user, $data['password']);
            $user->setPassword($password);
            $user->setPrenom($data['prenom']);
            $user->setNom($data['nom']);
            $manager->persist($user);
            $manager->flush();
            return new JsonResponse("User created!", 200);
        }
        return new JsonResponse(['error' => "Missing data : 'email', 'password', 'prenom', 'nom' needed to create User," .
            (!isset($data['email']) ?: " 'email'") .
            (!isset($data['password']) ?: " 'password'") .
            (!isset($data['prenom']) ?: " 'prenom'") .
            (!isset($data['nom']) ?: " 'nom'") .
            " given."], 400);
    }
}
