<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ChatRepository")
 */
class Chat
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

    private $pseudo_id;

    /**
     * @ORM\GeneratedValue
     * @ORM\Column(type="string")
     */

    private $message_global;


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
    public function getPseudoId()
    {
        return $this->pseudo_id;
    }

    /**
     * @param mixed $pseudo_id
     */
    public function setPseudoId($pseudo_id): void
    {
        $this->pseudo_id = $pseudo_id;
    }

    /**
     * @return mixed
     */
    public function getMessageGlobal()
    {
        return $this->message_global;
    }

    /**
     * @param mixed $message_global
     */
    public function setMessageGlobal($message_global): void
    {
        $this->message_global = $message_global;
    }




}
