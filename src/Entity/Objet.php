<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="App\Repository\ObjetRepository")
 */
class Objet
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
    /**
     * @var string
     * @ORM\Column(type="string", length=20)
     */
    private $nom;
    /**
     * @var integer
     * @ORM\Column(type="integer")
     */
    private $valeur;
    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Objectifs")
     */
    private $objectif;

    /**
     * @var string
     * @ORM\Column(type="string")
     */
    private $objet_img;

    /**
     * @return mixed
     */
    public function getObjectif()
    {
        return $this->objectif;
    }
    /**
     * @param mixed $objectif
     */
    public function setObjectif($objectif): void
    {
        $this->objectif = $objectif;
    }
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }
    /**
     * @return string
     */
    public function getNom(): string
    {
        return $this->nom;
    }
    /**
     * @param string $nom
     */
    public function setNom(string $nom): void
    {
        $this->nom = $nom;
    }
    /**
     * @return int
     */
    public function getValeur(): int
    {
        return $this->valeur;
    }
    /**
     * @param int $valeur
     */
    public function setValeur(int $valeur): void
    {
        $this->valeur = $valeur;
    }

    /**
     * @return string
     */
    public function getObjetImg()
    {
        return $this->objet_img;
    }

    /**
     * @param string $objet_img
     */
    public function setObjetImg($objet_img)
    {
        $this->objet_img = $objet_img;
    }


}