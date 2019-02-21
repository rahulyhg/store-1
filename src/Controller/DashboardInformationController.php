<?php
namespace App\Controller;

use App\Controller\MainDashboardController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class DashboardInformationController extends MainDashboardController
{
    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function renderDashboardInformationTemplateAction()
    {
        if ($this->checkAuthorization() == true) {
            $request = Request::createFromGlobals();
            return $this->render('dashboard_information.twig', array(
              'translation' => $this->getTranslation(),
              'authorization' => $this->checkAuthorization(),
              'profile' => $this->getProfile($request->cookies->get('user_id')),
              'languages' => $this->getAllLanguages(),
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
    public function getInformationAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            // добавить получение кода языка и загрузки соответствующего ему файла
            $language_id = $request->request->get('language_id');
            $common_languages_repository = $this->getDoctrine()->getRepository('App:CommonLanguages');
            $common_language_object = $common_languages_repository->findOneByLanguageId($language_id);
            $language_code = $common_language_object->getLanguageCode();

            $file_system = new Filesystem();
            $information_file_path = $this->getAppDir() . '/public/information/information.' . $language_code . '.html';

            try {
              if (!$file_system->exists($information_file_path)) {
                $file_system->touch($information_file_path);
              }
            } catch (IOExceptionInterface $exception) {
                $this->writeLog('App/Controller/DashboardInformationController::getInformationAction & Error read file: ' . $exception->getPath());
                return $this->render('error_request.twig', array(
                  'translation' => $this->getTranslation(),
                ));
            }

            // get contents of a file into a string
            //$filename = "/usr/local/something.txt";
            $handle = fopen($information_file_path, "r");
            if (filesize($information_file_path) == 0) {
              $result['content'] = "";
              $result['filesize'] = false;
            } else {
              $result['content'] = fread($handle, filesize($information_file_path));
              $result['filesize'] = true;
            }
            fclose($handle);

            $result['status'] = true;

            return new JsonResponse($result);
        } else {
            $this->writeLog('App/Controller/DashboardInformationController::getInformationAction Authorization Error');
            return $this->render('dashboard_authorization.twig', array(
              'translation' => $this->getTranslation(),
              'authorization' => $this->checkAuthorization(),
            ));
        }
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function saveInformationAction(Request $request)
    {
        if ($this->checkAuthorization() == true) {
            return new JsonResponse();
        } else {
            return $this->render('dashboard_authorization.twig', array(
              'translation' => $this->getTranslation(),
              'authorization' => $this->checkAuthorization(),
            ));
        }
    }
}
