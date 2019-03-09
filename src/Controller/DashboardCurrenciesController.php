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

use App\Entity\CommonCurrencies;

class DashboardCurrenciesController extends MainDashboardController
{
    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function renderDashboardCurrenciesTemplateAction()
    {
        $request = Request::createFromGlobals();
        $user_id = $request->cookies->get('user_id');
        if ($this->checkAuthorization() == true) {
            return $this->render('dashboard_currencies.twig', array(
              'translation' => $this->getTranslation(),
              'authorization' => $this->checkAuthorization(),
              'profile' => $this->getProfile($user_id),
              'currencies' => $this->getAllCurrencies(),
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
    private function checkCurrencyDependence($currency_id)
    {
      $settings_file_path = $this->getAppDir() . '/config/settings.yaml';
      try
        $settings = Yaml::parseFile($settings_file_path);
      } catch (ParseException $exception) {
        $this->writeLog('App/Controller/DashboardCurrenciesController::checkCurrencyDependence Unable to parse the YAML string: ' . $exception->getMessage());
        return $this->render('error_parse.twig', array(
          'translation' => $this->getTranslation(),
          'authorization' => $this->checkAuthorization(),
        ));
      }

      $store_currency = (int) $settings['store_currency'];

      if ($currency_id == $store_currency) {
        return true;
      }

      return false;
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function getCurrenciesAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $common_currencies_repository = $this->getDoctrine()->getRepository('App:CommonCurrencies');
            $common_currencies_object = $common_currencies_repository->findAll();

            if (count($common_currencies_object) < 1) {
                $currencies['status'] = false;
                return new JsonResponse($currencies);
            }

            foreach ($common_currencies_object as $currency) {
                $currencies[] = array(
                  'id' => $currency->getCurrencyId(),
                  'name' => $currency->getCurrencyName(),
                  'code' => $currency->getCurrencyCode(),
                  'symbol' => $currency->getCurrencySymbol(),
                  'dependence' => $this->checkCurrencyDependence($currency->getCurrencyId()),
                );
            }

            return new JsonResponse($currencies);
        } else {
            $this->writeLog("App/Controller/DashboardCurrenciesController::getCurrenciesAction Authorization Error");
            return $this->render('error_access.twig', array(
              'translation' => $this->getTranslation(),
              'authorization' => $this->checkAuthorization(),
          ));
        }
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function getCurrencyAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $currency_id = $request->request->get('currency_id');
            $common_currencies_repository = $this->getDoctrine()->getRepository('App:CommonCurrencies');
            $store_currency_object = $common_currencies_repository->findOneByCurrencyId($currency_id);

            $currency = array(
              'id' => $store_currency_object->getCurrencyId(),
              'name' => $store_currency_object->getCurrencyName(),
              'code' => $store_currency_object->getCurrencyCode(),
            );

            return new JsonResponse($currency);
        } else {
            $this->writeLog("App/Controller/DashboardCurrenciesController::getCurrencyAction Authorization Error");
            return $this->render('error_access.twig', array(
              'translation' => $this->getTranslation(),
              'authorization' => $this->checkAuthorization(),
            ));
        }
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function addCurrencyAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $form = $request->request->get('form');
            $common_currencies = new CommonCurrencies();
            $em = $this->getDoctrine()->getManager();

            $common_currencies->setCurrencyName($form['name']);
            $common_currencies->setCurrencyCode($form['code']);
            $common_currencies->setCurrencyCode($form['symbol']);

            $em->persist($common_currencies);
            $em->flush();

            $result['result'] = true;

            return new JsonResponse($result);
        } else {
            $this->writeLog("App/Controller/DashboardCurrenciesController::addCurrencyAction Authorization Error");
            return $this->render('error_access.twig', array(
              'translation' => $this->getTranslation(),
              'authorization' => $this->checkAuthorization(),
            ));
        }
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function editCurrencyAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $form = $request->request->get('form');
            $em = $this->getDoctrine()->getManager();
            $currency_object = $em->getRepository('App:CommonCurrencies')->findOneByCurrencyId($form['id']);

            $currency_object->setCurrencyName($form['name']);
            $currency_object->setCurrencyCode($form['code']);

            $em->flush();

            $result['result'] = true;

            return new JsonResponse($result);
        } else {
            $this->writeLog("App/Controller/DashboardCurrenciesController::editCurrencyAction Authorization Error");
            return $this->render('error_access.twig', array(
              'translation' => $this->getTranslation()
            ));
        }
    }

    /* ##################################################################################### */
    // Подумать над тем, чтоб удалять соответствующие файлы языка и meta-данных
    /* ##################################################################################### */
    public function deleteCurrencyAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $currency_id = $request->request->get('currency_id');
            $em = $this->getDoctrine()->getManager();
            $currency_object = $em->getRepository('App:CommonCurrencies')->findOneByCurrencyId($currency_id);

            $em->remove($currency_object);
            $em->flush();

            $result['result'] = true;

            return new JsonResponse($result);
        } else {
            $this->writeLog("App/Controller/DashboardCurrenciesController::deleteCurrencyAction Authorization Error");
            return $this->render('error_access.twig', array(
              'translation' => $this->getTranslation()
            ));
        }
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function saveDefaultCurrenciesAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $form = $request->request->get('form');

            $settings_file_path = $this->getAppDir() . '/config/settings.yaml';

            try {
                $settings = Yaml::parseFile($settings_file_path);
                $old_currency = (int) $settings['dashboard_currency'];

                $settings['dashboard_currency'] = (int) $form['dashboard'];
                $settings['store_currency'] = (int) $form['store'];

                $yaml = Yaml::dump($settings);
                file_put_contents($settings_file_path, $yaml);
            } catch (ParseException $exception) {
                $this->writeLog('App/Controller/DashboardCurrenciesController::saveDefaultCurrenciesAction Unable to parse the YAML string: ' . $exception->getMessage());
                $result['error'] = 'Unable to parse the YAML string: ' . $exception->getMessage();
                $result['result'] = false;
            }

            $result['result'] = true;

            return new JsonResponse($result);
        } else {
            $this->writeLog("App/Controller/DashboardCurrenciesController::saveDefaultCurrenciesAction Authorization Error");
            return $this->render('error_access.twig', array(
              'translation' => $this->getTranslation()
            ));
        }
    }
}
