<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Form\AdminType;
use Symfony\Component\Mime\Email;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * @Route("/admin", name="admin_")
 */
class AdminController extends AbstractController
{
    /**
     * @Route("/", name="index")
     */
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/creation_compte", name="create_admin")
     * @IsGranted("ROLE_SUPERADMIN")
     */
    public function add(
        Request $request,
        UserPasswordEncoderInterface $passwordEncoder
    ): ?Response {
        $admin = new User();
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $admin->setPassword(
                $passwordEncoder->encodePassword(
                    $admin,
                    $form->get('plainPassword')->getData()
                )
            );

            $admin->setCreatedAt(new DateTime('now'));
            $admin->setRoles('ROLE_ADMIN');
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($admin);
            $entityManager->flush();

            $this->addFlash(
                'success',
                'L\'inscription s\'est déroulée avec succès, 
                votre administrateur peut maintenant se connecter avec son compte'
            );
        }

        return $this->render('admin/add.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
