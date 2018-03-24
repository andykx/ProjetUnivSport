<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use FOS\UserBundle\Model\User as FosUser;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * User
 *
 * @ORM\Table(name="user")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\UserRepository")
 */
class User extends FosUser
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    public function __construct()
    {
        parent::__construct();
        $this->events = new arrayCollection();
        $this->inscriptions = new ArrayCollection();
    }

    /**
     *@ORM\OneToMany(targetEntity="AppBundle\Entity\Event", mappedBy="user")
     */

    private $events;

    /**
     *@ORM\OneToMany(targetEntity="AppBundle\Entity\Inscription", mappedBy="user")
     */

    private $inscriptions;


    public function addEvent(Event $event = null){
        $this->events [] = $event;
    }
    public function getEvent(){
        return $this->events;
    }

    public function addInscription(Inscription $inscription){
        $this->inscriptions [] = $inscription;
    }

    public function getInscription(){
        return $this->inscriptions;
    }

}
