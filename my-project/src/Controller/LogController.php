<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Log;

class LogController extends AbstractController
{
    #[Route('/log', name: 'app_log')]
    public function index(ManagerRegistry $doctrine): Response
    {
        $em = $doctrine->getManager();
        $logs = $em->getRepository(Log::class)->findAll();
        return $this->render('log/index.html.twig', [
            'logs' => $logs,
        ]);
    }
}

   
