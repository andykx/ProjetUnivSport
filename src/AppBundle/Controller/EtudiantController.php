<?php
/**
 * Created by PhpStorm.
 * User: Andy
 * Date: 12/03/2018
 * Time: 21:42
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Etudiant;
use AppBundle\Form\EtudiantType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;


class EtudiantController extends Controller
{
    /**
     * @Route("{_locale}/index", name="etudiant_index")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */

    public function indexAction(Request $request)
    {
        $repository = $this->getDoctrine()->getRepository(Etudiant::class);
        $etudiants = $repository->findAll();
        dump($etudiants);
        return $this->render('etudiant/index.html.twig', [
            'etudiants'=> $etudiants
        ]);
    }

    /**
     * @Route("/delete/{id}", requirements={"id": "\d+"}, name="etudiant_delete")
     */

    public function deleteAction(etudiant $etudiant)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($etudiant);
        $em->flush();

        return new Response('Etudiant supprimé');
    }



    public function newAction(Request $request)
    {
        $etudiant = new etudiant();
        $form = $this->createForm(EtudiantType::class, $etudiant,[
            'action' => $this->generateUrl('addEtudiant')
        ]);

        $form->handleRequest($request);
        if ( ! $form->isSubmitted() || ! $form->isValid()) {
            return $this->render('etudiant/new.html.twig', [
                'add_etudiant_form' => $form->createView(),
                'etudiant' => $etudiant,
            ]);
        }
        $em = $this->getDoctrine()->getManager();
        $em->persist($etudiant);
        $em->flush();

        return $this->redirectToRoute('etudiant_index');
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
        $etudiant = new etudiant();
        $form = $this->createForm(EtudiantType::class,$etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            $em = $this->getDoctrine()->getManager();
            $em->persist($etudiant);
            $em->flush();
            return new response('etudiant ajouté');
        }
        $formView = $form->createView();
        return $this->render('etudiant/new.html.twig', array('form'=>$formView));
    }


    /**
     * @Route ("{_locale}/list", name="etudiants_list")
     */
    public function listAction(){
        $repository = $this->getDoctrine()->getRepository('AppBundle:Etudiant');
        $etudiants = $repository->findAll();
        return $this->render('etudiant/edit.html.twig',array('etudiants'=>$etudiants));
    }


    /**
     * @return Response
     *
     * @Route ("/edit/{id}", name="etudiant_edit")
     */
    public function editAction(Request $request, Etudiant $etudiant){
        $form = $this->createForm(EtudiantType::class,$etudiant);
        $form->handleRequest($request);

        if ($form->isSubmitted())
        {
            $em = $this->getDoctrine()->getManager();
            //$em->persist($etudiant);
            $em->flush();
            return new Response('etudiant modifié');
        }
        $formView = $form->createView();
        return $this->render('etudiant/new.html.twig', array('form'=>$formView));
    }
}