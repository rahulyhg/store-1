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
    protected function getProfile($author_id)
    {
        $authors_repository = $this->getDoctrine()->getRepository('App:DashboardAuthors');
        $author_object = $authors_repository->findOneByAuthorId($author_id);

        $path_authors_images = '/images/authors/';

        $profile = array(
          'id' => $author_object->getAuthorId(),
          'name' => $author_object->getAuthorName(),
          //'about' => $author_object->getAuthorAbout(),
          'email' => $author_object->getAuthorEmail(),
          'image' => $path_authors_images . $author_object->getAuthorImage(),
          //'login' => $author_object->getAuthorLogin(),
          'password' => $author_object->getAuthorPassword()
        );

        // сделать установку всех данных в куки
        $response = new Response();
        $response->headers->setCookie(new Cookie('author_id', $profile['id']));
        $response->headers->setCookie(new Cookie('author_name', $profile['name']));
        $response->headers->setCookie(new Cookie('author_email', $profile['email']));
        $response->headers->setCookie(new Cookie('author_image', $profile['image']));
        //$response->headers->setCookie(new Cookie('author_login', $profile['login']));
        $response->headers->setCookie(new Cookie('author_password', $profile['password']));

        $response->send();

        if ($this->checkAuthorization() == true) {
            return $profile;
        } else {
            $this->writeLog("Controller/Dashboard/Profile/getProfile: Authorization Error");
            return $this->render('common_access_error.html.twig', array(
              'translation' => $this->getTranslation('access_error')
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
            $dashboard_users_repository = $this->getDoctrine()->getRepository('App:DashboardUser');
            $user_object = $dashboard_users_repository->findOneByUserId($request->cookies->get('user_id'));
            if (strcmp($request->cookies->get('user_email'), $author_object->getUserEmail()) == 0 && strcmp($request->cookies->get('user_password'), $user_object->getUserPassword()) == 0 && $user_object->getUserStatus() == true) {
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
