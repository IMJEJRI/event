<?php

namespace App\Controller;
//use, equivalent to the "require", represent all the classes which are used in the file.
use App\Entity\Kind;
use App\Form\KindType;
use App\Repository\KindRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class KindController extends AbstractController
{
   //kind listing
    #[Route('/kinds', name: 'listing')]
    public function kind(KindRepository $kindRepository)
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            //if you are not in admin role, you are redirected to the listing page with a message that appears
            $this->addFlash('warning', 'Vous n\'etes pas un administrateur');
            return $this->redirectToRoute('event_listing');
        }
        //Get all kinds in DB
        $kinds = $kindRepository->findAll();
        return  $this->render('kind/kind.html.twig', [
            'kinds' => $kinds
        ]);
    }
 //Add kind
    #[Route('/KindNew/new', name: 'Kind_new')]
    public function KindNew(Request $request, EntityManagerInterface $entityManager)
    {
        // Creates a new instance of a genre, which we will pass to the form
        $Kind = new Kind();
        // Create the form using KindType, which is the form template.
        // It contains the list of fields to generate
        $form = $this->createForm(KindType::class, $Kind);

        // Processes the request to check if the form data is submitted
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            // Retrieves data from the form
            $KindToSave = $form->getData();
            // persist allows to prepare the queries (SQL) to be executed in DB
            $entityManager->persist($KindToSave);
            // flush: Executes all previously prepared SQL queries
            $entityManager->flush();

            // Adds an ephemeral message to warn of the status of the request
            $this->addFlash('success', 'Votre genre à été créé avec succès.');

            return $this->redirectToRoute('listing');
        }

        return $this->render('kind/KindNew.html.twig', [
            'KindForm' => $form->createView()
        ]);
    }


    //Delete kind
    #[Route('/kinds/{id}/delete', name: 'kind_delete')]
    public function kindDelete(KindRepository $kindRepository, EntityManagerInterface $entityManager, $id)
    {
        // Retrieves a kind in DB, the one with the id specified in the URL
        $kind = $kindRepository->findOneBy(['id' => $id]);
        if (!$kind) {
            //if the varible is empty,  redirects to the page listing
            return $this->redirectToRoute('listing');
        }
        // Prepare the query to remove the kind from the DB
        $entityManager->remove($kind);
        // executes all previously prepared SQL queries
        $entityManager->flush();
        // Adds an ephemeral message to warn of the status of the request
        $this->addFlash('success', 'Votre genre a été supprimé.');
        return $this->redirectToRoute('listing');
    }

  //Edit Kind
    #[Route('/Kind/{id}/edit', name: 'Kind_edit')]
    public function KindEdit($id, KindRepository $KindRepository, Request $request, EntityManagerInterface $entityManager)
    {
        // Retrieves a Kind in DB, the one with the id specified in the URL
        $Kind = $KindRepository->findOneBy([
            'id' => $id
        ]);

        if (!$Kind) {
            $this->addFlash('warning', 'Aucun genre trouvé.');
            return $this->redirectToRoute('listing');
        }

        $form = $this->createForm(KindType::class, $Kind);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()){
            $KindToSave = $form->getData();
            $entityManager->persist($KindToSave);
            $entityManager->flush();

            $this->addFlash('success', 'Votre genre a été modifié avec succès.');

            return $this->redirectToRoute('listing');
        }

        return $this->render('kind/KindEdit.html.twig', [
            'KindForm' => $form->createView()
        ]);
    }

}