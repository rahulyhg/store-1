<?php
namespace App\Controller;

use App\Controller\MainDashboardController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

use App\Entity\CommonLanguages;

class DashboardLanguagesController extends MainDashboardController
{
    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function renderDashboardLanguagesTemplateAction()
    {
        $request = Request::createFromGlobals();
        $user_id = $request->cookies->get('user_id');
        if ($this->checkAuthorization() == true) {
            return $this->render('dashboard_languages.twig', array(
              'translation' => $this->getTranslation(),
              'authorization' => $this->checkAuthorization(),
              'profile' => $this->getProfile($user_id),
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
    private function checkLanguageDependence($language_id)
    {
      $settings = $this->getSettings();

      $store_language = (int) $settings['store_language'];
      $dashboard_language = (int) $settings['dashboard_language'];

      if ($language_id == $store_language) {
        return true;
      }

      if ($language_id == $dashboard_language) {
        return true;
      }

      return false;
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function getLanguagesAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $common_languages_repository = $this->getDoctrine()->getRepository('App:CommonLanguages');
            $common_languages_object = $common_languages_repository->findAll();

            if (count($common_languages_object) < 1) {
                $languages['status'] = false;
                return new JsonResponse($languages);
            }

            foreach ($common_languages_object as $language) {
                $languages[] = array(
                  'id' => $language->getLanguageId(),
                  'name' => $language->getLanguageName(),
                  'code' => $language->getLanguageCode(),
                  'dependence' => $this->checkLanguageDependence($language->getLanguageId()),
                );
            }

            return new JsonResponse($languages);
        } else {
            $this->writeLog("App/Controller/DashboardLanguagesController::getLanguagesAction > Authorization Error");
            return $this->render('error_access.twig', array(
              'translation' => $this->getTranslation()
          ));
        }
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function getLanguageAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $language_id = $request->request->get('language_id');
            $common_languages_repository = $this->getDoctrine()->getRepository('App:CommonLanguages');
            $store_language_object = $common_languages_repository->findOneByLanguageId($language_id);

            $language = array(
              'id' => $store_language_object->getLanguageId(),
              'name' => $store_language_object->getLanguageName(),
              'code' => $store_language_object->getLanguageCode(),
            );

            return new JsonResponse($language);
        } else {
            $this->writeLog("App/Controller/DashboardLanguagesController::getLanguageAction > Authorization Error");
            return $this->render('error_access.twig', array(
              'translation' => $this->getTranslation()
            ));
        }
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function addLanguageAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $form = $request->request->get('form');
            $common_languages = new CommonLanguages();
            $em = $this->getDoctrine()->getManager();

            $common_languages->setLanguageName($form['name']);
            $common_languages->setLanguageCode($form['code']);

            $em->persist($common_languages);
            $em->flush();

            $result['result'] = true;

            return new JsonResponse($result);
        } else {
            $this->writeLog("App/Controller/DashboardLanguagesController::addLanguageAction > Authorization Error");
            return $this->render('error_access.twig', array(
              'translation' => $this->getTranslation()
            ));
        }
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function editLanguageAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $form = $request->request->get('form');
            $em = $this->getDoctrine()->getManager();
            $language_object = $em->getRepository('App:CommonLanguages')->findOneByLanguageId($form['id']);

            $language_object->setLanguageName($form['name']);
            $language_object->setLanguageCode($form['code']);

            $em->flush();

            $result['result'] = true;

            return new JsonResponse($result);
        } else {
            $this->writeLog("App/Controller/DashboardLanguagesController::editLanguageAction > Authorization Error");
            return $this->render('error_access.twig', array(
              'translation' => $this->getTranslation()
            ));
        }
    }

    /* ##################################################################################### */
    // Подумать над тем, чтоб удалять соответствующие файлы языка и meta-данных
    /* ##################################################################################### */
    public function deleteLanguageAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $language_id = $request->request->get('language_id');
            $em = $this->getDoctrine()->getManager();
            $language_object = $em->getRepository('App:CommonLanguages')->findOneByLanguageId($language_id);

            $em->remove($language_object);
            $em->flush();

            $result['result'] = true;

            return new JsonResponse($result);
        } else {
            $this->writeLog("App/Controller/DashboardLanguagesController::deleteLanguageAction > Authorization Error");
            return $this->render('error_access.twig', array(
              'translation' => $this->getTranslation()
            ));
        }
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function saveDefaultLanguagesAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $form = $request->request->get('form');

            $settings = $this->getSettings();
            $settings['dashboard_language'] = (int) $form['dashboard'];
            $settings['store_language'] = (int) $form['store'];
            $this->saveSettings($settings);

            $result['result'] = true;

            return new JsonResponse($result);
        } else {
            $this->writeLog("App/Controller/DashboardLanguagesController::saveDefaultLanguagesAction > Authorization Error");
            return $this->render('error_access.twig', array(
              'translation' => $this->getTranslation()
            ));
        }
    }
}
