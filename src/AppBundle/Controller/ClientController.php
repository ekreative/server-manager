<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use AppBundle\Form\ClientType;
use AppBundle\Form\FrameworkType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

/**
 * Client controller.
 *
 * @Route("/client")
 */
class ClientController extends Controller
{

    /**
     * Lists all Client entities.
     *
     * @param $request Request
     * @return string
     *
     * @Route("/", name="client")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();

        $clients = $em->getRepository(Client::class)->findAll();
        $entities = $this->get('knp_paginator')->paginate($clients, $request->query->getInt('page', 1), 100);

        return [
            'entities' => $entities,
        ];
    }

    /**
     * Creates a new Framework entity.
     *
     * @Route("/new", name="client_new")
     * @Method("GET|POST")
     * @Template()
     * @param Request $request
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function newAction(Request $request)
    {
        $entity = new Client();

        $form = $this->createForm(new ClientType(), $entity, [
            'method' => 'POST',
        ])
            ->add('submit', 'submit', ['label' => 'Create']);

        if ($request->getMethod() == "POST") {
            $form->handleRequest($request);

            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($entity);
                $em->flush();
                return $this->redirect($this->generateUrl('client_show', ['client' => $entity->getId()]));
            }
        }

        return [
            'entity' => $entity,
            'form' => $form->createView(),
        ];
    }

    /**
     * Finds and displays a Framework entity.
     *
     * @Route("/{client}", name="client_show")
     * @Method("GET")
     * @Template()
     *
     * @param Client $client
     * @return array
     */
    public function showAction(Client $client)
    {
        $deleteForm = $this->createDeleteForm($client->getId());

        return [
            'entity' => $client,
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Displays a form to edit an existing Framework entity.
     *
     * @Route("/{client}/edit", name="client_edit")
     * @Method("GET|PUT")
     * @Template()
     *
     * @param Request $request
     * @param Client $client
     * @return array|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function editAction(Request $request, Client $client)
    {
        $em = $this->getDoctrine()->getManager();

        $editForm = $this->createForm(new ClientType(), $client, [
            'action' => $this->generateUrl('client_edit', ['client' => $client->getId()]),
            'method' => 'PUT',
        ])
        ->add('submit', 'submit', ['label' => 'Update']);

        $deleteForm = $this->createDeleteForm($client->getId());

        if ($request->getMethod() == "PUT") {
            $editForm->handleRequest($request);
            if ($editForm->isValid()) {
                $em->flush();

                return $this->redirect($this->generateUrl('client_show', ['client' => $client->getId()]));
            }
        }

        return [
            'entity' => $client,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ];
    }

    /**
     * Deletes a Framework entity.
     *
     * @Route("/{client}", name="client_delete")
     * @Method("DELETE")
     *
     * @param Request $request
     * @param Client $client
     * @return \Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function deleteAction(Request $request, Client $client)
    {
        $form = $this->createDeleteForm($client->getId());
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();

            $em->remove($client);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('client'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('client_delete', ['client' => $id]))
            ->setMethod('DELETE')
            ->add('submit', 'submit', ['label' => 'Delete'])
            ->getForm();
    }
}
