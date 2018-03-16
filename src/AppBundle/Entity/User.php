<?php

namespace AppBundle\Entity;

use AppBundle\Service\UserService;

use Doctrine\ORM\Mapping as ORM;


/**
 * Entity class for ORM mapping to database.
 * 
 * @ORM\Entity
 * @ORM\Table(name="user", 
 *  uniqueConstraints={
 *      @ORM\UniqueConstraint(name="username_idx", columns={"username"}), 
 *      @ORM\UniqueConstraint(name="rndhash_idx", columns={"random_hash"}),
 *      @ORM\UniqueConstraint(name="email_idx", columns={"email"})}
 * )
 */
class User
{
    
    /* Adds extra layer of obfuscation when generating random hash. */
    const SALT = "m4trh985hygw65y9gyuwy98475874";
    
    /**
     *
     * @var int
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(name="id", type="integer")
     */
    private $id;
    
    /**
     *
     * @var string
     * @ORM\Column(name="name", type="string")
     */
    private $name;
    
    /**
     *
     * @var string
     * @ORM\Column(name="surname", type="string")
     */
    private $surname;
    
    /**
     *
     * @var string
     * @ORM\Column(name="username", type="string")
     */
    private $username;
    
    /**
     *
     * @var string
     * @ORM\Column(name="email", type="string")
     */
    private $email;
    
    /**
     *
     * @var string
     * @ORM\Column(name="password", type="string")
     */
    private $password;
    
    /**
     *
     * @var string
     * @ORM\Column(name="random_hash", type="string")
     */
    private $randomHash;
    
    
    public function __construct()
    {
        $this->setRandomHash();
    }
    
    
    private function setRandomHash()
    {
        $randNum = rand(10, 100);
        $timestamp = time();
        $this->randomHash = hash(UserService::ALGO, $randNum. self::SALT . $timestamp);
    }
    /**
     * 
     * @return int
     */
    public function getId(): int {
        return $this->id;
    }

    /**
     * 
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * 
     * @return string
     */
    public function getSurname(): string {
        return $this->surname;
    }

    /**
     * 
     * @return string
     */
    public function getUsername(): string {
        return $this->username;
    }

    /**
     * 
     * @return string
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * 
     * @param string $name
     * @return User
     */
    public function setName(string $name) {
        $this->name = $name;
        return $this;
    }

    /**
     * 
     * @param string $surname
     * @return User
     */
    public function setSurname(string $surname) {
        $this->surname = $surname;
        return $this;
    }

    /**
     * 
     * @param string $username
     * @return User
     */
    public function setUsername(string $username) {
        $this->username = $username;
        return $this;
    }

    /**
     * 
     * @param string $email
     * @return User
     */
    public function setEmail(string $email) {
        $this->email = $email;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getPassword(): string {
        return $this->password;
    }

    /**
     * 
     * @param string $password
     * @return User
     */
    public function setPassword(string $password) {
        
        $passwdHash = hash(UserService::ALGO, $password);
        $this->password = $passwdHash;
        return $this;
    }
    
    /**
     * 
     * @return string
     */
    public function getRandomHash(): string
    {
        return $this->randomHash;
    }

}
