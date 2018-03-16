<?php

namespace AppBundle\Controller;

use AppBundle\Form\LoginType;
use AppBundle\Service\UserService;
use AppBundle\Entity\User;
use AppBundle\Service\UserRepositoryService;
use AppBundle\Service\SecureUrlService;
use AppBundle\Service\MailingService;
use AppBundle\Form\PasswordResetType;

use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Psr\Log\LoggerInterface;

use \Exception;

/**
 * Contains all action methods (pages) required by the task.
 */
class UserAuthController extends Controller
{
    
    const INVALID_CREDENTIALS_MSG = "Invalid username or password.";
    
    const EMAIL_UNKNOWN = "E-mail address you entered does not exist in our system.";
    
    const INVALID_HASH_MSG = "You are not authorized to access this feature.";
    
    const USER_NOT_FOUND = "User not found by using random hash.";
    
    const PASSWD_RESET_SUCCESSFUL = "Your password has been reset successfully.";
    
    const MAIL_SENT_MSG = "E-mail with reset password link has been sent. Check your inbox.";
    
    
    /**
     * @Route("/forgot-password", name="forgot_password")
     */
    public function forgotPasswordAction(Request $request, SecureUrlService $urlService, MailingService $mailingService)
    {
       $baseUrl = $this->getParameter('base_url');
       $form = $this->createFormBuilder()
               ->add('email', TextType::class, array('label'=>'Your e-mail address:',
                   'constraints'=>array(new Email())))
               ->getForm();
       
       $form->handleRequest($request);
       if($form->isSubmitted() && $form->isValid()) {
           $email = $form->getData()['email'];
           $url = $urlService->generateUrlFor($email, $baseUrl);
           
           if(FALSE === $url)
           {
                $this->addFlash('danger', self::EMAIL_UNKNOWN);
           }
           else
           {
                $message = $mailingService->createMessage($email, $url);
                $mailingService->sendMessage($message);
                $this->addFlash('success', self::MAIL_SENT_MSG);
           } 
       }
       
       return $this->render('@App/UserAuth/forgot_password.html.twig', 
               array('form' => $form->createView()));
    }
    
    /**
     * @Route("/reset-password/{userHash}", name="reset_password")
     */
    public function resetPasswordAction(Request $request, $userHash, UserRepositoryService $userRepository, LoggerInterface $logger)
    {
       $form = $this->createForm(PasswordResetType::class, null, array());
       $user = $this->resolveIfValidUserHash($userHash, $userRepository, $logger);
       
       if(FALSE === $user) {
           $this->addFlash('danger', self::INVALID_HASH_MSG);
           return $this->redirect($this->generateUrl('login'));
       }
       
       $form->handleRequest($request);
       if($form->isSubmitted() && $form->isValid()) 
       {
           $newPassword = $form->getData()->getNewPassword();
           $user->setPassword($newPassword);
           $userRepository->flush();
           $this->addFlash('success', self::PASSWD_RESET_SUCCESSFUL);
           return $this->redirect($this->generateUrl('login'));
       }
       
       return $this->render('@App/UserAuth/password_reset.html.twig', 
               array('form' => $form->createView()));
    }
    
    /**
     * 
     * @param string $userHash
     * @param UserRepositoryService $userRepository
     * @param LoggerInterface $logger
     * @return boolean|User
     */
    private function resolveIfValidUserHash($userHash, UserRepositoryService $userRepository, LoggerInterface $logger)
    {
        try
        {
            $user = $userRepository->findOneByRandomHash($userHash);
            if(!$user instanceof User)
            {
                return FALSE;
            }
            else
            {
                return $user;
            }
        } 
        catch (Exception $e)
        {
            $logger->error('danger', [self::USER_NOT_FOUND . $e->getMessage()]);
            return FALSE;
        }
    }
    
    /**
     * @Route("/login", name="login")
     */
    public function loginPromptAction(Request $request, UserService $userService)
    {
        $form = $this->createForm(LoginType::class, null, array());
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $formData = $form->getData();
            
            $isValid = $userService->checkCredentials($formData['username'], $formData['password']);
            
            if(TRUE === $isValid)
            {
                return $this->authenticateUser($request->getSession(), $formData['username']);
            }
            else
            {
                $this->addFlash('danger', self::INVALID_CREDENTIALS_MSG);
                return $this->redirect($this->generateUrl('login'));
            }  
        }
        return $this->render("@App/UserAuth/login.html.twig",
          array(
            'form' => $form->createView()
          ));
    }
    
    /**
     * Sets a session variable, and redirects to restricted content.
     * 
     * @param string $username
     */
    private function authenticateUser(Session $session, string $username)
    {
        $session->set('username', $username);
        return $this->redirect($this->generateUrl('admin_welcome'));
    }
    
    /**
     * @Route("/logout", name="logout")
     */
    public function logoutAction(Request $request)
    {
        $session = $request->getSession();
        $session->remove('username');
        return $this->redirect($this->generateUrl('login'));
    }
    
    
    /**
     * Temporary tool for inserting a User.
     * @Route("/new-user", name="new_user")
     */
//    public function createUserAction(UserRepositoryService $userRepository)
//    {
//        $user = new User();
//        $user->setName("John")
//                ->setSurname("Doe")
//                ->setUsername("jdoe")
//                ->setEmail("djuro.mandinic@gmail.com")
//                ->setPassword("lfp04i");
//        
//        
//        $userRepository->persist($user);
//        $userRepository->flush();
//        exit;
//    }
}
