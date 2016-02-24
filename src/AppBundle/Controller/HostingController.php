<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Hosting;
use AppBundle\Form\HostingType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Hosting controller.
 *
 * @Route("/hosting")
 */
class HostingController extends Controller
{

    /**
     * Lists all Hosting entities.
     *
     * @param $request Request
     * @return string
     *
     * @Route("/", name="hosting")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $query = $em->getRepository('AppBundle:Hosting')->createQueryBuilder('h')->getQuery();
        $entities = $this->get('knp_paginator')->paginate($query, $request->query->getInt('page', 1), 100);

        return [
            'entities' => $entities,
        ];
    }

    /**
     * Creates a new Hosting entity.
     *
     * @Route("/", name="hosting_create")
     * @Method("POST")
     * @Template("AppBundle:Hosting:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Hosting();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('hosting_show', ['id' => $entity->getId()]));
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Creates a form to create a Hosting entity.
     *
     * @param Hosting $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Hosting $entity)
    {
        $form = $this->createForm(new HostingType(), $entity, [
            'action' => $this->generateUrl('hosting_create'),
            'method' => 'POST',
        ]);

        $form->add('submit', 'submit', ['label' => 'Create']);

        return $form;
    }

    /**
     * Displays a form to create a new Hosting entity.
     *
     * @Route("/new", name="hosting_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Hosting();
        $form = $this->createCreateForm($entity);

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Hosting entity.
     *
     * @Route("/{id}", name="hosting_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Hosting')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Hosting entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return [
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Hosting entity.
     *
     * @Route("/{id}/edit", name="hosting_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Hosting')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Hosting entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    private function createEditForm(Hosting $entity)
    {
        $form = $this->createForm(new HostingType(), $entity, [
            'action' => $this->generateUrl('hosting_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        $form->add('submit', 'submit', ['label' => 'Update']);

        return $form;
    }

    /**
     * Edits an existing Hosting entity.
     *
     * @Route("/{id}", name="hosting_update")
     * @Method("PUT")
     * @Template("AppBundle:Hosting:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Hosting')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Hosting entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('hosting_show', ['id' => $id]));
        }

        return [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a Hosting entity.
     *
     * @Route("/{id}", name="hosting_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Hosting')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Hosting entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('hosting'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('hosting_delete', ['id' => $id]))
            ->setMethod('DELETE')
            ->add('submit', 'submit', ['label' => 'Delete'])
            ->getForm();
    }
}
