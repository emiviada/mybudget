<?php

namespace MyBudget\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use MyBudget\EntryBundle\Entity\Entry;
use MyBudget\BackendBundle\Form\EntryType;

/**
 * Entry controller.
 *
 */
class EntryController extends Controller
{
    /**
     * Lists all Entry entities.
     *
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('EntryBundle:Entry')->findAll();

        return $this->render('BackendBundle:Entry:index.html.twig', array(
            'entities' => $entities
        ));
    }

    /**
     * Finds and displays a Entry entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EntryBundle:Entry')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entry entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Entry:show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),

        ));
    }

    /**
     * Displays a form to create a new Entry entity.
     *
     */
    public function newAction()
    {
        $entity = new Entry();
        $entity->setDateEntry(new \DateTime());
        $form   = $this->createForm(new EntryType(), $entity);

        return $this->render('BackendBundle:Entry:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new Entry entity.
     *
     */
    public function createAction()
    {
        $entity  = new Entry();
        $request = $this->getRequest();
        $session = $this->get('session');
        $form    = $this->createForm(new EntryType(), $entity);
        $form->bind($request);

        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            $session->getFlashBag()->add('success', 'Movimiento creado exitosamente.');
            $session->getFlashBag()->add('add_another', true);

            return $this->redirect($this->generateUrl('entry_show', array('id' => $entity->getId())));           
        }
        else
        {
            $session->getFlashBag()->add('error', 'El formulario no se guardo. Hubo errores.');
        }

        return $this->render('BackendBundle:Entry:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Entry entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('EntryBundle:Entry')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entry entity.');
        }

        $editForm = $this->createForm(new EntryType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Entry:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Entry entity.
     *
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $session = $this->get('session');

        $entity = $em->getRepository('EntryBundle:Entry')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entry entity.');
        }

        $editForm   = $this->createForm(new EntryType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid())
        {
            $em->persist($entity);
            $em->flush();

            $session->getFlashBag()->add('success', 'Movimiento editado exitosamente.');

            return $this->redirect($this->generateUrl('entry_edit', array('id' => $id)));
        }
        else
        {
            $session->getFlashBag()->add('error', 'El formulario no se edito. Hubo errores.');
        }

        return $this->render('BackendBundle:Entry:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Entry entity.
     *
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('EntryBundle:Entry')->find($id);
        if ($entity)
        {
            $em->remove($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'El Movimiento fué borrado exitosamente.');
        }
        else
        {
            throw $this->createNotFoundException('Unable to find Entry entity.');
        }

        return $this->redirect($this->generateUrl('entry'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm();
    }
}
