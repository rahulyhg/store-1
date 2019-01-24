<?php
namespace App\Controller;

use App\Controller\MainDashboardController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use App\Entity\DashboardUsers;

class DashboardRegistrationController extends MainDashboardController
{
    /* ##################################################################################### */
    // ============================ RENDER DASHBOARD REGISTRATION TEMPLATE =================
    //
    /* ##################################################################################### */
    public function renderDashboardRegistrationAction()
    {
        $session = new Session();
        $session->invalidate();

        return $this->render('dashboard_registration.html.twig', array(
          'translation' => $this->getTranslation(),
        ));
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function registerUserAction(Request $request)
    {
        $session = new Session();
        $session->invalidate();

        $em = $this->getDoctrine()->getManager();
        $dashboard_users = new DashboardUsers();

        if (strlen($request->request->get('input_user_name')) >= 3 || strlen($request->request->get('input_user_name')) < 50) {
        	return $this->render('error_request.html.twig', ['translation' => $this->getTranslation()]);
        } else {
            $dashboard_users->setUserName($request->request->get('input_user_name'));
        }
        
        if ($this->checkPhoneNumber($request->request->get('input_user_phone')) == true) {
            $dashboard_users->setUserPhone($request->request->get('input_user_phone'));
         } else {
         	return $this->render('error_request.html.twig', ['translation' => $this->getTranslation()]);
         }
         
        if ($this->checkEmail($request->request->get('input_user_email')) == true) {
            $dashboard_users->setUserEmail($request->request->get('input_user_email'));
        } else {
        	return $this->render('error_request.html.twig', ['translation' => $this->getTranslation()]);
        }
        
        if (strlen($request->request->get('input_user_password')) >= 6 || strlen($request->request->get('input_user_password')) < 50) {
        	$dashboard_users->setUserPassword($this->getPasswordHash($request->request->get('input_user_password')));
        } else {
            return $this->render('error_request.html.twig', ['translation' => $this->getTranslation()]);
        }
        
        $dashboard_users->setUserStatus(0);

        $em->persist($dashboard_users);
        $em->flush();

        return new RedirectResponse($this->generateUrl('dashboard_dashboard'));
    }
    
    /* ##################################################################################### */
    // Проверяет наличие в БД аккаунта с таким емейлом
    /* ##################################################################################### */
    public function checkEmailExists() {
    	
    }
    
    /* ##################################################################################### */
    // Проверяет наличие в БД аккаунта с таким номером телефона
    /* ##################################################################################### */
    public function checkPhoneNumberExists() {
    	
    }
}
