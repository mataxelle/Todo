<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class DefaultController extends AbstractController
{    
    /**
     * Get homepage
     *
     * @return void
     */
    #[Route('/', name: 'homepage', methods: ['GET'])]
    public function indexAction()
    {
        return $this->render('default/index.html.twig');
    }
}
