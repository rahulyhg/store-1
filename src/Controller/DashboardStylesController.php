<?php
namespace App\Controller;

use App\Controller\MainDashboardController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

class DashboardStylesController extends MainDashboardController
{
    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function renderDashboardStylesTemplateAction()
    {
        $request = Request::createFromGlobals();
        $user_id = $request->cookies->get('user_id');
        if ($this->checkAuthorization() == true) {
            return $this->render('dashboard_styles.twig', array(
              'translation' => $this->getTranslation(),
              'authorization' => $this->checkAuthorization(),
              'profile' => $this->getProfile($user_id),
              'colors' => $this->getStyleColors(),
            ));
        } else {
            return $this->render('dashboard_authorization.twig', array(
              'translation' => $this->getTranslation(),
              'authorization' => $this->checkAuthorization(),
            ));
        }
    }

    private function getStylesFilePath()
    {
      $styles_file_path = $this->getAppDir() . 'public/styles/styles.yaml';

      return $styles_file_path;
    }

    /* ##################################################################################### */
    // Возвращает список кодов цветов и их названий для рендера палетки цветов
    /* ##################################################################################### */
    private function getStyleColors()
    {
      $colors_file_path = $this->getAppDir() . 'public/styles/colors.yaml';
      try {
          $colors = Yaml::parseFile($colors_file_path);
      } catch (ParseException $exception) {
          $this->writeLog('App/Controller/DashboardStylesController::getStyleColors > ParseException > ' . $exception->getMessage());
          return false;
      }

      foreach ($colors as $color_code => $color_name) {
        $palette[] = [
          'name' => $color_name,
          'code' => $color_code
        ];
      }

      return $palette;

    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    private function getStyles()
    {
      $styles_file_path = $this->getStylesFilePath();
      $dist_styles_file_path = $this->getAppDir() . 'public/styles/styles.dist.yaml';

      $fileSystem = new Filesystem();

      if (!$fileSystem->exists($styles_file_path)) {
        try {
          $fileSystem->copy($dist_styles_file_path, $styles_file_path, true);
        } catch (IOExceptionInterface $exception) {
          $this->writeLog('App/Controller/DashboardStylesController::getStyles > Error copy file > ' . $exception->getPath());
          return false;
        }
      }

      try {
          $styles = Yaml::parseFile($styles_file_path);
      } catch (ParseException $exception) {
          $this->writeLog('App/Controller/DashboardStylesController::getStyles > ParseException > ' . $exception->getMessage());
          return false;
      }

      return $styles;
    }

    public function getStylesAction(Request $request)
    {
      if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
        $styles = $this->getStyles();

        return new JsonResponse($styles);
      } else {
          $this->writeLog("App/Controller/DashboardStylesController::getStylesAction > Authorization Error");
          return $this->render('error_access.twig', array(
            'translation' => $this->getTranslation(),
          ));
      }
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    private function saveStyles($styles)
    {
      $styles_file_path = $this->getStylesFilePath();

      try {
        $yaml = Yaml::dump($styles);
        file_put_contents($styles_file_path, $yaml);
      } catch (ParseException $exception) {
        $this->writeLog('App/Controller/DashboardStylesController::saveStyles > Unable to parse the YAML string: ' . $exception->getMessage());
        return false;
      }

      return true;
    }

    public function saveStylesAction(Request $request)
    {
      if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
          $styles = $this->getStyles();
          $form = $request->request->get('form');

          if (strlen($form['header_background']) == 7) {
            $styles['header_background'] = $form['header_background'];
          }
          if (strlen($form['header_text']) == 7) {
            $styles['header_text'] = $form['header_text'];
          }
          if (strlen($form['footer_background']) == 7) {
            $styles['footer_background'] = $form['footer_background'];
          }
          if (strlen($form['footer_text']) == 7) {
            $styles['footer_text'] = $form['footer_text'];
          }

          $result['status'] = $this->saveStyles($styles);

          return new JsonResponse($result);
      } else {
          $this->writeLog("App/Controller/DashboardStylesController::getStylesAction > Authorization Error");
          return $this->render('error_access.twig', array(
            'translation' => $this->getTranslation(),
          ));
      }
    }
}
