<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Plan;
use AppBundle\Utils\Utilities;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;use Symfony\Component\HttpFoundation\Request;

/**
 * Plan controller.
 *
 * @Route("backend/plan")
 */
class PlanController extends Controller
{
    /**
     * Lists all plan entities.
     *
     * @Route("/", name="backend_plan_index")
     * @Method("GET")
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getManager();

        $plans = $em->getRepository('AppBundle:Plan')->findAll();

        return $this->render('plan/index.html.twig', array(
            'plans' => $plans,
        ));
    }

    /**
     * Creates a new plan entity.
     *
     * @Route("/new", name="backend_plan_new")
     * @Method({"GET", "POST"})
     */
    public function newAction(Request $request, Utilities $utilities)
    {
        $plan = new Plan();
        $form = $this->createForm('AppBundle\Form\PlanType', $plan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $resume = $utilities->resume($plan->getContenu(), 300, '...', true);
            $plan->setResume($resume);
            $em->persist($plan);
            $em->flush();

            return $this->redirectToRoute('backend_plan_show', array('slug' => $plan->getSlug()));
        }

        return $this->render('plan/new.html.twig', array(
            'plan' => $plan,
            'form' => $form->createView(),
        ));
    }

    /**
     * Finds and displays a plan entity.
     *
     * @Route("/{slug}", name="backend_plan_show")
     * @Method("GET")
     */
    public function showAction(Plan $plan)
    {
        $deleteForm = $this->createDeleteForm($plan);

        return $this->render('plan/show.html.twig', array(
            'plan' => $plan,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing plan entity.
     *
     * @Route("/{slug}/edit", name="backend_plan_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, Plan $plan, Utilities $utilities)
    {
        $deleteForm = $this->createDeleteForm($plan);
        $editForm = $this->createForm('AppBundle\Form\PlanType', $plan);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $resume = $utilities->resume($plan->getContenu(), 300, '...', true);
            $plan->setResume($resume);
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('backend_plan_show', array('slug' => $plan->getSlug()));
        }

        return $this->render('plan/edit.html.twig', array(
            'plan' => $plan,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a plan entity.
     *
     * @Route("/{id}", name="backend_plan_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, Plan $plan)
    {
        $form = $this->createDeleteForm($plan);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($plan);
            $em->flush();
        }

        return $this->redirectToRoute('backend_plan_index');
    }

    /**
     * Creates a form to delete a plan entity.
     *
     * @param Plan $plan The plan entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Plan $plan)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('backend_plan_delete', array('id' => $plan->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
