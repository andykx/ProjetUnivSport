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
use AppBundle\Entity\Utilisateur;
use AppBundle\Entity\Evenement;
use AppBundle\Form\UtilisateurType;
use Symfony\Component\HttpFoundation\Response;



class EvenementController extends Controller
{
    /**
     * @Route("{_locale}/index", name="utilisateur_index")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */

    public function indexAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Evenement::class);
        $evenements = $repository->findAll();
        dump($evenements);
        return $this->render('evenement/index.html.twig', [
            'evenements'=> $evenements
        ]);
    }

    /**
     * @Route("/delete/{id}", requirements={"id": "\d+"}, name="utilisateur_delete")
     */

    public function deleteAction(utilisateur $utilisateur)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($utilisateur);
        $em->flush();

        return new Response('utilisateur supprimé');
    }



    public function newAction(Request $request)
    {
        $evenement = new Evenement();
        $form = $this->createForm(EvenementType::class, $evenement,[
            'action' => $this->generateUrl('addEvenement')
        ]);

        $form->handleRequest($request);
        if ( ! $form->isSubmitted() || ! $form->isValid()) {
            return $this->render('evenement/new.html.twig', [
                'add_evenement_form' => $form->createView(),
                'evenement' => $evenement,
            ]);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($evenement);
        $em->flush();

        return $this->redirectToRoute('evenement_index');
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
     * @Route("{_locale}/add", name="addFilm")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */

    public function addAction(Request $request)
    {
        $evenement = new evenement();
        $form = $this->createForm(EvenementType::class,$evenement);
        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($evenement);
            $em->flush();

        }
        $formView = $form->createView();
        return $this->render('evenement/new.html.twig', array('form'=>$formView));
    }


    /**
     * @Route ("{_locale}/list", name="utilisateurs_list")
     */
    public function listAction(){
        $repository = $this->getDoctrine()->getRepository('AppBundle:utilisateur');
        $utilisateurs = $repository->findAll();
        return $this->render('utilisateur/edit.html.twig',array('utilisateurs'=>$utilisateurs));
    }


    /**
     * @return Response
     *
     * @Route ("/edit/{id}", name="utilisateur_edit")
     */
    public function editAction(Request $request, utilisateur $utilisateur){
        $form = $this->createForm(utilisateurType::class,$utilisateur);
        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            $em = $this->getDoctrine()->getManager();
            //$em->persist($utilisateur);
            $em->flush();
            return new Response('utilisateur modifié');
        }
        $formView = $form->createView();
        return $this->render('utilisateur/new.html.twig', array('form'=>$formView));
    }
}