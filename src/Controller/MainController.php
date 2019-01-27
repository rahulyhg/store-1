<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/* ============= Запросы и ответы =============== */
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

/* =============== Локализация ====================== */
use Symfony\Component\Translation\Translator;
use Symfony\Component\Translation\TranslatorInterface;
use Symfony\Component\Translation\Loader\YamlFileLoader;
use Symfony\Component\Yaml\Yaml;


/* ==================== Include entities ================== */
use App\Entity\CommonLogs;

class MainController extends Controller
{
    /* ##################################################################################### */
    // Получение рабочей директории "/var/www/html/store/"
    /* ##################################################################################### */
    protected function getAppDir()
    {
        $app_dir = realpath($this->getParameter('kernel.root_dir').'/..') . DIRECTORY_SEPARATOR;
        return $app_dir;
    }

    /* ##################################################################################### */
    // Получение локализации для всего приложения
    /* ##################################################################################### */
    //protected function getTranslation(TranslatorInterface $translator)
    protected function getTranslation()
    {
        $translation = Yaml::parseFile($this->getAppDir() . 'translations/translation.ru.yaml');

        return $translation;
    }

    /* ##################################################################################### */
    // Метод получает дефолтные значения: шаг пагинации, имена файлов изображений и т.п.
    /* ##################################################################################### */
    public function getStoreSettings()
    {
        return $settings;
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
    //
    /* ##################################################################################### */
    public function generateRandomToken($token_length)
    {
        $random_token = openssl_random_pseudo_bytes($token_length);
        $random_token = bin2hex($random_token);

        return $random_token;
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
