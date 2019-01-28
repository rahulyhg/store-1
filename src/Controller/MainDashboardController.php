<?php
namespace App\Controller;

use App\Controller\MainController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\HttpFoundation\Session\Storage\NativeSessionStorage;

/* ========== Подключение БД ========== */
use App\Entity\DashboardUsers;

class MainDashboardController extends MainController
{
    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    protected function getProfile($user_id)
    {
        $users_repository = $this->getDoctrine()->getRepository('App:DashboardUsers');
        $user_object = $users_repository->findOneByUserId($user_id);

        $profile = array(
          'id' => $user_object->getUserId(),
          'name' => $user_object->getUserName(),
          'email' => $user_object->getUserEmail(),
          'password' => $user_object->getUserPassword()
        );

        // сделать установку всех данных в куки
        $response = new Response();
        $response->headers->setCookie(new Cookie('user_id', $profile['id']));
        $response->headers->setCookie(new Cookie('user_name', $profile['name']));
        $response->headers->setCookie(new Cookie('user_email', $profile['email']));
        $response->headers->setCookie(new Cookie('user_password', $profile['password']));

        $response->send();

        if ($this->checkAuthorization() == true) {
            return $profile;
        } else {
            $this->writeLog("App/Controller/MainDashboardController::getProfile [authorization error]");
            return $this->render('error_access.html.twig', array(
              'translation' => $this->getTranslation(),
              'error' => 'App/Controller/MainDashboardController::getProfile [authorization error]'
          ));
        }
    }

    /* ##################################################################################### */
    // Представление строки с экранированием специальных символов
    /* ##################################################################################### */
    protected function provideString($string)
    {
        $string = stripslashes($string);
        $string = htmlspecialchars($string);
        $string = addslashes($string);

        return $string;
    }

    /* ##################################################################################### */
    // Получение хэша пароля
    /* ##################################################################################### */
    protected function getPasswordHash($password)
    {
        $options = array(
            'cost' => 11,
        );
        $hash = password_hash($password, PASSWORD_DEFAULT, $options);

        return $hash;
    }

    /* ##################################################################################### */
    // Проверка хэша пароля и введенного пароля на соответствие
    /* ##################################################################################### */
    protected function checkPasswordHash($password, $hash)
    {
        $result = password_verify($password, $hash);
        return $result;
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function loginUserAction(Request $request)
    {
        $session = new Session();
        $session->invalidate();
        $options['cache_limiter'] = session_cache_limiter();
        $storage = new NativeSessionStorage($options);
        $session->start();

        $request = Request::createFromGlobals();
        $response = new Response();

        $form_data = $request->request->get('form_data');

        $dashboard_users_repository = $this->getDoctrine()->getRepository('App:DashboardUsers');
        $dashboard_users_object = $dashboard_users_repository->findAll();

        $result['result'] = false;

        foreach ($dashboard_users_object as $user) {
            if (strcmp($user->getUserEmail(), $form_data['email']) == 0 && $this->checkPasswordHash($form_data['password'], $user->getUserPassword()) == true && $user->getUserStatus() == true) {
                $response->headers->setCookie(new Cookie('user_id', $user->getUserId()));
                $response->headers->setCookie(new Cookie('user_name', $user->getUserName()));
                $response->headers->setCookie(new Cookie('user_email', $user->getUserEmail()));
                $response->headers->setCookie(new Cookie('user_password', $user->getUserPassword()));

                $response->send();

                $result['result'] = true;
            }
        }

        return new JsonResponse($result);
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    protected function checkAuthorization()
    {
        $request = Request::createFromGlobals();
        if ($request->cookies->has('user_id')) {
            $dashboard_users_repository = $this->getDoctrine()->getRepository('App:DashboardUsers');
            $user_object = $dashboard_users_repository->findOneByUserId($request->cookies->get('user_id'));
            if (strcmp($request->cookies->get('user_email'), $user_object->getUserEmail()) == 0 && strcmp($request->cookies->get('user_password'), $user_object->getUserPassword()) == 0 && $user_object->getUserStatus() == true) {
                return true;
            }
        }
        return false;
    }

    /* ##################################################################################### */
    /* #################################### LOGOUT USER ##################################### */
    //
    /* ##################################################################################### */
    public function logoutUserAction()
    {
        $response = new Response();
        $response->headers->clearCookie('user_id');
        $response->headers->clearCookie('user_name');
        $response->headers->clearCookie('user_email');
        $response->headers->clearCookie('user_password');

        $response->send();

        $session = new Session();
        $session->invalidate();

        return new JsonResponse(['result' => true]);
    }
}
