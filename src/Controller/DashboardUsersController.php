<?php
namespace App\Controller;

use App\Controller\MainDashboardController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Entity\DashboardUsers;

class DashboardUsersController extends MainDashboardController
{
    /* ##################################################################################### */
    // ============================ RENDER DASHBOARD USERS TEMPLATE ======================
    //
    /* ##################################################################################### */
    public function renderDashboardUsersTemplateAction()
    {
        $request = Request::createFromGlobals();
        $user_id = $request->cookies->get('user_id');
        if ($this->checkAuthorization() == true) {
            return $this->render('dashboard_users.twig', array(
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
    private function getUsers()
    {
        $request = Request::createFromGlobals();

        $users_repository = $this->getDoctrine()->getRepository('App:DashboardUsers');
        $users_object = $users_repository->findAll();

        foreach ($users_object as $user) {
            $users[$user->getUserId()] = array(
                'id' => $user->getUserId(),
                'name' => $user->getUserName(),
                'email' => $user->getUserEmail(),
                'status' => $user->getUserStatus(),
                'current_user_id' => $request->cookies->get('user_id'),
            );
        }

        return $users;
    }

    public function getUsersAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $users = $this->getUsers();
            return new JsonResponse($users);
        } else {
            $this->writeLog("Controller/Dashboard/Users/getUsers: Authorization Error");
            return $this->render('error_access.twig', array(
                'translation' => $this->getTranslation()
            ));
        }
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    private function changeUser($form_data)
    {
        $em = $this->getDoctrine()->getManager();
        $user_object = $em->getRepository('App:DashboardUsers')->findOneByUserId($form_data['id']);

        $user_object->setUserStatus($form_data['status']);

        $em->flush();

        return true;
    }

    public function changeUserAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $form_data = $request->request->get('form_data');
            $result['result'] = $this->changeUser($form_data);
            return new JsonResponse($result);
        } else {
            $this->writeLog("Controller/Dashboard/Users/changeUser: Authorization Error");
            return $this->render('common_access_error.html.twig', array(
                'translation' => $this->getTranslation('access_error')
            ));
        }
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    private function deleteUser($user_id)
    {
        $em = $this->getDoctrine()->getManager();

        $user_object = $em->getRepository('App:DashboardUsers')->findOneByUserId($user_id);

        $em->remove($user_object);
        $em->flush();

        return true;
    }

    public function deleteUserAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $user_id = $request->request->get('user_id');
            $result['result'] = $this->deleteUser($user_id);
            return new JsonResponse($result);
        } else {
            $this->writeLog("Controller/Dashboard/Users/deleteUser: Authorization Error");
            return $this->render('common_access_error.html.twig', array(
                'translation' => $this->getTranslation('access_error')
            ));
        }
    }
}
