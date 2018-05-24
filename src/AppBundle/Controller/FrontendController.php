<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class FrontendController extends Controller
{
    /**
     * Details article fondation
     *
     * @Route("/fondation/{slug}", name="frontend_fondation")
     */
    public function fondationAction($slug)
    {
        $em = $this->getDoctrine()->getManager();
        $presentation = $em->getRepository('AppBundle:Presentation')->findOneBy(array('slug' => $slug));
        //dump($presentation);die();

        return $this->render("frontend/presentation.html.twig",[
            'presentation'  => $presentation,
        ]);
    }
}