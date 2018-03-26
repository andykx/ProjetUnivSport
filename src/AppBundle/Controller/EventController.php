<?php
/**
 * Created by PhpStorm.
 * User: Andy
 * Date: 13/03/2018
 * Time: 18:03
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Event;
use AppBundle\Form\EventType;
use Symfony\Component\HttpFoundation\Response;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Cache;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\EventDispatcher\GenericEvent;
use Symfony\Component\HttpFoundation\JsonResponse;
use Suin\RSSWriter\Feed;
use Suin\RSSWriter\Channel;
use Suin\RSSWriter\Item;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


/**
 * @Route("/auth/{_locale}/event")
 */

class EventController extends Controller
{
    /**
     * @Route("/", name="event_index")
     * @return \Symfony\Component\HttpFoundation\Response
     * @Method("GET")
     */

    public function indexAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Event::class);
        $events = $repository->findAll();
        dump($events);
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Home",$this->get("router")->generate("homepage"));
        $breadcrumbs->addItem("Liste des évènements",$this->get("router")->generate("event_index"));

        $em = $this->getDoctrine()->getManager();

        $listeEvents = $em->getRepository('AppBundle:Event')->findAll();
        $events  = $this->get('knp_paginator')->paginate(
            $listeEvents,
            $request->query->get('page', 1)/*le numéro de la page à afficher*/,
            4/*nbre d'éléments par page*/
        );

        return $this->render('event/index.html.twig', [
            'events'=> $events
        ]);
    }

    /**
     * @Route("/rss.xml", name="event_rss")
     */
    public function rssAction(Request $request)
    {
        $domaine = 'http://UnivSport.fr/';
        $repository = $this->getDoctrine()->getRepository(Event::class);
        $events = $repository->findAll();

        $locale =  $request->getLocale();

        $feed = new Feed();

        $channel = new Channel();
        $channel
            ->title('Evenements')
            ->description('Liste des évènements à venir')
            ->url('http://UnivSport.fr')
            ->feedUrl('http://UnivSport.fr/rss')
            ->copyright('Copyright 2018, UnivSport')
            ->pubDate(strtotime(date('Y-m-d H:i:s')))
            ->lastBuildDate(strtotime(date('Y-m-d H:i:s')))
            ->appendTo($feed);

        foreach ($events as $event) {
            $item = new Item();
            $item
                ->title($event->getTitre())
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
     * @Route("/add", name="event_add")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */

    public function addAction(Request $request)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Home",$this->get("router")->generate("homepage"));
        $breadcrumbs->addItem("Ajouter un évènement",$this->get("router")->generate("event_add"));

        $event = new Event();
        $user = $this->getUser();
        $form = $this->createForm(EventType::class, $event,[
            'action'=>$this->generateUrl('event_add')
        ]);
        $form->handleRequest($request);
        if (!$form->isSubmitted() || ! $form->isValid()){

            return $this->render('event/new.html.twig',[
                'form'=> $form->createView(),

            ]);
        }
        $event->setUser($user);
        $event->setNbPlacesDispo($event->getNbInscrits());
        $em = $this->getDoctrine()->getManager();
        $em->persist($event);
        $em->flush();

        $this->addFlash('notice', 'Event posté');

        return $this->redirectToRoute('event_index');
    }

    /**
     * @Route("/edit/{id}", name="event_edit")
     */
    public function editAction(Request $request, Event $event)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Home",$this->get("router")->generate("homepage"));
        $breadcrumbs->addItem("Liste des évènements",$this->get("router")->generate("event_index"));
        $breadcrumbs->addItem("Editer évènement");

        $deleteForm = $this->createDeleteForm($event);
        $editForm = $this->createForm('AppBundle\Form\EventType', $event);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('event_edit', array('id' => $event->getId()));
        }
        return $this->render('event/edit.html.twig', array(
            'event' => $event,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     *
     * @param Event $event The event entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(Event $event)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('event_delete', array('id' => $event->getId())))
            ->setMethod('DELETE')
            ->getForm();
    }

    /**
     * @Route("/delete/{id}", requirements={"id": "\d+"}, name="event_delete")
     */

    public function deleteAction(event $event)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($event);
        $em->flush();

        return $this->redirectToRoute('event_index');
    }

    /**
     * @Route ("/show/{id}",requirements ={"id": "\d+"}, name="event_show")
     */

    public function showAction(Event $event){

        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Home",$this->get("router")->generate("homepage"));
        $breadcrumbs->addItem("Liste des évènements",$this->get("router")->generate("event_index"));
        $breadcrumbs->addItem("Voir évènement");

        return $this->render('event/show.html.twig',[
            'event'=> $event
        ]);

    }

    /**
     * @Route("/result", name="search_results")
     */

    public function resultsAction(Request $request)
    {
        $em = $this->getDoctrine()->getManager();
        $search = array();
        $search['lieu']= $request->query->get('lieu');
        $search['categorie']= $request->query->get('categorie');

        $resultats = $em->getRepository(Event::class)->findEvent($search);
        // Ici on utilise une requête créée dans le repository
        $event = $this->get('knp_paginator')->paginate($resultats,
            $request->query->get('page',1),4);

        return $this->render('search/result.html.twig', [
            'Event'=>$event
        ]);
    }

    /**
     * @Route("/search", name="search")
     */
    public function searchAction(Request $request)
    {
        $breadcrumbs = $this->get("white_october_breadcrumbs");
        $breadcrumbs->addItem("Home",$this->get("router")->generate("homepage"));
        $breadcrumbs->addItem("Rechercher un évènement",$this->get("router")->generate("search"));

        return $this->render('search/search.html.twig');
    }
}