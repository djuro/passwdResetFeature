<?php

namespace AppBundle\Service;

use AppBundle\Entity\User;

use Doctrine\ORM\EntityManagerInterface;

/**
 * Data access layer class (Repository pattern). Query methods required for 
 * User records (objects).
 */
class UserRepositoryService
{
    /**
     *
     * @var EntityManagerInterface
     */
    private $em;
    
    /**
     * 
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }
    
    /**
     * 
     * @param string $username
     * @return User
     */
    public function findOneByUsername(string $username): User
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('u')
                ->from('AppBundle:User', 'u')
                ->where('u.username = :username')
                ->setParameter('username', $username);
        
        $user = $qb->getQuery()->getSingleResult();
        return $user;
    }
    
    /**
     * 
     * @param string $email
     * @return User
     */
    public function findOneByEmail(string $email): User
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('u')
                ->from('AppBundle:User', 'u')
                ->where('u.email = :email')
                ->setParameter('email', $email);
        
        $user = $qb->getQuery()->getSingleResult();
        
        return $user;
    }
    
    /**
     * 
     * @param string $hash
     * @return User
     */
    public function findOneByRandomHash($hash)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('u')
                ->from('AppBundle:User', 'u')
                ->where('u.randomHash = :hash')
                ->setParameter('hash', $hash);
        
        $user = $qb->getQuery()->getSingleResult();
        return $user;
    }
    /**
     * 
     * @param User $user
     */
    public function persist(User $user)
    {
        $this->em->persist($user);
    }
    
    public function flush()
    {
        $this->em->flush();
    }
}
