<?php

namespace MyBudget\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use MyBudget\BackendBundle\Entity\Target;
use MyBudget\BackendBundle\Form\TargetType;
use MyBudget\BackendBundle\Util\Paginator;

/**
 * Target controller.
 *
 */
class TargetController extends Controller
{
    /**
     * Lists all Target entities.
     *
     */
    public function indexAction($page = 1)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $request = $this->get('request');
        $items_per_list = $this->container->getParameter('items_per_list');

        $all = $em->getRepository('BackendBundle:Target')->findAll();

        $paginator = Paginator::getInfo(count($all), $items_per_list, $page, 'target_page');

        $entities = $em->getRepository('BackendBundle:Target')->findBy(
            array(), //Criteria (Filtering)
            array(), //OrderBy (Sortering)
            $paginator['per_page'],
            $paginator['offset']
        );

        return $this->render('BackendBundle:Target:index.html.twig', array(
            'entities' => $entities,
            'paginator' => $paginator
        ));
    }

    /**
     * Finds and displays a Target entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BackendBundle:Target')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entry entity.');
        }

        return $this->render('BackendBundle:Target:show.html.twig', array(
            'entity'      => $entity,
        ));
    }

    /**
     * Displays a form to create a new Target entity.
     *
     */
    public function newAction()
    {
        $entity = new Target();
        $entity->setMonth(new \DateTime());
        $form   = $this->createForm(new TargetType(), $entity);

        return $this->render('BackendBundle:Target:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new Target entity.
     *
     */
    public function createAction()
    {
        $entity  = new Target();
        $request = $this->getRequest();
        $session = $this->get('session');
        $form    = $this->createForm(new TargetType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            $session->getFlashBag()->add('success', 'Objetivo creado exitosamente.');
            $session->getFlashBag()->add('add_another', true);

            return $this->redirect($this->generateUrl('target_show', array('id' => $entity->getId())));           
        }
        else
        {
            $session->getFlashBag()->add('error', 'El formulario no se guardo. Hubo errores.');
        }

        return $this->render('BackendBundle:Target:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Target entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('BackendBundle:Target')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entry entity.');
        }

        $editForm = $this->createForm(new TargetType(), $entity);

        return $this->render('BackendBundle:Target:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
     * Edits an existing Target entity.
     *
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $session = $this->get('session');

        $entity = $em->getRepository('BackendBundle:Target')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entry entity.');
        }

        $editForm   = $this->createForm(new TargetType(), $entity);

        $request = $this->getRequest();
        $editForm->bindRequest($request);

        if ($editForm->isValid())
        {
            $em->persist($entity);
            $em->flush();

            $session->getFlashBag()->add('success', 'Objetivo editado exitosamente.');

            return $this->redirect($this->generateUrl('target_edit', array('id' => $id)));
        }
        else
        {
            $session->getFlashBag()->add('error', 'El formulario no se edito. Hubo errores.');
        }

        return $this->render('BackendBundle:Target:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
        ));
    }

    /**
     * Deletes a Target entity.
     *
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('BackendBundle:Target')->find($id);
        if ($entity)
        {
            $em->remove($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'El Objetivo fué borrado exitosamente.');
        }
        else
        {
            throw $this->createNotFoundException('Unable to find Entry entity.');
        }

        return $this->redirect($this->generateUrl('target'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm();
    }
}
