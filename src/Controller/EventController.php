<?php

namespace App\Controller;

// Les use, équivalents aux "require", représentent toutes les classes qui sont utilisées
// dans le fichier.
use App\Entity\Event;
use App\Form\EventSearchFormType;
use App\Form\EventType;
use App\Repository\EventRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController
{
    #[Route('/events', name: 'event_listing')]
    public function events(EventRepository $eventRepository)
    {
        $events = $eventRepository->findAll();
        return $this->render('event/events.html.twig', [
            'events' => $events
        ]);
    }

    /**
     * @Route("/", name="Accueil")
     */
    public function Accueil(){
           return $this->render('event/index.html.twig');
    }

    /**
     * @Route("/Contact", name="Contact")
     */
    public function Contact(){
        return $this->render('event/contact.html.twig');
    }


    #[Route('/events/new', name: 'event_new')]
    public function eventNew(Request $request, EntityManagerInterface $entityManager)
    {
        $event = new Event();

        $user = $this->getUser();
        if (!$user) {
            return $this->redirectToRoute('event_listing');
        }

        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = $form->getData();

            $event->setUser($user);

            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('event_listing');
        }

        return $this->render('event/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/events/{id}/edit', name: 'event_edit')]
    public function eventEdit(Request $request, EntityManagerInterface $entityManager, EventRepository $eventRepository, $id)
    {
        $event = $eventRepository->findOneBy(['id' => $id]);

        if (!$event) {
            return $this->redirectToRoute('event_listing');
        }

        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('event_listing');
        }

        if ($user !== $event->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('event_listing');
        }

        $form = $this->createForm(EventType::class, $event);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $event = $form->getData();

            $event->setUser($user);

            $entityManager->persist($event);
            $entityManager->flush();

            return $this->redirectToRoute('event_listing');
        }

        return $this->render('event/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

    #[Route('/events/{id}/delete', name: 'event_delete')]
    public function eventDelete(Request $request, EntityManagerInterface $entityManager, EventRepository $eventRepository, $id)
    {
        $event = $eventRepository->findOneBy(['id' => $id]);

        $entityManager->remove($event);
        $entityManager->flush();

        $this->addFlash('success', 'Votre èvenement a été supprimé.');

        return $this->redirectToRoute('event_listing');

    }

    #[Route('/my_events', name: 'my_events')]
    public function myEvents(EventRepository $eventRepository)
    {
        $user =$this->getUser();

        $events = $eventRepository->findBy(['user' => $user]);

        return $this->render('event/events.html.twig', [
            'events' => $events
        ]);
    }
}
