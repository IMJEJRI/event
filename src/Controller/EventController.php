<?php

namespace App\Controller;

//use, equivalent to the "require", represent all the classes which are used in the file.
use App\Entity\Event;
use App\Form\EventSearchFormType;
use App\Form\EventType;
use App\Repository\EventRepository;
use App\Repository\KindRepository;
use ContainerBTzhWdN\getEventRepositoryService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class EventController extends AbstractController

{
    // The route takes advantage of the name parameter.
    // In our code, we will have to use this name when we want to refer to it
    //    Create a route and a controller with the url /events
    #[Route('/events', name: 'event_listing')]
    //Use a custom method of the Repository to retrieve events
    public function events(EventRepository $eventRepository)
    {
        //Get all events in DB
        $events = $eventRepository->findAllOrderByDate();
        //use the file events.html.twig in the template for display
        return $this->render('event/events.html.twig', [
            'events' => $events
        ]);
    }

// Add new event
    #[Route('/events/new', name: 'event_new')]
    public function eventNew(Request $request, EntityManagerInterface $entityManager)
    {
        // Creates a new instance of a event, which will be passed to the form
        $event = new Event();

        $user = $this->getUser();
        if (!$user) {
            //if the variable contains nothing, we will redirect to the page event_listing
            return $this->redirectToRoute('event_listing');
        }
        // Creates the form using AuthorType, which is the form template.
        // It contains the list of fields to generate
        $form = $this->createForm(EventType::class, $event);
        //Processes the request to check if the form data is submitted
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Retrieves the form data
            $event = $form->getData();

            $event->setUser($user);
            // persist allows to prepare the queries (SQL) to be executed in DB
            $entityManager->persist($event);
            // Executes all previously prepared SQL queries
            $entityManager->flush();

            return $this->redirectToRoute('event_listing');
        }

        return $this->render('event/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

//    Edit event
    #[Route('/events/{id}/edit', name: 'event_edit')]
    public function eventEdit(Request $request, EntityManagerInterface $entityManager, EventRepository $eventRepository, $id)
    {
        // Retrieves a event in DB, the one with the id specified in the URL
        $event = $eventRepository->findOneBy(['id' => $id]);

        if (!$event) {
            //if the content is empty, we redirect to the page event_listing
            return $this->redirectToRoute('event_listing');
        }

        $user = $this->getUser();

        if (!$user) {
            return $this->redirectToRoute('event_listing');
        }
        // Checks if the user has ROLE_ADMIN
        if ($user !== $event->getUser() && !$this->isGranted('ROLE_ADMIN')) {
            return $this->redirectToRoute('event_listing');
        }

        $form = $this->createForm(EventType::class, $event);
        // //Process the query to check if the form data is submitted
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Retrieves the form data
            $event = $form->getData();
            $event->setUser($user);
         // persist allows to prepare the queries (SQL) to be executed in DB
            $entityManager->persist($event);
            // executes all previously prepared SQL queries
            $entityManager->flush();
           // Adds an ephemeral message to warn of the status of the request
            $this->addFlash('success', 'Votre event à été créé avec succès.');
            return $this->redirectToRoute('event_listing');
        }
        return $this->render('event/edit.html.twig', [
            'form' => $form->createView()
        ]);
    }

//    Delete event
    #[Route('/events/{id}/delete', name: 'event_delete')]
    public function eventDelete(Request $request, EntityManagerInterface $entityManager, EventRepository $eventRepository, $id)
    {
        // Retrieves a genre in DB, the one with the id specified in the URL
        $event = $eventRepository->findOneBy(['id' => $id]);
        // Prepare the query to remove the event from the DB
        $entityManager->remove($event);
        $entityManager->flush();
        // Adds an ephemeral message to warn of the status of the request
        $this->addFlash('success', 'Votre èvenement a été supprimé.');

        return $this->redirectToRoute('event_listing');

    }

//    Own events published by user
    #[Route('/my_events', name: 'my_events')]
    public function myEvents(EventRepository $eventRepository)
    {
        $user =$this->getUser();

        $events = $eventRepository->findBy(['user' => $user]);

        return $this->render('event/events.html.twig', [
            'events' => $events
        ]);
    }

    //Event detail
    // create a route and a controller with the url /events/{id}
    #[Route('/events/{id}', name: 'event_detail')]
    public function eventDetail ($id, EventRepository $eventRepository){
        // Use a custom method of the repository to retrieve the events with all the desired joins
        $event = $eventRepository->findOneEventByIdWithKind($id);
         //        dd($event);

        if (!$event) {
            // Adds an ephemeral message to warn of the status of the request
            $this->addFlash('warning', 'Aucun event trouvé.');
            return $this->redirectToRoute('event_listing');
        }

        return $this->render('event/events.html.twig', [
            'event' => $event,

        ]);
    }
    //   Code for the homepage
    /**
     * @Route("/", name="Accueil")
     */
    public function Accueil(EventRepository $eventRepository){

        return $this->render('event/index.html.twig', [
            'events' => $eventRepository ->findAll()
        ]);
    }

//    Code pour la page partenaire
    #[Route('/event/contact', name: 'Partenaire')]
    public function Contact(){
        return $this->render('event/contact.html.twig');
    }

}
