<?php

namespace AppBundle\Service;

use Psr\Log\LoggerInterface;

use \Swift_Message;
use \Swift_Mailer;
use \Exception;

class MailingService
{
    const FROM = "djuro.mandinic@gmail.com";
    
    const SUBJECT = "Password reset";
    
    const BODY = "Hello. \n\n You requested to reset your password. Click on this link: %s \n\n to do so.";
    
    const SENDING_FAILED_MSG = "E-mail sending failed.";
    
    /**
     *
     * @var Swift_Mailer
     */
    private $mailer;
    
    /**
     *
     * @var LoggerInterface
     */
    private $logger;
    
    /**
     * 
     * @param Swift_Mailer $mailer
     */
    public function __construct(Swift_Mailer $mailer, LoggerInterface $logger)
    {
        $this->mailer = $mailer;
        $this->logger = $logger;
    }
    
    /**
     * 
     * @param string $email
     * @param string $url
     * @return Swift_Message;
     */
    public function createMessage($email, $url): Swift_Message
    {
        $body = sprintf(self::BODY, $url);
        
        $message = (new \Swift_Message(self::SUBJECT))
            ->setFrom(self::FROM)
            ->setTo($email)
            ->setBody($body);
        
        return $message;
    }
    
    /**
     * 
     * @param Swift_Message $message
     */
    public function sendMessage(Swift_Message $message)
    {
        try
        {
            $this->mailer->send($message);
        }
        catch (Exception $e)
        {
            $this->logger->error('danger', self::SENDING_FAILED_MSG . $e->getMessage());
            throw $e;
            return FALSE;
        }
        
    }
}
