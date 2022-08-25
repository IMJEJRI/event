<?php

namespace App\Controller;
//use, equivalent to the "require", represent all the classes which are used in the file.
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    // The route takes advantage of the name parameter.
    // In our code, we will have to use this name when we want to refer to it
    //    Create a route and a controller with the url /
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
