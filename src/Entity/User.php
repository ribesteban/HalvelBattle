<?php
namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

// /src/Entity/User.php



/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="user")
 * @UniqueEntity(fields="email", message="Email déjà pris")
 * @UniqueEntity(fields="username", message="Username déjà pris")
 */
class User implements UserInterface, \Serializable
{
    /**
     * @var int
     *
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;


    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true, length=50)
     * @Assert\NotBlank()
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(type="string", unique=true, length=50)
     * @Assert\NotBlank()
     * @Assert\Email()
     */
    private $email;

    /**
     * @var string
     *
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @var array
     *
     * @ORM\Column(type="text")
     */
    private $roles;


    /**
     * @ORM\GeneratedValue
     * @ORM\Column(type="string", nullable=true)
     */
    private $user_image;

    /**
     * @ORM\GeneratedValue
     * @ORM\Column(type="decimal", nullable=true)
     */
    private $user_etat;

    /**
     * @ORM\GeneratedValue
     * @ORM\Column(type="decimal", nullable=true)
     */
    private $user_jouees;

    /**
     * @ORM\GeneratedValue
     * @ORM\Column(type="decimal", nullable=true)
     */
    private $user_win;


    public function getId(): int
    {
        return $this->id;
    }


    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): void
    {
        $this->email = $email;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    /**
     * Retourne les rôles de l'user
     */
     public function getRoles()
       {
          // Afin d'être sûr qu'un user a toujours au moins 1 rôle
           return json_decode($this->roles);
       }

       /**
        * @param $roles
        * @return $this
        */
       public function setRoles($roles)
       {
           $this->roles = $roles;
       }

    /**
     * @return mixed
     */
    public function getUserImage()
    {
        return $this->user_image;
    }

    /**
     * @param mixed $user_image
     */
    public function setUserImage($user_image)
    {
        $this->user_image = $user_image;
    }

    /**
     * @return mixed
     */
    public function getUserEtat()
    {
        return $this->user_etat;
    }

    /**
     * @param mixed $user_etat
     */
    public function setUserEtat($user_etat)
    {
        $this->user_etat = $user_etat;
    }

    /**
     * @return mixed
     */
    public function getUserJouees()
    {
        return $this->user_jouees;
    }

    /**
     * @param mixed $user_jouees
     */
    public function setUserJouees($user_jouees)
    {
        $this->user_jouees = $user_jouees;
    }

    /**
     * @return mixed
     */
    public function getUserWin()
    {
        return $this->user_win;
    }

    /**
     * @param mixed $user_win
     */
    public function setUserWin($user_win)
    {
        $this->user_win = $user_win;
    }

    /**
     * Retour le salt qui a servi à coder le mot de passe
     *
     * {@inheritdoc}
     */
    public function getSalt(): ?string
    {
// See "Do you need to use a Salt?" at https://symfony.com/doc/current/cookbook/security/entity_provider.html
// we're using bcrypt in security.yml to encode the password, so
// the salt value is built-in and you don't have to generate one

        return null;
    }

    /**
     * Removes sensitive data from the user.
     *
     * {@inheritdoc}
     */
    public function eraseCredentials(): void
    {
// Nous n'avons pas besoin de cette methode car nous n'utilions pas de plainPassword
// Mais elle est obligatoire car comprise dans l'interface UserInterface
// $this->plainPassword = null;
    }

    /**
     * {@inheritdoc}
     */
    public function serialize(): string
    {
        return serialize([$this->id, $this->username, $this->password]);
    }

    /**
     * {@inheritdoc}
     */
    public function unserialize($serialized): void
    {
        [$this->id, $this->username, $this->password] = unserialize($serialized, ['allowed_classes' => false]);
    }
}
?>
