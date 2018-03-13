<?php
/**
 * Created by PhpStorm.
 * User: Andy
 * Date: 12/03/2018
 * Time: 21:33
 */

namespace AppBundle\Controller;


use AppBundle\Entity\Etudiant;
use AppBundle\Entity\Sport;
use AppBundle\Form\SportType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;


class SportController extends Controller
{
    /**
     * @Route("/add/{id}", name="addSport")
     * @return \Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */

    public function newAction(Etudiant $etudiant, Request $request)
    {
        $sport = new Sport();
        $form = $this->createForm(SportType::class, $sport,[
            'action' => $this->generateUrl('addSport',['id'=>$etudiant->getId()])
        ]);

        $form->handleRequest($request);
        if ( ! $form->isSubmitted() || ! $form->isValid()) {
            return $this->render('sport/new.html.twig', [
                'add_sport_form' => $form->createView(),
                'etudiant' => $etudiant,
            ]);
        }
        $sport->setEtudiant($etudiant);
        $em = $this->getDoctrine()->getManager();
        $em->persist($sport);
        $em->flush();

        $this->addFlash('notice','Youy create a new sport !');
        return $this->redirectToRoute('showEtudiant',['id'=>$etudiant->getId()]);
    }
}