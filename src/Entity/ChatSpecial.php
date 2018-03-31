<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChatSpecialRepository")
 */
class ChatSpecial
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
    private $message_spe_id;

    /**
     * @ORM\GeneratedValue
     * @ORM\Column(type="string")
     */
    private $pseudo_id1;

    /**
     * @ORM\GeneratedValue
     * @ORM\Column(type="string")
     */
    private $pseudo_id2;

    /**
     * @ORM\GeneratedValue
     * @ORM\Column(type="string")
     */
    private $datacreate_spe;

    /**
     * @ORM\GeneratedValue
     * @ORM\Column(type="string")
     */
    private $message_spe;
}
