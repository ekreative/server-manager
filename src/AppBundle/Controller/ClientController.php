<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Client;
use AppBundle\Form\ClientType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * Client controller.
 *
 * @Route("/client")
 */
class ClientController extends Controller
{

    /**
     * @Route("/", name="client")
     * @Method("GET")
     * @Template()
     */
    public function indexAction(Request $request)
    {
        $clients = $this->getDoctrine()->getRepository(Client::class)->findAll();
        $entities = $this->get('knp_paginator')->paginate($clients, $request->query->getInt('page', 1), 100);

        return [
            'entities' => $entities,
        ];
    }

    /**
     * Creates a new Client entity.
     *
     * @Route("/new", name="client_new")
     * @Method("GET|POST")
     * @Template()
     */
    public function newAction(Request $request)
    {
        $entity = new Client();

        $form = $this->createForm(ClientType::class, $entity, [
            'method' => 'POST',
        ])
            ->add('submit', SubmitType::class, ['label' => 'Create']);

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
     * Finds and displays a Client entity.
     *
     * @Route("/{client}", name="client_show")
     * @Method("GET")
     * @Template()
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
     * Displays a form to edit an existing Client entity.
     *
     * @Route("/{client}/edit", name="client_edit")
     * @Method("GET|PUT")
     * @Template()
     */
    public function editAction(Request $request, Client $client)
    {
        $em = $this->getDoctrine()->getManager();

        $editForm = $this->createForm(ClientType::class, $client, [
            'action' => $this->generateUrl('client_edit', ['client' => $client->getId()]),
            'method' => 'PUT',
        ])
        ->add('submit', SubmitType::class, ['label' => 'Update']);

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
            ->add('submit', SubmitType::class, ['label' => 'Delete'])
            ->getForm();
    }
}
