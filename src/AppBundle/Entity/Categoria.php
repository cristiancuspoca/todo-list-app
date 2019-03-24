<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * Categoria
 *
 * @ORM\Table(name="categoria")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\CategoriaRepository")
 */
class Categoria
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
     * @Assert\NotBlank
     * @ORM\Column(name="nombre", type="string", length=255)
     */
    private $nombre;

    /**
     * @ORM\OneToMany(targetEntity="Actividad", mappedBy="categoria")
     */
    private $actividades;


    public function __construct()
    {
        $this->actividades = new ArrayCollection();
    }


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
     * Set nombre
     *
     * @param string $nombre
     *
     * @return Categoria
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Add actividades
     *
     * @param \AppBundle\Entity\Actividad $actividade
     *
     * @return Categoria
     */
    public function addActividades(\AppBundle\Entity\Actividad $actividade)
    {
        $this->actividades[] = $actividade;

        return $this;
    }

    /**
     * Remove actividade
     *
     * @param \AppBundle\Entity\Actividad $actividade
     */
    public function removeActividades(\AppBundle\Entity\Actividad $actividade)
    {
        $this->actividades->removeElement($actividade);
    }

    /**
     * Get actividades
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getActividades()
    {
        return $this->actividades;
    }

    public function getActividadesFull()
    {
        return $this->actividades->toArray();
    }



    public function __toString() {
        return $this->nombre;
    }
}
