<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/* ============= Запросы и ответы =============== */
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/* =============== Локализация ====================== */
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;


/* ==================== Include entities ================== */
use App\Entity\CommonLogs;

class MainController extends Controller
{
    /* ##################################################################################### */
    // Получение рабочей директории типа "/var/www/html/store/"
    /* ##################################################################################### */
    protected function getAppDir()
    {
        $app_dir = realpath($this->getParameter('kernel.root_dir').'/..') . DIRECTORY_SEPARATOR;
        return $app_dir;
    }

    /* ##################################################################################### */
    // Получение локализации для всего приложения
    /* ##################################################################################### */
    protected function getTranslation()
    {
        try {
            $translation = Yaml::parseFile($this->getAppDir() . 'translations/translation.ru.yaml');
        } catch (ParseException $exception) {
            $this->writeLog('Ошибка получения языкового пакета. Возможно пакет отсутствует. Текст ошибки: %s', $exception->getMessage());
            $translation = Yaml::parseFile($this->getAppDir() . 'translations/translation.ru.yaml');
        }

        return $translation;
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    private function getSettingsFilePath ()
    {
      $settings_file_path = $this->getAppDir() . '/config/settings.yaml';

      return $settings_file_path;
    }

    /* ##################################################################################### */
    // Метод получает дефолтные значения: шаг пагинации, имена файлов изображений и т.п.
    /* ##################################################################################### */
    protected function getSettings()
    {
        $settings_file_path = $this->getSettingsFilePath();

        try {
            $settings = Yaml::parseFile($settings_file_path);
        } catch (ParseException $exception) {
            $this->writeLog('App/Controller/MainController::getSettings [' . 'Unable to parse the YAML string: ' . $exception->getMessage() . ']');
        }

        return $settings;
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    protected function saveSettings($settings)
    {
      if ($this->checkAuthorization() == true) {
        $settings_file_path = $this->getSettingsFilePath();

        try {
          $yaml = Yaml::dump($settings);
          file_put_contents($settings_file_path, $yaml);
        } catch (ParseException $exception) {
          $this->writeLog('App/Controller/MainController::saveSettings Unable to parse the YAML string: ' . $exception->getMessage());
          return false;
        }

        return true;
      } else {
          $this->writeLog("App/Controller/MainController::saveSettings Authorization Error");
          return $this->render('error_access.twig', array(
            'translation' => $this->getTranslation()
          ));
      }
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function checkEmail($email)
    {
        $pattern = '/^(("[\w-\s]+")|([\w-]+(?:\.[\w-]+)*)|("[\w-\s]+")([\w-]+(?:\.[\w-]+)*))(@((?:[\w-]+\.)*\w[\w-]{0,66})\.([a-z]{2,6}(?:\.[a-z]{2})?)$)|(@\[?((25[0-5]\.|2[0-4][0-9]\.|1[0-9]{2}\.|[0-9]{1,2}\.))((25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\.){2}(25[0-5]|2[0-4][0-9]|1[0-9]{2}|[0-9]{1,2})\]?$)/i';
        if (preg_match($pattern, $email)) {
            return true;
        } else {
            return false;
        }
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function checkPhoneNumber($phone_number)
    {
        $pattern = '/^\d[\d\(\)\ -]{4,14}\d$/';
        if (preg_match($pattern, $phone_number)) {
            return true;
        } else {
            return false;
        }
    }

    /* ##################################################################################### */
    // добавить функции проверки имени и пароля
    /* ##################################################################################### */

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function generateRandomToken($token_length)
    {
        $random_token = openssl_random_pseudo_bytes($token_length);
        $random_token = bin2hex($random_token);

        return $random_token;
    }

    /* ##################################################################################### */
    // Получает список всех языков с флагом использования в панели управления или в магазине
    /* ##################################################################################### */
    protected function getAllLanguages()
    {
        $common_languages_repository = $this->getDoctrine()->getRepository('App:CommonLanguages');
        $common_languages_object = $common_languages_repository->findAll();

        if (count($common_languages_object) < 1) {
            return false;
        }

        $settings = $this->getSettings();
        $store_language = (int) $settings['store_language'];
        $dashboard_language = (int) $settings['dashboard_language'];

        foreach ($common_languages_object as $language) {
            $language_id = $language->getLanguageId();

            $languages[$language_id] = array(
              'id' => $language->getLanguageId(),
              'name' => $language->getLanguageName(),
              'code' => $language->getLanguageCode(),
            );

            if ($language->getLanguageId() == $store_language) {
              $languages[$language_id]['store_selected'] = true;
            } else {
              $languages[$language_id]['store_selected'] = false;
            }

            if ($language->getLanguageId() == $dashboard_language) {
              $languages[$language_id]['dashboard_selected'] = true;
            } else {
              $languages[$language_id]['dashboard_selected'] = false;
            }
        }

        return $languages;
    }

    /* ##################################################################################### */
    // Получает список всех валют из БД с флагом использования валюты в магазине
    /* ##################################################################################### */
    protected function getAllCurrencies()
    {
      $common_currencies_repository = $this->getDoctrine()->getRepository('App:CommonCurrencies');
      $common_currencies_object = $common_currencies_repository->findAll();

      if (count($common_currencies_object) < 1) {
          return false;
      }

      $settings = $this->getSettings();

      $store_currency = (int) $settings['store_currency'];

      foreach ($common_currencies_object as $currency) {
          $currency_id = $currency->getCurrencyId();

          $currencies[$currency_id] = array(
            'id' => $currency->getLanguageId(),
            'name' => $currency->getLanguageName(),
            'code' => $currency->getLanguageCode(),
            'symbol' => $currency->getCurrencySymbol()
          );

          if ($currency->getCurrencyId() == $store_currency) {
            $currencies[$currency_id]['store_selected'] = true;
          } else {
            $currencies[$currency_id]['store_selected'] = false;
          }
      }

      return $currencies;
    }

    /* ##################################################################################### */
    // Метод записывает все ошибки в таблицу БД
    /* ##################################################################################### */
    public function writeLog($log_data)
    {
        $common_logs = new CommonLogs();
        $request = Request::createFromGlobals();

        $user_agent = $request->server->get('HTTP_USER_AGENT');
        $remote_addr = $request->server->get('REMOTE_ADDR');
        $client_ip = $request->server->get('HTTP_CLIENT_IP');

        $common_logs->setLogData($user_agent . '<br>Remote Address: ' .  $remote_addr . '<br>Client IP: ' . $client_ip . '<br><b>' . $log_data . '</b>');
        $common_logs->setLogDatetime(date("Y-m-d H:i:s"));

        $em = $this->getDoctrine()->getManager();
        $em->persist($common_logs);
        $em->flush();

        return true;
    }

    public function writeLogAction(Request $request)
    {
        $log_data = $request->request->get('log_data');
        $result['result'] = $this->writeLog($log_data);

        return new JsonResponse($result);
    }
}
