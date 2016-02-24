<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Framework;
use AppBundle\Form\FrameworkType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Framework controller.
 *
 * @Route("/framework")
 */
class FrameworkController extends Controller
{

    /**
     * Lists all Framework entities.
     *
     * @param $request Request
     * @return string
     *
     * @Route("/", name="framework")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('AppBundle:Framework')->createQueryBuilder('h')->getQuery();
        $entities = $this->get('knp_paginator')->paginate($query, $request->query->getInt('page', 1), 100);

        return [
            'entities' => $entities,
        ];
    }

    /**
     * Creates a new Framework entity.
     *
     * @Route("/", name="framework_create")
     * @Method("POST")
     * @Template("AppBundle:Framework:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Framework();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('framework_show', ['id' => $entity->getId()]));
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Creates a form to create a Framework entity.
     *
     * @param Framework $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Framework $entity)
    {
        $form = $this->createForm(new FrameworkType(), $entity, [
            'action' => $this->generateUrl('framework_create'),
            'method' => 'POST',
        ]);

        $form->add('submit', 'submit', ['label' => 'Create']);

        return $form;
    }

    /**
     * Displays a form to create a new Framework entity.
     *
     * @Route("/new", name="framework_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Framework();
        $form = $this->createCreateForm($entity);

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Framework entity.
     *
     * @Route("/{id}", name="framework_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Framework')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Framework entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return [
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Framework entity.
     *
     * @Route("/{id}/edit", name="framework_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Framework')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Framework entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    private function createEditForm(Framework $entity)
    {
        $form = $this->createForm(new FrameworkType(), $entity, [
            'action' => $this->generateUrl('framework_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        $form->add('submit', 'submit', ['label' => 'Update']);

        return $form;
    }

    /**
     * Edits an existing Framework entity.
     *
     * @Route("/{id}", name="framework_update")
     * @Method("PUT")
     * @Template("AppBundle:Framework:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Framework')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Framework entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('framework_show', ['id' => $id]));
        }

        return [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a Framework entity.
     *
     * @Route("/{id}", name="framework_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Framework')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Framework entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('framework'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('framework_delete', ['id' => $id]))
            ->setMethod('DELETE')
            ->add('submit', 'submit', ['label' => 'Delete'])
            ->getForm();
    }
}
