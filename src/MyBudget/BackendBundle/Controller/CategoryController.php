<?php

namespace MyBudget\BackendBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Session\Session;
use MyBudget\CategoryBundle\Entity\Category;
use MyBudget\BackendBundle\Form\CategoryType;
use MyBudget\BackendBundle\Util\Paginator;

/**
 * Category controller.
 *
 */
class CategoryController extends Controller
{
    /**
     * Lists all Category entities.
     *
     */
    public function indexAction($page = 1)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $request = $this->get('request');
        $items_per_list = $this->container->getParameter('items_per_list');

        //Sorting
        list($orderBy, $sortMode, $sortModeReverse) = $this->sorting();
        $iconClass = ($sortMode == 'ASC')? "icon-chevron-up" : "icon-chevron-down";

        $all = $em->getRepository('CategoryBundle:Category')->findAll();

        $paginator = Paginator::getInfo(count($all), $items_per_list, $page, 'category_page');

        $entities = $em->getRepository('CategoryBundle:Category')->findBy(
            array(), //Criteria (Filtering)
            array($orderBy => $sortMode), //OrderBy (Sortering)
            $paginator['per_page'],
            $paginator['offset']
        );

        return $this->render('BackendBundle:Category:index.html.twig', array(
            'entities' => $entities,
            'paginator' => $paginator,
            'orderBy' => $orderBy,
            'sort_mode_reverse' => $sortModeReverse,
            'icon_class' => $iconClass
        ));
    }

    /**
     * Filter Category list
     */
    public function filterAction($field, $mode)
    {
        $session = $this->get('session');

        $sort = array(
            'category' => array(
                'field' => $field,
                'mode' => $mode
            )
        );

        $session->set('sort', json_encode($sort));

        return $this->redirect($this->generateUrl('category'));
    }

    /**
     * sorting method
     */
    public function sorting()
    {
        $session = $this->get('session');

        $orderBy = "id";
        $sortMode = "ASC";
        $sortModeReverse = "DESC";
        if ($session->has('sort')) {
            $sort = json_decode($session->get('sort'), true);
            $orderBy = $sort['category']['field'];
            $sortMode = $sort['category']['mode'];
            $sortModeReverse = ($sortMode == "ASC")? "DESC" : "ASC";
        }

        return array($orderBy, $sortMode, $sortModeReverse);
    }

    /**
     * Finds and displays a Category entity.
     *
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CategoryBundle:Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        return $this->render('BackendBundle:Category:show.html.twig', array(
            'entity'      => $entity,
        ));
    }

    /**
     * Displays a form to create a new Category entity.
     *
     */
    public function newAction()
    {
        $entity = new Category();
        $form   = $this->createForm(new CategoryType(), $entity);

        return $this->render('BackendBundle:Category:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Creates a new Category entity.
     *
     */
    public function createAction()
    {
        $entity  = new Category();
        $request = $this->getRequest();
        $session = $this->get('session');
        $form    = $this->createForm(new CategoryType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid())
        {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();

            $session->getFlashBag()->add('success', 'Categoría creada exitosamente.');
            $session->getFlashBag()->add('add_another', true);

            return $this->redirect($this->generateUrl('category_show', array('id' => $entity->getId())));            
        }
        else
        {
            $session->getFlashBag()->add('error', 'El formulario no se guardo. Hubo errores.');
        }

        return $this->render('BackendBundle:Category:new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView()
        ));
    }

    /**
     * Displays a form to edit an existing Category entity.
     *
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('CategoryBundle:Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $editForm = $this->createForm(new CategoryType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('BackendBundle:Category:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing Category entity.
     *
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $session = $this->get('session');

        $entity = $em->getRepository('CategoryBundle:Category')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        $editForm   = $this->createForm(new CategoryType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid())
        {
            $em->persist($entity);
            $em->flush();

            $session->getFlashBag()->add('success', 'Categoría editada exitosamente.');

            return $this->redirect($this->generateUrl('category_edit', array('id' => $id)));
        }
        else
        {
            $session->getFlashBag()->add('error', 'El formulario no se edito. Hubo errores.');
        }

        return $this->render('BackendBundle:Category:edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a Category entity.
     *
     */
    public function deleteAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository('CategoryBundle:Category')->find($id);
        if ($entity)
        {
            $em->remove($entity);
            $em->flush();

            $this->get('session')->getFlashBag()->add('success', 'La Categoría fué borrada exitosamente.');
        }
        else
        {
            throw $this->createNotFoundException('Unable to find Category entity.');
        }

        return $this->redirect($this->generateUrl('category'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
