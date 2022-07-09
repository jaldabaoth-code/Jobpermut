<?php

namespace App\Controller;

use DateTime;
use App\Entity\User;
use App\Entity\Testimony;
use App\Form\TestimonyType;
use App\Repository\TestimonyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("admin/temoignage", name="testimony_")
 * @IsGranted("ROLE_ADMIN")
 */
class TestimonyController extends AbstractController
{
    /**
     * @Route("/", name="index", methods={"GET"})
     */
    public function index(TestimonyRepository $testimonyRepository): Response
    {
        return $this->render('testimony/index.html.twig', [
            'testimonies' => $testimonyRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $testimony = new Testimony();
        $form = $this->createForm(TestimonyType::class, $testimony);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $testimony->setCreatedAt(new DateTime());
            $entityManager->persist($testimony);
            $entityManager->flush();

            return $this->redirectToRoute('testimony_index');
        }

        return $this->render('testimony/new.html.twig', [
            'testimony' => $testimony,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="show", methods={"GET"})
     */
    public function show(Testimony $testimony): Response
    {
        return $this->render('testimony/show.html.twig', [
            'testimony' => $testimony,
        ]);
    }

    /**
     * @Route("/{id}", name="delete", methods={"POST"})
     */
    public function delete(Request $request, Testimony $testimony): Response
    {
        if ($this->isCsrfTokenValid('delete' . $testimony->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($testimony);
            $entityManager->flush();
        }

        return $this->redirectToRoute('testimony_index');
    }
}
