<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ObjectifsRepository")
 */
class Objectifs
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    // add your own fields

    /**
     * @ORM\GeneratedValue
     * @ORM\Column(type="decimal")
     */
    private $objectif_id;

    /**
     * @ORM\GeneratedValue
     * @ORM\Column(type="string")
     */
    private $carte_img;

    /**
     * @ORM\GeneratedValue
     * @ORM\Column(type="decimal")
     */
    private $objectif_nbpts;

    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id): void
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getObjectifId()
    {
        return $this->objectif_id;
    }

    /**
     * @param mixed $objectif_id
     */
    public function setObjectifId($objectif_id): void
    {
        $this->objectif_id = $objectif_id;
    }

    /**
     * @return mixed
     */
    public function getCarteImg()
    {
        return $this->carte_img;
    }

    /**
     * @param mixed $carte_img
     */
    public function setCarteImg($carte_img): void
    {
        $this->carte_img = $carte_img;
    }

    /**
     * @return mixed
     */
    public function getObjectifNbpts()
    {
        return $this->objectif_nbpts;
    }

    /**
     * @param mixed $objectif_nbpts
     */
    public function setObjectifNbpts($objectif_nbpts): void
    {
        $this->objectif_nbpts = $objectif_nbpts;
    }


}
