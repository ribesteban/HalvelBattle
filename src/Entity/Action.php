<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ActionRepository")
 */
class Action
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
    private $action_nom;

    /**
     * @ORM\GeneratedValue
     * @ORM\Column(type="decimal", nullable=true)
     */
    private $action_etat;

    /**
     * @ORM\GeneratedValue
     * @ORM\Column(type="string",  nullable=true)
     */
    private $_user_id_;

    /**
     * @ORM\GeneratedValue
     * @ORM\Column(type="string", nullable=true)
     */
    private $partie_id_;
}
