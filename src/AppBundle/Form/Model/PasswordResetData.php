<?php

namespace AppBundle\Form\Model;

use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

/**
 * Underlying data class for PasswordReset form. 
 * Form component binds form data to it and validation rules get applied.
 */
class PasswordResetData
{
    const EMPTY_VALUES_MSG = "New password and confirmed password value are both required.";
    
    const PASSWORDS_DONT_MATCH_MSG = "Passwords don't match.";
    
    /**
     *
     * @var string
     */
    private $newPassword;
    
    /**
     *
     * @var string
     */
    private $confirmPassword;
    
    
    public function getNewPassword() {
        return $this->newPassword;
    }

    public function getConfirmPassword() {
        return $this->confirmPassword;
    }

    public function setNewPassword($newPassword) {
        $this->newPassword = $newPassword;
        return $this;
    }

    public function setConfirmPassword($confirmPassword) {
        $this->confirmPassword = $confirmPassword;
        return $this;
    }


     /**
     * @Assert\Callback
     */
    public function validate(ExecutionContextInterface $context)
    {
        if(empty($this->newPassword) && empty($this->confirmPassword))
        {
            $context->buildViolation(self::EMPTY_VALUES_MSG)
                    ->atPath('newPassword')
                    ->addViolation();
        }
        
        if($this->newPassword != $this->confirmPassword)
        {
            $context->buildViolation(self::PASSWORDS_DONT_MATCH_MSG)
                    ->atPath('newPassword')
                    ->addViolation();
        }
    }
}
