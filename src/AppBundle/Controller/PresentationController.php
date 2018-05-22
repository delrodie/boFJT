<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Presentation;
use AppBundle\Utils\Utilities;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Presentation controller.
 *
 * @Route("backend/presentation")
 */
class PresentationController extends Controller
{
    /**
     * Lists all presentation entities.
     *
     * @Route("/", name="backend_presentation_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $presentations = $em->getRepository('AppBundle:Presentation')->findAll();

        return $this->render('presentation/index.html.twig', array(
            'presentations' => $presentations,
        ));
    }

    /**
     * Creates a new presentation entity.
     *
     * @Route("/new", name="backend_presentation_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Utilities $utilities)
    {
        $presentation = new Presentation();
        $form = $this->createForm('AppBundle\Form\PresentationType', $presentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $resume = $utilities->resume($presentation->getContenu(), 300, '...', true);
            $presentation->setResume($resume);
            $em->persist($presentation);
            $em->flush();

            return $this->redirectToRoute('backend_presentation_show', array('slug' => $presentation->getSlug()));
        }

        return $this->render('presentation/new.html.twig', array(
            'presentation' => $presentation,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a presentation entity.
     *
     * @Route("/{slug}", name="backend_presentation_show")
     * @Method("GET")
     */
    public function showAction(Presentation $presentation)
    {
        $deleteForm = $this->createDeleteForm($presentation);

        return $this->render('presentation/show.html.twig', array(
            'presentation' => $presentation,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing presentation entity.
     *
     * @Route("/{slug}/edit", name="backend_presentation_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Presentation $presentation, Utilities $utilities)
    {
        $deleteForm = $this->createDeleteForm($presentation);
        $editForm = $this->createForm('AppBundle\Form\PresentationType', $presentation);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $resume = $utilities->resume($presentation->getContenu(), 300, '...', true);
            $presentation->setResume($resume);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_presentation_show', array('slug' => $presentation->getSlug()));
        }

        return $this->render('presentation/edit.html.twig', array(
            'presentation' => $presentation,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a presentation entity.
     *
     * @Route("/{id}", name="backend_presentation_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Presentation $presentation)
    {
        $form = $this->createDeleteForm($presentation);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($presentation);
            $em->flush();
        }

        return $this->redirectToRoute('backend_presentation_index');
    }

    /**
     * Creates a form to delete a presentation entity.
     *
     * @param Presentation $presentation The presentation entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Presentation $presentation)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('backend_presentation_delete', array('id' => $presentation->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
