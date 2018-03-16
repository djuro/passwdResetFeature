<?php

namespace AppBundle\Controller;

use Symfony\Component\HttpFoundation\Session\Session;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

/**
 * Contains a restricted action (page). Just as an example of successful authentication.
 */
class AdminController extends Controller
{
    /**
     * @Route("/admin/welcome", name="admin_welcome")
     */
    public function welcomePageAction(Request $request)
    {
        
        if(TRUE !== $this->checkIfAuthenticated($request->getSession()))
            throw new NotFoundHttpException;
        
        return $this->render("@App/Admin/welcome.html.twig");
            
    }
    
    /**
     * 
     * @param Session $session
     * @return bool
     */
    private function checkIfAuthenticated(Session $session): bool
    {   
        
        return empty($session->get('username')) ? FALSE: TRUE;
    }
}
