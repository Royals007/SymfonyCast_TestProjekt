<?php


namespace AppBundle\Entity;


use Symfony\Component\Security\Core\User\UserInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="user")
 */
class User implements UserInterface
{

    /**
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $email;


    /**
     * @ORM\Column(type="string")
     */
    private $password;


    /**
     * creates a non-persisted field -- for encoded password
     */
    private $plainPassword;

    public function getUsername()
    {
        // TODO: Implement getUsername() method.
        return $this->email;
    }


    public function getRoles()
    {
        // See Security.yml
        return ['ROLE_USER'];
    }

    public function getPassword()
    {
        // TODO: Implement getPassword() method.
        return $this->getPassword();
    }

    public function getSalt()
    {
        // TODO: Implement getSalt() method.

    }

    public function eraseCredentials()
    {
        // minor security measure to prevent passwd accidentally saved anywhere
        $this->plainPassword = null;

    }

    public function setEmail($email)
    {
        $this->email = $email;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * @return mixed
     */
    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

    /**
     * @param mixed $plainPassword
     */
    public function setPlainPassword($plainPassword)
    {
        $this->plainPassword = $plainPassword;

        // forces the object to look 'dirty' to doctrine.
        //Avoids doctrine  NOT saving this entity field, unless this plainPassword has been changed
        // This object looks it has been changed (password)
        // it is very essential part

        $this->password = null;
    }


}