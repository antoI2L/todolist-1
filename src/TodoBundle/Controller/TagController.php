<?php

namespace TodoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TodoBundle\Entity\Tag;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use TodoBundle\Form\Type\TagType;

class TagController extends Controller
{
    /**
     * Permet de créer un tag
     *
     * @Route("/tag/create", name="create_tag")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $tag = new Tag();

        $form = $this->createForm(TagType::class, $tag);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($tag);
            $em->flush();

            $this->addFlash(
                'notice',
                'Tag added with success'
            );

            return $this->redirect('/');
        }

        return $this->render(
            'TodoBundle:Tag:create.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Permet de lister les tags
     *
     * @Route("/tag/list", name="list_tag")
     */
    public function listAction()
    {
        $tags = $this->getDoctrine()->getRepository('TodoBundle:Tag')->findAll();

        return $this->render(
            'TodoBundle:Tag:list.html.twig',
            array(
                'tags' => $tags,
            )
        );
    }
}
