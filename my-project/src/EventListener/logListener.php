<?php

namespace App\EventListener;

use Symfony\Component\HttpKernel\Event\ControllerEvent ;
use App\Controller\UserController;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use App\Entity\Log;

class logListener 
{
    private $em;

    public function __construct(EntityManager $em) {
        $this->em = $em;
    }
    public function onKernelController(ControllerEvent $event) {
        $request = $event->getRequest();
        $controller = $event->getController();
        if (is_array($controller)) {
            $controller = $controller[0];
        }
        if($controller instanceof UserController){
            $log = new Log();
            $log->setRoute($request->attributes->get('_route'));
            $log->setIp($request->server->get('REMOTE_ADDR'));
            $log->setDate(new \DateTime('@'.strtotime('now')));
            $this->em->persist($log);
            $this->em->flush();
        }
    }
}