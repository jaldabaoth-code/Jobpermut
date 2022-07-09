<?php

namespace App\Controller;

use DateTime;
use LogicException;
use App\Entity\User;
use App\Entity\ChangePassword;
use App\Form\ChangePasswordType;
use Symfony\Component\Mime\Email;
use App\Form\RegistrationFormType;
use App\Security\AppAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Guard\GuardAuthenticatorHandler;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     */
    public function login(SessionInterface $session, AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // get the last route for redirection if login has false
        $lastRoute = $session->get('last_route');

        $session->set('error', $error ?
            'Email ou mot de passe incorrect.' :
            'Connection requise.');

        if (!$lastRoute) {
            return $this->redirectToRoute('home');
        }

        return $this->redirectToRoute($lastRoute['route'], $lastRoute['params']);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout(): void
    {
        throw new LogicException('This method can be blank -
        it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/register", name="app_register")
     */
    public function register(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        MailerInterface $mailer,
        GuardAuthenticatorHandler $guardHandler,
        AppAuthenticator $authenticator
    ): ?Response {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        } else {
            $user = new User();
            $form = $this->createForm(RegistrationFormType::class, $user);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $user->setPassword(
                    $passwordEncoder->encodePassword(
                        $user,
                        $form->get('plainPassword')->getData()
                    )
                );

                $user->setCreatedAt(new DateTime('now'));
                $user->setRoles('ROLE_USER');
                $entityManager = $this->getDoctrine()->getManager();
                $entityManager->persist($user);
                $entityManager->flush();

                $email = (new Email())
                ->from(strval($this->getParameter('mailer_from')))
                ->to($form->get('email')->getData())
                ->subject('Confirmation de votre inscription')
                ->html($this->renderView('mail/confirmationMail.html.twig', ['user' => $user]));

                $mailer->send($email);

                $this->addFlash(
                    'success',
                    'Votre inscription s\'est bien deroulée, un mail de confirmation vous sera envoyé'
                );

                return $guardHandler->authenticateUserAndHandleSuccess(
                    $user,
                    $request,
                    $authenticator,
                    'main' // firewall name in security.yaml
                );
            }
        }

        return $this->render('security/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    public function modalLogin(SessionInterface $session): Response
    {
        $error = '';

        if ($session->get('error')) {
            $error = $session->get('error');
            $session->remove('error');
        }

        return $this->render('includes/_login.html.twig', [
            'error' => $error
        ]);
    }

    /**
     * @Route("parametres/{username}/mot-de-passe", name="change_password")
     * @ParamConverter("user", class="App\Entity\User"),
     * options={"mapping": {"username": "username"}})
     */
    public function changePassword(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder,
        EntityManagerInterface $entityManager,
        User $user
    ): Response {

        $changePassword = new ChangePassword();

        $form = $this->createForm(ChangePasswordType::class, $changePassword);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user->setPassword(
                $passwordEncoder->encodePassword(
                    $user,
                    $form->get('newPassword')->getData()
                )
            );

            $entityManager->flush();

            $this->addFlash('success', 'Votre mot de passe a bien été modifié.');

            return $this->redirectToRoute('setting');
        }

        return $this->render('setting/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
