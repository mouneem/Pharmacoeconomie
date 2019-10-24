<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class JSONController extends AbstractController
{
    /**
     * @Route("/j/s/o/n", name="j_s_o_n")
     */
    public function index()
    {
        return $this->render('json/index.html.twig', [
            'controller_name' => 'JSONController',
        ]);
    }


    /**
     * @Route("/api/hello/{name}")
     */
    public function apiExample($name)
    {
        return $this->json([
            'name' => $name,
            'symfony' => 'rocks',
        ]);
    }
}
