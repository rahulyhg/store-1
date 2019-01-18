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
//use App\Entity\DashboardAuthors;

class MainDashboardController extends MainController
{
    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    /*protected function getTranslation($template_name)
    {
        include $this->getAppDir() . 'translations/dashboard/main.php';
        include $this->getAppDir() . 'translations/dashboard/' . $template_name . '.php';

        return $translation;
    }*/

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
    public function loginAuthorAction(Request $request)
    {
        $session = new Session();
        $session->invalidate();
        $options['cache_limiter'] = session_cache_limiter();
        $storage = new NativeSessionStorage($options);
        $session->start();

        $request = Request::createFromGlobals();
        $response = new Response();

        $form_data = $request->request->get('form_data');

        $dashboard_authors_repository = $this->getDoctrine()->getRepository('App:DashboardAuthors');
        $dashboard_authors_object = $dashboard_authors_repository->findAll();

        $result['result'] = false;

        foreach ($dashboard_authors_object as $author) {
            if (strcmp($author->getAuthorLogin(), $form_data['login']) == 0 && $this->checkPasswordHash($form_data['password'], $author->getAuthorPassword()) == true && $author->getAuthorStatus() == true) {
                $response->headers->setCookie(new Cookie('author_id', $author->getAuthorId()));
                $response->headers->setCookie(new Cookie('author_name', $author->getAuthorName()));
                $response->headers->setCookie(new Cookie('author_email', $author->getAuthorEmail()));
                $response->headers->setCookie(new Cookie('author_image', $author->getAuthorImage()));
                $response->headers->setCookie(new Cookie('author_login', $author->getAuthorLogin()));
                $response->headers->setCookie(new Cookie('author_password', $author->getAuthorPassword()));

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
        if ($request->cookies->has('author_id')) {
            $dashboard_authors_repository = $this->getDoctrine()->getRepository('App:DashboardAuthors');
            $author_object = $dashboard_authors_repository->findOneByAuthorId($request->cookies->get('author_id'));
            if (strcmp($request->cookies->get('author_login'), $author_object->getAuthorLogin()) == 0 && strcmp($request->cookies->get('author_password'), $author_object->getAuthorPassword()) == 0 && $author_object->getAuthorStatus() == true) {
                return true;
            }
        }
        return false;
    }

    /* ##################################################################################### */
    /* #################################### LOGOUT USER ##################################### */
    //
    /* ##################################################################################### */
    public function logoutAuthorAction()
    {
        $response = new Response();
        $response->headers->clearCookie('author_id');
        $response->headers->clearCookie('author_login');
        $response->headers->clearCookie('author_name');
        $response->headers->clearCookie('author_email');
        $response->headers->clearCookie('author_image');
        $response->headers->clearCookie('author_password');

        $response->send();

        $session = new Session();
        $session->invalidate();

        return new JsonResponse(['result' => true]);
    }
}
