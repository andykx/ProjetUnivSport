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


/**
 * @Route("{_locale}/event")
 */

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
     * @Route("/add", name="event_add")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */

    public function addAction(Request $request)
    {
        $event = new event();
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
     *
     * @Route("/edit/{id}", name="event_edit")
     *
     */
    public function editAction(Request $request, Event $event)
    {
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

}