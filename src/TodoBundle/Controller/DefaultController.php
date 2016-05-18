<?php

namespace TodoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;

class DefaultController extends Controller
{
    /**
     * Affiche la page d'accueil
     *
     * @Route("/", name="homepage")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $tasks = $this
            ->getDoctrine()
            ->getRepository('TodoBundle:Task')
            ->getOutdatedTasks(
                $this->getUser()
            );

        return $this->render(
            'TodoBundle:Default:index.html.twig',
            array(
                'tasks' => $tasks,
            )
        );
    }
}
