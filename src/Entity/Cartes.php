<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CartesRepository")
 */
class Cartes
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
     * @ORM\Column(type="text")
     */
    private $carte_id;

    /**
     * @ORM\GeneratedValue
     * @ORM\Column(type="decimal")
     */
    private $carte_points;

    /**
     * @ORM\GeneratedValue
     * @ORM\Column(type="string")
     */
    private $carte_nom;

    /**
     * @ORM\GeneratedValue
     * @ORM\Column(type="string")
     */
    private $carte_img;

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
    public function getCarteId()
    {
        return $this->carte_id;
    }

    /**
     * @param mixed $carte_id
     */
    public function setCarteId($carte_id): void
    {
        $this->carte_id = $carte_id;
    }

    /**
     * @return mixed
     */
    public function getCartePoints()
    {
        return $this->carte_points;
    }

    /**
     * @param mixed $carte_points
     */
    public function setCartePoints($carte_points): void
    {
        $this->carte_points = $carte_points;
    }

    /**
     * @return mixed
     */
    public function getCarteNom()
    {
        return $this->carte_nom;
    }

    /**
     * @param mixed $carte_nom
     */
    public function setCarteNom($carte_nom): void
    {
        $this->carte_nom = $carte_nom;
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

}
