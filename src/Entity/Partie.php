<?php
namespace App\Entity;
use Doctrine\ORM\Mapping as ORM;
/**
 * @ORM\Entity(repositoryClass="App\Repository\PartieRepository")
 */
class Partie
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $j1;
    /**
     * @var User
     * @ORM\ManyToOne(targetEntity="App\Entity\User")
     */
    private $j2;
    /**
     * @var
     * @ORM\Column(type="text")
     */
    private $pioche;
    /**
     * @var
     * @ORM\Column(type="text")
     */
    private $main_j1;
    /**
     * @var
     * @ORM\Column(type="text")
     */
    private $main_j2;
    /**
     * @var
     * @ORM\Column(type="string")
     */
    private $tour;
    /**
     * @ORM\Column(type="integer")
     */
    private $carte_ecartee;
    /**
     * @var
     * @ORM\Column(type="string")
     */
    private $manche;
    /**
     * @var
     * @ORM\Column(type="integer")
     */
    private $score_j1;
    /**
     * @ORM\Column(type="integer")
     */
    private $score_j2;
    /**
     * @var
     * @ORM\Column(type="text")
     */
    private $objectif_attribution;
    /**
     * @ORM\Column(type="text")
     */
    private $action_j1;
    /**
     * @var
     * @ORM\Column(type="text")
     */
    private $action_j2;
    /**
     * @return mixed
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return User
     */
    public function getJ1() : User
    {
        return $this->j1;
    }
    /**
     * @param User $j1
     */
    public function setJ1(User $j1): void
    {
        $this->j1 = $j1;
    }
    /**
     * @return User
     */
    public function getJ2(): User
    {
        return $this->j2;
    }
    /**
     * @param User $j2
     */
    public function setJ2(User $j2): void
    {
        $this->j2 = $j2;
    }
    /**
     * @return mixed
     */
    public function getPioche()
    {
        return json_decode($this->pioche);
    }
    /**
     * @param mixed $pioche
     */
    public function setPioche($pioche): void
    {
        $this->pioche = $pioche;
    }
    /**
     * @return mixed
     */
    public function getMainJ1()
    {
        return json_decode($this->main_j1);
    }
    /**
     * @param mixed $main_j1
     */
    public function setMainJ1($main_j1): void
    {
        $this->main_j1 = $main_j1;
    }
    /**
     * @return mixed
     */
    public function getMainJ2()
    {
        return json_decode($this->main_j2);
    }
    /**
     * @param mixed $main_j2
     */
    public function setMainJ2($main_j2): void
    {
        $this->main_j2 = $main_j2;
    }
    /**
     * @return mixed
     */
    public function getTour()
    {
        return $this->tour;
    }
    /**
     * @param mixed $tour
     */
    public function setTour($tour)
    {
        $this->tour = $tour;
    }
    /**
     * @return mixed
     */
    public function getManche()
    {
        return $this->manche;
    }
    /**
     * @param mixed $manche
     */
    public function setManche($manche)
    {
        $this->manche = $manche;
    }
    /**
     * @return mixed
     */
    public function getScoreJ1()
    {
        return $this->score_j1;
    }
    /**
     * @param mixed $score_j1
     */
    public function setScoreJ1($score_j1)
    {
        $this->score_j1 = $score_j1;
    }
    /**
     * @return mixed
     */
    public function getScoreJ2()
    {
        return $this->score_j2;
    }
    /**
     * @param mixed $score_j2
     */
    public function setScoreJ2($score_j2)
    {
        $this->score_j2 = $score_j2;
    }
    /**
     * @return mixed
     */
    public function getObjectifAttribution()
    {
        return (array)json_decode($this->objectif_attribution);
    }
    /**
     * @param mixed $objectif_attribution
     */
    public function setObjectifAttribution($objectif_attribution): void
    {
        $this->objectif_attribution = $objectif_attribution;
    }
    /**
     * @return mixed
     */
    public function getActionJ1()
    {
        return json_decode($this->action_j1);
    }
    /**
     * @param mixed $action_j1
     */
    public function setActionJ1($action_j1): void
    {
        $this->action_j1 = $action_j1;
    }
    /**
     * @return mixed
     */
    public function getActionJ2()
    {
        return json_decode($this->action_j2);
    }
    /**
     * @param mixed $action_j2
     */
    public function setActionJ2($action_j2):void
    {
        $this->action_j2 = $action_j2;
    }
    /**
     * @return mixed
     */
    public function getCarteEcartee()
    {
        return $this->carte_ecartee;
    }
    /**
     * @param mixed $carte_ecartee
     */
    public function setCarteEcartee($carte_ecartee) : void
    {
        $this->carte_ecartee = $carte_ecartee;
    }
}