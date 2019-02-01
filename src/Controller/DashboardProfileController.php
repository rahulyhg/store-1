<?php
namespace App\Controller;

use App\Controller\MainDashboardController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Filesystem\Filesystem;

//use App\Entity\DashboardUsers;

class DashboardProfileController extends MainDashboardController
{
    /* ##################################################################################### */
    // ============================ RENDER DASHBOARD PROFILE TEMPLATE ======================
    //
    /* ##################################################################################### */
    public function renderDashboardProfileAction()
    {
    	$request = Request::createFromGlobals();
         $user_id = $request->cookies->get('user_id');
        if ($this->checkAuthorization() == true) {
            return $this->render('dashboard_profile.twig', array(
              'translation' => $this->getTranslation(),
              'authorization' => $this->checkAuthorization(),
              'profile' => $this->getProfile($user_id),
            ));
        } else {
            return $this->render('dashboard_authorization.twig', array(
              'translation' => $this->getTranslation(),
              'authorization' => $this->checkAuthorization(),
            ));
        }
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function editProfileAction(Request $request)
    {
        if ($this->checkAuthorization() == false) {
            $this->writeLog("Controller/Dashboard/Profile/editProfile: Authorization Error");
            return $this->render('error_access.twig', array(
                'translation' => $this->getTranslation()
            ));
        }

        $em = $this->getDoctrine()->getManager();
        $user_id = $request->request->get('input_user_id');
        //$user_id = $this->getProfile($request->request->get('input_user_id'));
        $user_object = $em->getRepository('App:DashboardUsers')->findOneByUserId($user_id);

        $user_object->setUserStatus(true);

        $user_object->setUserName($request->request->get('input_user_name'));
        $user_object->setUserEmail($request->request->get('input_user_email'));

        if ($request->request->get('input_user_password') && strlen($request->request->get('input_user_password')) >= 6) {
            $user_object->setUserPassword($this->getPasswordHash($request->request->get('input_user_password')));
        }

        $em->flush();
        
        //$this->logoutUserAction();

        return new RedirectResponse($this->generateUrl('dashboard_profile'));
    }
}
