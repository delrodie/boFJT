<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Treichenjoie;
use AppBundle\Utils\Utilities;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Treichenjoie controller.
 *
 * @Route("backend/treichenjoie")
 */
class TreichenjoieController extends Controller
{
    /**
     * Lists all treichenjoie entities.
     *
     * @Route("/", name="backend_treichenjoie_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $treichenjoies = $em->getRepository('AppBundle:Treichenjoie')->findAll();

        return $this->render('treichenjoie/index.html.twig', array(
            'treichenjoies' => $treichenjoies,
        ));
    }

    /**
     * Creates a new treichenjoie entity.
     *
     * @Route("/new", name="backend_treichenjoie_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Utilities $utilities)
    {
        $treichenjoie = new Treichenjoie();
        $form = $this->createForm('AppBundle\Form\TreichenjoieType', $treichenjoie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $resume = $utilities->resume($treichenjoie->getContenu(), 300, '...', true);
            $treichenjoie->setResume($resume);
            $em->persist($treichenjoie);
            $em->flush();

            return $this->redirectToRoute('backend_treichenjoie_show', array('slug' => $treichenjoie->getSlug()));
        }

        return $this->render('treichenjoie/new.html.twig', array(
            'treichenjoie' => $treichenjoie,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a treichenjoie entity.
     *
     * @Route("/{slug}", name="backend_treichenjoie_show")
     * @Method("GET")
     */
    public function showAction(Treichenjoie $treichenjoie)
    {
        $deleteForm = $this->createDeleteForm($treichenjoie);

        return $this->render('treichenjoie/show.html.twig', array(
            'treichenjoie' => $treichenjoie,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing treichenjoie entity.
     *
     * @Route("/{slug}/edit", name="backend_treichenjoie_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Treichenjoie $treichenjoie, Utilities $utilities)
    {
        $deleteForm = $this->createDeleteForm($treichenjoie);
        $editForm = $this->createForm('AppBundle\Form\TreichenjoieType', $treichenjoie);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $resume = $utilities->resume($treichenjoie->getContenu(), 300, '...', true);
            $treichenjoie->setResume($resume);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_treichenjoie_show', array('slug' => $treichenjoie->getSlug()));
        }

        return $this->render('treichenjoie/edit.html.twig', array(
            'treichenjoie' => $treichenjoie,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a treichenjoie entity.
     *
     * @Route("/{id}", name="backend_treichenjoie_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Treichenjoie $treichenjoie)
    {
        $form = $this->createDeleteForm($treichenjoie);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($treichenjoie);
            $em->flush();
        }

        return $this->redirectToRoute('backend_treichenjoie_index');
    }

    /**
     * Creates a form to delete a treichenjoie entity.
     *
     * @param Treichenjoie $treichenjoie The treichenjoie entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Treichenjoie $treichenjoie)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('backend_treichenjoie_delete', array('id' => $treichenjoie->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
