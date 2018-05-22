<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Actualite;
use AppBundle\Utils\Utilities;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Actualite controller.
 *
 * @Route("backend/actualite")
 */
class ActualiteController extends Controller
{
    /**
     * Lists all actualite entities.
     *
     * @Route("/", name="backend_actualite_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $actualites = $em->getRepository('AppBundle:Actualite')->findAll();

        return $this->render('actualite/index.html.twig', array(
            'actualites' => $actualites,
        ));
    }

    /**
     * Creates a new actualite entity.
     *
     * @Route("/new", name="backend_actualite_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Utilities $utilities)
    {
        $actualite = new Actualite();
        $form = $this->createForm('AppBundle\Form\ActualiteType', $actualite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $resume = $utilities->resume($actualite->getContenu(), 300, '...', true);
            $actualite->setResume($resume);
            $em->persist($actualite);
            $em->flush();

            return $this->redirectToRoute('backend_actualite_show', array('slug' => $actualite->getSlug()));
        }

        return $this->render('actualite/new.html.twig', array(
            'actualite' => $actualite,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a actualite entity.
     *
     * @Route("/{slug}", name="backend_actualite_show")
     * @Method("GET")
     */
    public function showAction(Actualite $actualite)
    {
        $deleteForm = $this->createDeleteForm($actualite);

        return $this->render('actualite/show.html.twig', array(
            'actualite' => $actualite,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing actualite entity.
     *
     * @Route("/{slug}/edit", name="backend_actualite_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Actualite $actualite, Utilities $utilities)
    {
        $deleteForm = $this->createDeleteForm($actualite);
        $editForm = $this->createForm('AppBundle\Form\ActualiteType', $actualite);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $resume = $utilities->resume($actualite->getContenu(), 300, '...', true);
            $actualite->setResume($resume);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_actualite_show', array('slug' => $actualite->getSlug()));
        }

        return $this->render('actualite/edit.html.twig', array(
            'actualite' => $actualite,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a actualite entity.
     *
     * @Route("/{id}", name="backend_actualite_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Actualite $actualite)
    {
        $form = $this->createDeleteForm($actualite);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($actualite);
            $em->flush();
        }

        return $this->redirectToRoute('backend_actualite_index');
    }

    /**
     * Creates a form to delete a actualite entity.
     *
     * @param Actualite $actualite The actualite entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Actualite $actualite)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('backend_actualite_delete', array('id' => $actualite->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
