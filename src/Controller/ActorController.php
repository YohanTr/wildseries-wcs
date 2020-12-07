<?php
// src/Controller/ActorController.php
namespace App\Controller;

use App\Entity\Actor;
use App\Entity\Program;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("actor", name="actor_")
 */
class ActorController extends AbstractController
{
    /**
     * @Route("/{id}", name="show", methods={"GET"})
     * @return Response A response instance
     */
    public function show(Actor $actor): Response
    {
        return $this->render('actor/show.html.twig',
        [
            'actor' => $actor
        ]);
    }
}