<?php
/**
 * Created by PhpStorm.
 * User: Andy
 * Date: 23/03/2018
 * Time: 13:31
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Inscription;
use AppBundle\Entity\Event;


/**
 *@Route ("/{_locale}/inscrip")
 */

class InscriptionController extends Controller
{

    /**
     * @Route("/reserv/{id}",name="reserv_index")
     * @return Symfony\Component\HttpFoundation\Response
     * @throws \LogicException
     */
    public function resAction(Event $event, Request $request){

        $exist= false;
        $user= $this->getUser();
        $inscriptions = $event->getInscription();
        $placedispo = $event->getNbPlacesDispo();

        if ($placedispo == 0 ){
            return $this->redirectToRoute('event_show',['id'=>$event->getId()]);
        }

        else{
            $i=0;
            while ($exist==false and $i< count($inscriptions)){
                $userReserv = $inscriptions[$i]->getUser();
                if ($userReserv->getId()==$user->getId()){
                    $exist= true;
                }
                $i++;
            }

            if($exist == false){

                $inscrip= new Inscription();
                $placedispo = $placedispo - 1;
                $event->setNbPlacesDispo($placedispo);
                $inscrip->setEvent($event);
                $inscrip->setUser($user);
                $event->addInscription($inscrip);
                $em = $this->getDoctrine()->getManager();
                $em->persist($inscrip);
                $em->flush();
                return $this->redirectToRoute('event_show',['id'=>$event->getId()]);
            }
        }
    }
}