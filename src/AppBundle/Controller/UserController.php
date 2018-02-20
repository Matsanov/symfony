<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Role;
use AppBundle\Entity\User;
use AppBundle\Form\RegisterType;
use AppBundle\Service\UserServiceInterface;
use Doctrine\Common\Collections\Collection;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class UserController extends Controller
{

    /**
     * @var UserServiceInterface
     */
    private $userService;

    /**
     * UserController constructor.
     * @param UserServiceInterface $userService
     */
    public function __construct(UserServiceInterface $userService)
    {
        $this->userService = $userService;
    }


    /**
     * @Route("/register", name="register_user")
     * @param Request $request
     * @param UserPasswordEncoderInterface $passwordEncoder
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function registerAction(Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        $user = new User();

        $form = $this->createForm(RegisterType::class,$user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $this->userService->register($user,$passwordEncoder);

            return $this->redirectToRoute('login_user');
        }

        return $this->render('user/register.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/users", name="all_users")
     */
    public function allUsers(){

        $allUsers = $this->userService->viewAll();

        return $this->render('users.html.twig',['allUsers' => $allUsers]);
    }

}
