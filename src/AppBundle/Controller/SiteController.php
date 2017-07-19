<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Site;
use AppBundle\Entity\Client;
use AppBundle\Form\ModelTransformer\SitesFilter;
use AppBundle\Form\SitesFilterType;
use AppBundle\Form\SiteType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Acl\Exception\Exception;

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
        $filter = new SitesFilter();

        if (!$this->get('security.authorization_checker')->isGranted('ROLE_REDMINE_ADMIN')) {
            // get all projects current user from RedMine
            $redmineClientService = $this->container->get('redmine_client');
            $uri = '/users/' . $this->getUser()->getId() . '.json?include=memberships';
            $result = \GuzzleHttp\json_decode($redmineClientService->get($uri)->getBody(), true);
            $listMemberships = array_shift($result)['memberships'];
            if (!empty($listMemberships)) {
                foreach ($listMemberships as $membership) {
                    $filter->addProject($membership['project']['id']);
                }
            }
        }

        $form = $this->createForm(SitesFilterType::class, $filter)
            ->add('Search', SubmitType::class);
        $form->handleRequest($request);
        $query = $em->getRepository('AppBundle:Site')->searchQuery($filter);
        $entities = $this->get('knp_paginator')->paginate($query, $request->query->getInt('page', 1), 100);

        return [
            'entities' => $entities,
            'form' => $form->createView(),
        ];
    }

    /**
     * Creates a new Site entity.
     *
     * @Route("/", name="site_create")
     * @Method("POST")
     * @Template("AppBundle:Site:new.html.twig")
     *
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function createAction(Request $request)
    {
        $entity = new Site();
        $form = $this->createCreateForm($entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            if ($form->get('client')->getData() == '') {
                /**
                 * @var Client $client
                 */
                $client = $form->get('newClient')->getData();
                if ($client->getFullName() && $client->getEmail()) {
                    $em->persist($client);
                }
            } else {
                $client = $em->getRepository(Client::class)->find($form->get('client')->getData());
            }

            $entity->getProject()->setClient($client);
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
        $clients = $this->getDoctrine()
            ->getRepository(Client::class)
            ->createQueryBuilder('p')
            ->getQuery()
            ->getResult();

        $form = $this->createForm(new SiteType(), $entity, [
            'action' => $this->generateUrl('site_create'),
            'method' => 'POST',
            'clients' => $clients
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
