<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Subscription;
use App\Entity\RegisteredUser;
use App\Form\SubscriptionType;
use App\Repository\CompanyRepository;
use App\Repository\RegisteredUserRepository;
use App\Service\ApiRome\ApiRome;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/subscription", name="subscription_")
 */
class SubscriptionController extends AbstractController
{
    /**
     * @Route("/new", name="new")
     */
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        CompanyRepository $companyRepository,
        ApiRome $apiRome
    ): Response {

        /** @var User */
        $user = $this->getUser();

        /** @var RegisteredUser */
        $registeredUser = $user->getRegisteredUser();

        if ($registeredUser->getId()) {
            $subscription = $registeredUser->getSubscription();
        } else {
            return $this->redirectToRoute('profile_edit', ['username' => $user->getUsername(), 'premium' => true]);
        }

        if ($subscription) {
            return $this->redirectToRoute(
                'subscription_edit',
                ['subscription' => $subscription->getId()]
            );
        } else {
            $subscription = new Subscription();
        }

        $rome = $registeredUser->getId() ? $registeredUser->getRome() : null;

        $form = $this->createForm(SubscriptionType::class, $subscription, ['rome' => $rome]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($subscription->getCompanyCode()) {
                $subscription->setCompany($companyRepository->findOneBy(['code' => $subscription->getCompanyCode()]));
            }

            $ogr = $subscription->getOgrCode();

            if ($ogr && $ogr !== null) {
                $ogrName = $apiRome->getDetailsOfAppellation(strval($ogr))['libelleCourt'];
                $subscription->setOgrName($ogrName);
            }

            $subscription->setSubscriptionAt(new DateTimeImmutable());
            $entityManager->persist($subscription);
            $registeredUser->setSubscription($subscription);

            $entityManager->flush();
            return $this->redirectToRoute('profile_show', ['username' => $user->getUsername()]);
        }

        return $this->render('subscription/index.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/{subscription}/edit", name="edit")
     */
    public function edit(
        Subscription $subscription,
        RegisteredUserRepository $registeredRepository,
        Request $request,
        EntityManagerInterface $entityManager,
        CompanyRepository $companyRepository,
        ApiRome $apiRome
    ): Response {

        /** @var User */
        $user = $this->getUser();

        /** @var RegisteredUser */
        $registeredUser = $registeredRepository->findOneBy([
            'subscription' => $subscription
        ]);

        $rome = $registeredUser->getRome();

        $form = $this->createForm(SubscriptionType::class, $subscription, ['rome' => $rome]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            if ($subscription->getCompanyCode()) {
                $subscription->setCompany($companyRepository->findOneBy(['code' => $subscription->getCompanyCode()]));
            }

            $ogr = $subscription->getOgrCode();

            if (strval($ogr) !== $subscription->getOgrCode()) {
                if ($ogr !== null) {
                    $ogrName = $apiRome->getDetailsOfAppellation(strval($ogr))['libelleCourt'];
                    $subscription->setOgrName($ogrName);
                } else {
                    $subscription->setOgrName(null);
                }
            }

            $subscription->setUpdatedAt(new DateTimeImmutable());
            $entityManager->flush();

            return $this->redirectToRoute('profile_show', ['username' => $user->getUsername()]);
        }

        return $this->render('subscription/index.html.twig', [
            'form' => $form->createView(),
            'edit' => 'Modifier'
        ]);
    }
}
