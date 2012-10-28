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
        $form    = $this->createForm(new EntryType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('entry_show', array('id' => $entity->getId())));
            
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

        $entity = $em->getRepository('EntryBundle:Entry')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Entry entity.');
        }

        $editForm   = $this->createForm(new EntryType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('entry_edit', array('id' => $id)));
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
