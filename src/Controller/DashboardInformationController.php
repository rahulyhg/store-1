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
        if ($this->checkAuthorization() == true) {
            $fileSystem = new Filesystem();
            $information_file_path = $this->getAppDir() . '/public/content/information.html';

            try {
                //$fileSystem->mkdir(sys_get_temp_dir().'/'.random_int(0, 1000));

            } catch (IOExceptionInterface $exception) {
                $this->writeLog('App/Controller/DashboardInformationController::getInformationAction & Error read file: ' . $exception->getPath());
            }

            // get contents of a file into a string
            $filename = "/usr/local/something.txt";
            $handle = fopen($filename, "r");
            $contents = fread($handle, filesize($filename));
            fclose($handle);

            return new JsonResponse();
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
