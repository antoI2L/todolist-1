<?php

namespace TodoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use TodoBundle\Entity\Category;
use TodoBundle\Form\Type\CategoryType;

class CategoryController extends Controller
{
    /**
     * Permet de créer une catégorie
     *
     * @Route("/category/create", name="create_category")
     */
    public function createAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $category = new Category();

        $form = $this->createForm(CategoryType::class, $category);

        $form->handleRequest($request);

        if ($form->isValid()) {
            $em->persist($category);
            $em->flush();

            $this->addFlash(
                'notice',
                'Category added with success'
            );

            return $this->redirect('/');
        }

        return $this->render(
            'TodoBundle:Category:create.html.twig',
            array(
                'form' => $form->createView(),
            )
        );
    }

    /**
     * Permet de lister les catégories
     *
     * @Route("/category/list", name="list_category")
     */
    public function listAction()
    {
        $categories = $this->getDoctrine()->getRepository('TodoBundle:Category')->findAll();

        return $this->render(
            'TodoBundle:Category:list.html.twig',
            array(
                'categories' => $categories,
            )
        );
    }


    /**
     * Permet de supprimer une catégorie
     *
     * @Route("/category/delete/{category}" , name="remove_category")
     * @ParamConverter("category", class="TodoBundle:Category")
     */
    public function removeTask(Category $category)
    {
        $em = $this->getDoctrine()->getManager();

        if (0 === count($category->getTasks())) {
            $em->remove($category);
            $em->flush();

            $this->addFlash(
                'notice',
                'Category '.$category->getId().' removed with success'
            );
        } else {
            $this->addFlash(
                'notice',
                'Category '.$category->getId().' has tasks, it can\'t be removed.'
            );
        }

        return $this->redirect($this->generateUrl('list_category'));
    }
}
