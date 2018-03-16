<?php

namespace AppBundle\Service;


use AppBundle\Service\UserService;
use AppBundle\Service\UserRepositoryService;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Psr\Log\LoggerInterface;

use \Exception;

class SecureUrlService
{
    
    const SALT = "6ur3At14nt1c53454lt";
    
    const RESET_PASSWD_ROUTE = "reset_password";
    
    const RESET_PASSWD_PARAM = "userHash";
    
    const URL_GEN_FAILURE_MSG = "Failed to generate reset URL.";
    
    /**
     *
     * @var UrlGeneratorInterface
     */
    private $router;
    
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
     * @param UrlGeneratorInterface $router
     * @param UserRepositoryService $userRepository
     */
    public function __construct(UrlGeneratorInterface $router, 
            UserRepositoryService $userRepository, LoggerInterface $logger) {
        
        $this->router = $router;
        $this->userRepository = $userRepository;
        $this->logger = $logger;
    }
    
    /**
     * 
     * @param string $email
     * @param string $baseUrl
     * @return string | boolean
     */
    public function generateUrlFor(string $email, string $baseUrl)
    {
        try
        {
            $user = $this->userRepository->findOneByEmail($email);
            $resetUrl = $this->createUrl($user->getRandomHash());
            return $baseUrl . $resetUrl;
        } 
        catch(Exception $e)
        {
            $this->logger->error(self::URL_GEN_FAILURE_MSG . $e->getMessage());
            return FALSE;
        }
    }
    
    
    /**
     * 
     * @param string $hash
     */
    private function createUrl($hash): string
    {
        $url = $this->router->generate(self::RESET_PASSWD_ROUTE, array(self::RESET_PASSWD_PARAM => $hash));
        return $url;
    }
}
