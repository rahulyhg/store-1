<?php
namespace App\Controller;

use App\Controller\MainDashboardController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Filesystem\Filesystem;
//use Symfony\Component\Filesystem\Exception\IOExceptionInterface;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

class DashboardImagesController extends MainDashboardController
{
    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function renderDashboardImagesTemplateAction()
    {
        if ($this->checkAuthorization() == true) {
            $request = Request::createFromGlobals();
            return $this->render('dashboard_images.twig', array(
              'translation' => $this->getTranslation(),
              'authorization' => $this->checkAuthorization(),
              'profile' => $this->getProfile($request->cookies->get('user_id')),
              'images' => $this->getImagesAction(),
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
    private function getImagesAction()
    {
        if ($this->checkAuthorization() == true) {

            $settings_file_path = $this->getAppDir() . '/config/settings.yaml';

            try {
                $settings = Yaml::parseFile($settings_file_path);
                $store_icon_filename = $settings['store_icon'];
                $store_logo_filename = $settings['store_logo'];
            } catch (ParseException $exception) {
                $this->writeLog('App/Controller/DashboardLanguagesController::saveDefaultLanguagesAction Unable to parse the YAML string: ' . $exception->getMessage());
                return $this->render('error_request', array(
                  'translation' => $this->getTranslation(),
                  'authorization' => $this->checkAuthorization(),
                ));
            }

            $images['icon'] = $store_icon_filename;
            $images['logo'] = $store_logo_filename;

            return $images;
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
    public function saveImagesAction(Request $request)
    {
        if ($this->checkAuthorization() == true) {
            $settings_file_path = $this->getAppDir() . '/config/settings.yaml';
            $images_directory_path = $this->getAppDir() . '/public/images/store/';

            if ($request->request->get('change_store_icon') == 1 && strlen($request->request->get('store_icon_tokenname')) == 32) {
              $store_icon_tokenname = (string) $request->request->get('store_icon_tokenname');

              try {
                  $settings = Yaml::parseFile($settings_file_path);

                  $old_store_icon_filepath = $images_directory_path . $settings['store_icon'];
                  if (file_exists($old_store_icon_filepath)) {
                      $file_system = new Filesystem();
                      $file_system->remove($old_store_icon_filepath);
                  }

                  $settings['store_icon'] = $store_icon_tokenname;
                  $yaml = Yaml::dump($settings);
                  file_put_contents($settings_file_path, $yaml);
              } catch (ParseException $exception) {
                  $this->writeLog('App/Controller/DashboardImagesController::saveDefaultLanguagesAction Unable to parse the YAML string: ' . $exception->getMessage());
                  return $this->render('error_request', array(
                    'translation' => $this->getTranslation(),
                    'authorization' => $this->checkAuthorization(),
                  ));
              }

              $store_icon_file = $request->files->get('input_store_icon_file');
              $store_icon_file->move($images_directory_path, $store_icon_tokenname);
            }

            if ($request->request->get('change_store_logo') == 1 && strlen($request->request->get('store_logo_tokenname')) == 32) {
              $store_logo_tokenname = (string) $request->request->get('store_logo_tokenname');

              try {
                  $settings = Yaml::parseFile($settings_file_path);

                  $old_store_logo_filepath = $images_directory_path . $settings['store_logo'];
                  if (file_exists($old_store_logo_filepath)) {
                      $file_system = new Filesystem();
                      $file_system->remove($old_store_logo_filepath);
                  }

                  $settings['store_logo'] = $store_logo_tokenname;
                  $yaml = Yaml::dump($settings);
                  file_put_contents($settings_file_path, $yaml);
              } catch (ParseException $exception) {
                  $this->writeLog('App/Controller/DashboardImagesController::saveDefaultLanguagesAction Unable to parse the YAML string: ' . $exception->getMessage());
                  return $this->render('error_request', array(
                    'translation' => $this->getTranslation(),
                    'authorization' => $this->checkAuthorization(),
                  ));
              }

              $store_logo_file = $request->files->get('input_store_logo_file');
              $store_logo_file->move($images_directory_path, $store_logo_tokenname);
            }

            return new RedirectResponse($this->generateUrl('dashboard_images'));
        } else {
            return $this->render('dashboard_authorization.twig', array(
              'translation' => $this->getTranslation(),
              'authorization' => $this->checkAuthorization(),
            ));
        }
    }
}
