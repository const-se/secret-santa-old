<?php

namespace AppBundle\Controller;

use AppBundle\Entity\User;
use AppBundle\Form\Type\User\LoginType;
use LogicException;
use OutOfBoundsException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration as Config;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/**
 * @Config\Route("/admin")
 * @Config\Security("['ROLE_ADMIN']")
 */
class AdminController extends Controller
{
    /**
     * @Config\Route("/", name = "admin_index")
     * @return Response
     */
    public function index(): Response
    {
        return $this->render('AppBundle:Admin:index.html.twig');
    }

    /**
     * @Config\Route("/login", name = "admin_login")
     * @Config\Security("['IS_AUTHENTICATED_ANONYMOUSLY']")
     * @return Response
     * @throws LogicException
     * @throws OutOfBoundsException
     */
    public function login(): Response
    {
        if ($this->isGranted(['IS_AUTHENTICATED_FULLY', 'IS_AUTHENTICATED_REMEMBERED'])) {
            return $this->redirectToRoute('admin_index');
        }

        $user = new User();
        /** @var AuthenticationUtils $authUtils */
        $authUtils = $this->get('security.authentication_utils');
        $user->setUsername($authUtils->getLastUsername());
        $loginForm = $this->createForm(LoginType::class, $user);

        if ($error = $authUtils->getLastAuthenticationError()) {
            $loginForm->get('username')->addError(new FormError('Неправильный логин...'));
            $loginForm->get('password')->addError(new FormError('...или пароль'));
        }

        return $this->render('AppBundle:Admin:login.html.twig', ['login_form' => $loginForm->createView()]);
    }

    /**
     * @Config\Route("/login-check", name = "admin_login_check")
     * @throws NotFoundHttpException
     */
    public function loginCheck(): void
    {
        throw $this->createNotFoundException();
    }

    /**
     * @Config\Route("/logout", name = "admin_logout")
     * @throws NotFoundHttpException
     */
    public function logout(): void
    {
        throw $this->createNotFoundException();
    }
}
