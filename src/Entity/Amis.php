<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AmisRepository")
 */
class Amis
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
     * @ORM\Column(type="string")
     */
    private $id_ami1;

    /**
     * @ORM\GeneratedValue
     * @ORM\Column(type="string")
     */
    private $id_ami2;

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
    public function getIdAmi1()
    {
        return $this->id_ami1;
    }

    /**
     * @param mixed $id_ami1
     */
    public function setIdAmi1($id_ami1): void
    {
        $this->id_ami1 = $id_ami1;
    }

    /**
     * @return mixed
     */
    public function getIdAmi2()
    {
        return $this->id_ami2;
    }

    /**
     * @param mixed $id_ami2
     */
    public function setIdAmi2($id_ami2): void
    {
        $this->id_ami2 = $id_ami2;
    }


}
