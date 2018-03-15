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
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Event;
use AppBundle\Form\EventType;
use Symfony\Component\HttpFoundation\Response;



class EventController extends Controller
{
    /**
     * @Route("/index", name="event_index")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */

    public function indexAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Event::class);
        $events = $repository->findAll();
        dump($events);
        return $this->render('event/index.html.twig', [
            'events'=> $events
        ]);
    }

    /**
     * @Route("/delete/{id}", requirements={"id": "\d+"}, name="event_delete")
     */

    public function deleteAction(Event $event)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($event);
        $em->flush();

        return new Response('event supprimé');
    }



    public function newAction(Request $request)
    {
        $event = new Event();
        $form = $this->createForm(EventType::class, $event,[
            'action' => $this->generateUrl('addevent')
        ]);

        $form->handleRequest($request);
        if ( ! $form->isSubmitted() || ! $form->isValid()) {
            return $this->render('event/new.html.twig', [
                'add_event_form' => $form->createView(),
                'event' => $event,
            ]);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($event);
        $em->flush();

        return $this->redirectToRoute('event_index');
    }

    /**
     * @Route("/edit/{id}", requirements={"id": "\d+"}, name="editFilm")
     */
    /**
    public function updateAction(Film $film, Request $request)
    {
    $form = $this->createForm(FilmType::class, $film);
    $form->handleRequest($request);

    if (! $form->isSubmitted() || ! $form->isValid()) {
    return $this->render('film/edit.html.twig', [
    'film' => $film,
    'edit_film_form' => $form->createView(),
    ]);
    }

    $em = $this->getDoctrine()->getManager();
    $em->flush();
    return $this->redirectToRoute('film_index');
    }**/


    /**
     * @Route("/add", name="addEvent")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */

    public function addAction(Request $request)
    {
        $event = new Event();
        $form = $this->createForm(EventType::class,$event);
        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($event);
            $em->flush();

        }
        $formView = $form->createView();
        return $this->render('event/new.html.twig', array('form'=>$formView));
    }


    /**
     * @Route ("/list", name="event_list")
     */
    public function listAction(){
        $repository = $this->getDoctrine()->getRepository('AppBundle:event');
        $events = $repository->findAll();
        return $this->render('event/edit.html.twig',array('events'=>$events));
    }


    /**
     * @return Response
     *
     * @Route ("/edit/{id}", name="event_edit")
     */
    public function editAction(Request $request, Event $event){
        $form = $this->createForm(EventType::class,$event);
        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return new Response('event modifié');
        }
        $formView = $form->createView();
        return $this->render('event/new.html.twig', array('form'=>$formView));
    }
}