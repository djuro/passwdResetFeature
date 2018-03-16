<?php

namespace AppBundle\Service;

use AppBundle\Service\UserRepositoryService;
use AppBundle\Entity\User;
use Psr\Log\LoggerInterface;

use \Exception;


/**
 * Contains a few methods for security checking.
 */
class UserService
{
    
    const ALGO = "sha256";
    
    const USER_NOT_FOUND_MSG = "User not found.";
    
    /**
     *
     * @var UserRepositoryService 
     */
    private $userRepository;
    
    /**
     *
     * @var LoggerInterface
     */
    private $logger;
    
    /**
     * 
     * @param UserRepositoryService $userRepository
     * @param Logger $logger
     */
    public function __construct(UserRepositoryService $userRepository, LoggerInterface $logger)
    {
        $this->userRepository = $userRepository;
        $this->logger = $logger;
    }
    
    /**
     * 
     * @param string $username
     * @param string $password
     * @return boolean
     */
    public function checkCredentials($username, $password)
    {
        try
        {
            $user = $this->userRepository->findOneByUsername($username);
        
            if($user instanceof User)
                return $this->comparePasswords($password, $user->getPassword());
        }
        catch(Exception $e)
        {
            $this->logger->error(self::USER_NOT_FOUND_MSG . $e->getMessage());
            return FALSE;
        }
    }
    
    /**
     * 
     * @param string $password
     * @param string $passwordHash
     * @return boolean
     */
    private function comparePasswords($password, $passwordHash)
    {
        return hash(self::ALGO, $password) == $passwordHash ? TRUE: FALSE;
    }
    
}
