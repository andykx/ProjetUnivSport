<?php
/**
 * Created by PhpStorm.
 * User: Andy
 * Date: 13/03/2018
 * Time: 17:56
 */

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Evenement;
use AppBundle\Entity\Utilisateur;
use AppBundle\Form\UtilisateurType;
use AppBundle\Form\EvenementType;

class UtilisateurController extends Controller
{
    /**
     * @Route("/add/{id}", name="addEvenement")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */

    public function newAction(Evenement $evenement, Request $request)
    {
        $utilisateur = new utilisateur();
        $form = $this->createForm(UtilisateurType::class, $utilisateur,[
            'action' => $this->generateUrl('addEvenement',['id'=>$utilisateur->getId()])
        ]);

        $form->handleRequest($request);
        if ( ! $form->isSubmitted() || ! $form->isValid()) {
            return $this->render('evenement/new.html.twig', [
                'add_event_form' => $form->createView(),
                'utilisateur' => $utilisateur,
            ]);
        }
        $evenement->setEtudiant($evenement);
        $em = $this->getDoctrine()->getManager();
        $em->persist($evenement);
        $em->flush();

        $this->addFlash('notice','Youy create a new event !');
        return $this->redirectToRoute('showUtilisateur',['id'=>$utilisateur->getId()]);
    }
}