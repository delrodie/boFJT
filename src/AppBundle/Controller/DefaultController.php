<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $sliders = $em->getRepository('AppBundle:Slider')->findBy(array('statut' => 1), array('id' => 'DESC'), 3, 0);
        $bienvenues = $em->getRepository('AppBundle:Presentation')->findPresentation($slug = 'president', 1, 0);
        $presentations = $em->getRepository('AppBundle:Presentation')->findPresentation($slug = 'somme', 1, 0);
        $missions = $em->getRepository('AppBundle:Plan')->findPlan($rubrique = 'mission', 1, 0);
        $treichenjoies = $em->getRepository('AppBundle:Treichenjoie')->findBy(array('statut' => 1), array('titre' => 'ASC'), 4, 0);
        $actualites = $em->getRepository('AppBundle:Actualite')->findBy(array('statut' => 1), array('id' => 'DESC'), 3, 0);

        //dump($missions);die();
        return $this->render('default/index.html.twig',[
            'sliders'   => $sliders,
            'bienvenues' => $bienvenues,
            'presentations' => $presentations,
            'missions' => $missions,
            'treichenjoies' => $treichenjoies,
            'actualites' => $actualites,
        ]);
    }

    /**
     * @Route("/backend", name="backend")
     */
    public function dashboard()
    {
        return $this->render('default/dashboard.html.twig');
    }

    /**
     * Menu de la rubrique fondation
     *
     * @Route("/menu-fondation/", name="frontend_menu_fondation")
     */
    public function menufondationAction()
    {
        $em = $this->getDoctrine()->getManager();
        $menus = $em->getRepository('AppBundle:Presentation')->findBy(array('statut'=>1));
        return $this->render('default/menu_fondation.html.twig', [
            'menus' => $menus,
        ]);
    }

    /**
     * Menu de la rubrique fondation
     *
     * @Route("/menu-plan/", name="frontend_menu_plan")
     */
    public function menuplanAction()
    {
        $em = $this->getDoctrine()->getManager();
        $menus = $em->getRepository('AppBundle:Plan')->findBy(array('statut'=>1));
        return $this->render('default/menu_plan.html.twig', [
            'menus' => $menus,
        ]);
    }

    /**
     * Menu de la rubrique fondation
     *
     * @Route("/menu-treichenjoie/", name="frontend_menu_treichenjoie")
     */
    public function menutreichenjoieAction()
    {
        $em = $this->getDoctrine()->getManager();
        $menus = $em->getRepository('AppBundle:Treichenjoie')->findBy(array('statut'=>1));
        return $this->render('default/menu.html.twig', [
            'menus' => $menus,
        ]);
    }
}
