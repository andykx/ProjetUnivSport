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
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Home",$this->get("router")->generate("homepage"));
        return $this->render('default/index.html.twig');
    }

    /**
     *
     * @Route("/locale/{locale}", name="setlocale")
     *
     */
    public function setLocaleAction(Request $request, $locale = "null")
    {
        if ($locale != null) {
            $request->getSession()->set('{_locale', $locale);
        }
        return $this->redirectToRoute('homepage');
    }

    /**
     * @Route("/rss", name="getFluxRss")
     */
    public function rssAction(Request $request)
    {

        $domaine = 'http://UnivSport.fr/';
        $em = $this->getDoctrine()->getManager();

        // recuperation d'uniquement ceux à venir
        $events = $this->getDoctrine()
            ->getRepository('AppBundle:Event')
            ->findAllGreaterThanDate(date('Y-m-d H:i:s'));

        $locale =  $request->getLocale();

        $feed = new Feed();

        $channel = new Channel();
        $channel
            ->title('Evenements')
            ->description('Liste des évènements à venir')
            ->url('http://UnivSport.fr')
            ->feedUrl('http://UnivSport.fr/rss')
            ->copyright('Copyright 2018, UnivSport.fr')
            ->pubDate(strtotime(date('Y-m-d H:i:s')))
            ->lastBuildDate(strtotime(date('Y-m-d H:i:s')))
            ->appendTo($feed);

        foreach ($events as $event) {
            $auteur = $event->getUser();
            $item = new Item();
            $item
                ->titre($event->getTitre())
                ->description('<div>'.$event->getDescription().'</div>')
                ->url($domaine.$locale.'/event/'.$event->getId())
                ->pubDate($event->getDate()->getTimeStamp())
                ->appendTo($channel);
        }
        $response = new Response($feed);
        $response->headers->set('Content-Type', 'xml');
        return $response;
    }

    /**
     * Change the locale for the current user
     *
     * @Route("/appelTheme", name="appelTheme")
     *
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
            ->add('Ok', SubmitType::class)
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // data is an array with "name", "email", and "message" keys
            $data = $form->getData();
            $data = implode(',' , $data);

            $this->get('session')->set('theme',$data);
        }

        return $this->render('event/theme.html.twig', [
            'form'=>$form->createView()

        ]);

    }

}
