<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Event;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Item;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class DefaultController extends Controller
{
    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        $theme = 'bootstrap';
        if ($this->get('session')->get('theme') == null ){
            $this->get('session')->set('theme',$theme);
        }

        $translated = $this->get('translator')->trans('action.cancel');
        dump ($translated);

        // insertion du fil d'ariane
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Home",$this->get("router")->generate("homepage"));
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/auth/{_locale}/appelTheme", name="appelTheme")
     */
    public function themeAction(Request $request)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Home",$this->get("router")->generate("homepage"));
        $breadcrumbs->addItem("Sélection d'un thème",$this->get("router")->generate("appelTheme"));

        $defaultData = array('theme' => 'default');
        $form = $this->createFormBuilder($defaultData)
            ->add('theme', ChoiceType::class, array(
                'choices'=>array(
                    'Lux' => 'lux',
                    'Materia' => 'materia',
                    'Cerulean' => 'cerulean',
                    'Sketchy' => 'sketchy',
                    'Bootstrap' => 'bootstrap'
                )
            ))
            ->add('Valider', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $data = $form->getData();
            $data = implode(',' , $data);

            $this->get('session')->set('theme',$data);
        }

        return $this->render('event/theme.html.twig', [
            'form'=>$form->createView()

        ]);
    }
}
