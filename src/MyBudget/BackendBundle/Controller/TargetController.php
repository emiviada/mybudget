<?php

namespace MyBudget\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use MyBudget\BackendBundle\Entity\Target;
use MyBudget\BackendBundle\Form\TargetType;

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
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('BackendBundle:Target')->findAll();

        return $this->render('BackendBundle:Target:index.html.twig', array(
            'entities' => $entities
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
        $form    = $this->createForm(new TargetType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            $this->get('session')->setFlash('success', 'Objetivo creado exitosamente.');
            $this->get('session')->setFlash('add_another', true);

            return $this->redirect($this->generateUrl('target_show', array('id' => $entity->getId())));           
        }
        else
        {
            $this->get('session')->setFlash('error', 'El formulario no se guardo. Hubo errores.');
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

            $this->get('session')->setFlash('success', 'Objetivo editado exitosamente.');

            return $this->redirect($this->generateUrl('target_edit', array('id' => $id)));
        }
        else
        {
            $this->get('session')->setFlash('error', 'El formulario no se edito. Hubo errores.');
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

            $this->get('session')->setFlash('success', 'El Objetivo fué borrado exitosamente.');
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
