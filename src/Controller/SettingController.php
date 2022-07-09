<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/parametres")
 */
class SettingController extends AbstractController
{
    /**
     * @Route("/", name="setting", methods={"GET","POST"})
     */
    public function setting(Request $request): Response
    {
        $form = $this->createForm(UserType::class, $this->getUser());
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', 'Votre profil a bien été modifié.');

            return $this->redirectToRoute('setting');
        }

        return $this->render('setting/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
