<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    /**
     * @Route("{_locale}/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $translated = $this->get('translator')->trans('action.cancel');
        dump ($translated);
        return $this->render('default/index.html.twig');
    }
}
