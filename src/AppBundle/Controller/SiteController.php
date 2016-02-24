<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Site;
use AppBundle\Form\SiteType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;

/**
 * Site controller.
 *
 * @Route("/site")
 */
class SiteController extends Controller
{

    /**
     * Lists all Site entities.
     *
     * @param Request $request
     *
     * @Route("/", name="site")
     * @Method("GET")
     * @Template()
     *
     * @return array
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $name = $request->query->get('name');
        $framework = $request->query->get('framework');

        if ($name || $framework) {
            $query = $em->getRepository('AppBundle:Site')->searchQuery($name, $framework);
        } else {
            $query = $em->getRepository('AppBundle:Site')->createQueryBuilder('s')->getQuery();
        }

        $entities = $this->get('knp_paginator')->paginate($query, $request->query->getInt('page', 1), 100);
        $form = $this->createSearchForm(['name' => $name, 'framework' => $framework]);

        return [
            'entities' => $entities,
            'form' => $form->createView(),
        ];
    }

    /**
     * Creates a form to search a Site entity.
     *
     * @param array $data Data from request
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createSearchForm(array $data = [])
    {
        if ($data['framework']) {
            $data['framework'] = $this->getDoctrine()->getManager()->getRepository('AppBundle:Framework')->find($data['framework']);
        }
        return $this->get('form.factory')->createNamedBuilder(null, 'form', $data, [
            'method' => Request::METHOD_GET,
            'csrf_protection' => false
        ])
            ->add('name', null, [
                'attr' => ['placeholder' => 'Name'],
                'required' => false,
            ])
            ->add('framework', 'entity', [
                'class' => 'AppBundle\Entity\Framework',
                'required' => false,
                'empty_value' => '-Select-',
            ])
            ->add('submit', 'submit')
            ->getForm();

    }

    /**
     * Creates a new Site entity.
     *
     * @Route("/", name="site_create")
     * @Method("POST")
     * @Template("AppBundle:Site:new.html.twig")
     */
    public function createAction(Request $request)
    {
        $entity = new Site();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('site_show', ['id' => $entity->getId()]));
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Creates a form to create a Site entity.
     *
     * @param Site $entity The entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createCreateForm(Site $entity)
    {
        $form = $this->createForm(new SiteType(), $entity, [
            'action' => $this->generateUrl('site_create'),
            'method' => 'POST',
        ]);

        $form->add('submit', 'submit', ['label' => 'Create']);

        return $form;
    }

    /**
     * Displays a form to create a new Site entity.
     *
     * @Route("/new", name="site_new")
     * @Method("GET")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Site();
        $form = $this->createCreateForm($entity);

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Site entity.
     *
     * @Route("/{id}", name="site_show")
     * @Method("GET")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Site')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Site entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return [
            'entity' => $entity,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Site entity.
     *
     * @Route("/{id}/edit", name="site_edit")
     * @Method("GET")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Site')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Site entity.');
        }

        $editForm = $this->createEditForm($entity);
        $deleteForm = $this->createDeleteForm($id);

        return [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    private function createEditForm(Site $entity)
    {
        $form = $this->createForm(new SiteType(), $entity, [
            'action' => $this->generateUrl('site_update', ['id' => $entity->getId()]),
            'method' => 'PUT',
        ]);

        $form->add('submit', 'submit', ['label' => 'Update']);

        return $form;
    }

    /**
     * Edits an existing Site entity.
     *
     * @Route("/{id}", name="site_update")
     * @Method("PUT")
     * @Template("AppBundle:Site:edit.html.twig")
     */
    public function updateAction(Request $request, $id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('AppBundle:Site')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Site entity.');
        }

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createEditForm($entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->flush();

            return $this->redirect($this->generateUrl('site_show', ['id' => $id]));
        }

        return [
            'entity' => $entity,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a Site entity.
     *
     * @Route("/{id}", name="site_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('AppBundle:Site')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Site entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('site'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('site_delete', ['id' => $id]))
            ->setMethod('DELETE')
            ->add('submit', 'submit', ['label' => 'Delete'])
            ->getForm();
    }
}
