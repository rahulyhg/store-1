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

        if (strlen($request->request->get('input_user_name')) >= 3 && strlen($request->request->get('input_user_name')) < 60) {
          $dashboard_users->setUserName($request->request->get('input_user_name'));
        } else {
          return $this->render('error_request.html.twig', [
            'translation' => $this->getTranslation(),
            'error' => 'App/DashboardRegistrationController::registerUserAction > input_user_name'
          ]);
        }

        if ($this->checkEmail($request->request->get('input_user_email')) == false) {
          return $this->render('error_request.html.twig', [
            'translation' => $this->getTranslation(),
            'error' => 'App/DashboardRegistrationController::registerUserAction > [input_user_email] Email format error'
          ]);
        } elseif ($this->checkUserEmailExists($request->request->get('input_user_email')) == false) {
          return $this->render('error_request.html.twig', [
            'translation' => $this->getTranslation(),
            'error' => 'App/DashboardRegistrationController::registerUserAction > [input_user_email] The folowing Email exists'
          ]);
        } else {
            $dashboard_users->setUserEmail($request->request->get('input_user_email'));
        }

        if (strlen($request->request->get('input_user_password')) >= 6 && strlen($request->request->get('input_user_password')) < 60) {
            $dashboard_users->setUserPassword($this->getPasswordHash($request->request->get('input_user_password')));
        } else {
            return $this->render('error_request.html.twig', [
              'translation' => $this->getTranslation(),
              'error' => 'App/DashboardRegistrationController::registerUserAction > input_user_password'
            ]);
        }

        $dashboard_users->setUserStatus(0);

        $em->persist($dashboard_users);
        $em->flush();

        return new RedirectResponse($this->generateUrl('dashboard_dashboard'));
    }

    /* ##################################################################################### */
    // Проверяет наличие в БД аккаунта с таким емейлом
    /* ##################################################################################### */
    public function checkUserEmailExists($user_email)
    {
        $dashboard_users_repository = $this->getDoctrine()->getRepository('App:DashboardUsers');
        $dashboard_users_object = $dashboard_users_repository->findAll();

        foreach ($dashboard_users_object as $user) {
            if (strcmp($user_email, $user->getUserEmail()) == 0) {
                return false;
            }
        }

        return true;
    }

}
