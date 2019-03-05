<?php
namespace App\Controller;

use App\Controller\MainDashboardController;

use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;
//use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

class DashboardMetaController extends MainDashboardController
{
    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function renderDashboardMetaTemplateAction()
    {
        if ($this->checkAuthorization() == true) {
            $request = Request::createFromGlobals();
            return $this->render('dashboard_meta.twig', array(
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
    private function getMetaFilePath($language_id)
    {
      $common_languages_repository = $this->getDoctrine()->getRepository('App:CommonLanguages');
      $common_language_object = $common_languages_repository->findOneByLanguageId($language_id);
      $language_code = $common_language_object->getLanguageCode();

      $meta_file_path = $this->getAppDir() . '/public/meta/meta.' . $language_code . '.yaml';

      return $meta_file_path;
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function getMetaAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $language_id = $request->request->get('language_id');

            $meta_file_path = $this->getMetaFilePath($language_id);

            $file_system = new Filesystem();

            try {
                if (!$file_system->exists($meta_file_path)) {
                    $file_system->touch($meta_file_path);

                    $new_meta = array(
                      'title' => 'Seandle Store',   // Заголовок страницы
                      'name' => 'Seandle Store',    // Название магазина в шапке сайта
                      'description' => 'Description for this store',
                      'keywords' => 'some, keywords, for, this, store',
                      'aboutus' => 'Text About Us in the footer of the site.',
                      'copyright' => 'Copyright text',
                      'revisit' => 15,
                    );

                    $yaml = Yaml::dump($new_meta);
                    file_put_contents($meta_file_path, $yaml);

                    $result['newfile'] = true;
                }
            } catch (IOExceptionInterface $exception) {
                $this->writeLog('App/Controller/DashboardInformationController::getInformationAction & Error read file: ' . $exception->getPath());
                $result['status'] = false;
                return new JsonResponse($result);
            }

            try {
                $result['form'] = Yaml::parseFile($meta_file_path);
            } catch (ParseException $exception) {
                $this->writeLog('Ошибка получения языкового пакета. Возможно пакет отсутствует. Текст ошибки: %s', $exception->getMessage());
                //$translation = Yaml::parseFile($this->getAppDir() . 'translations/translation.ru.yaml');
            }

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
    public function saveMetaAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $language_id = $request->request->get('language_id');
            $form = $request->request->get('form');

            $meta_file_path = $this->getMetaFilePath($language_id);

            try {
                $yaml = Yaml::dump($form);
                file_put_contents($meta_file_path, $yaml);
                $result['status'] = true;
            } catch (ParseException $exception) {
                $this->writeLog('App/Controller/DashboardMetaController::saveDefaultLanguagesAction Unable to parse the YAML string: ' . $exception->getMessage());
                $result['error'] = 'Unable to parse the YAML string: ' . $exception->getMessage();
                $result['result'] = false;
            }

            return new JsonResponse($result);
        } else {
            return $this->render('dashboard_authorization.twig', array(
              'translation' => $this->getTranslation(),
              'authorization' => $this->checkAuthorization(),
            ));
        }
    }
}
