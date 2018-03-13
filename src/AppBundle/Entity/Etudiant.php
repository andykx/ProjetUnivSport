<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Etudiant
 *
 * @ORM\Table(name="etudiant")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\EtudiantRepository")
 */
class Etudiant
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="nomEtu", type="string", length=255, nullable=true)
     */
    private $nomEtu;

    /**
     * @var string
     *
     * @ORM\Column(name="prenomEtu", type="string", length=255, nullable=true)
     */
    private $prenomEtu;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set nomEtu
     *
     * @param string $nomEtu
     *
     * @return Etudiant
     */
    public function setNomEtu($nomEtu)
    {
        $this->nomEtu = $nomEtu;

        return $this;
    }

    /**
     * Get nomEtu
     *
     * @return string
     */
    public function getNomEtu()
    {
        return $this->nomEtu;
    }

    /**
     * Set prenomEtu
     *
     * @param string $prenomEtu
     *
     * @return Etudiant
     */
    public function setPrenomEtu($prenomEtu)
    {
        $this->prenomEtu = $prenomEtu;

        return $this;
    }

    /**
     * Get prenomEtu
     *
     * @return string
     */
    public function getPrenomEtu()
    {
        return $this->prenomEtu;
    }
}

