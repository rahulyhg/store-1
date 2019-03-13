<?php
namespace App\Controller;

use App\Controller\MainDashboardController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
//use Symfony\Component\HttpFoundation\RedirectResponse;

class DashboardSettingsController extends MainDashboardController
{
    /* ##################################################################################### */
    // SETTINGS MAIN TEMPLATE
    /* ##################################################################################### */
    public function renderDashboardSettingsTemplateAction()
    {
        if ($this->checkAuthorization() == true) {
          $request = Request::createFromGlobals();
          return $this->render('dashboard_settings.twig', array(
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
    // Возвращает количество логов в БД для отображения в бадже collection
    /* ##################################################################################### */
    public function getLogsCountAction(Request $request)
    {
        if ($this->checkAuthorization() == true) {
          $logs_repository = $this->getDoctrine()->getRepository('App:CommonLogs');
          $logs_object = $logs_repository->findAll();

          $result['count'] = count($logs_object);

          return new JsonResponse($result);

        } else {
            return $this->render('dashboard_authorization.twig', [
              'translation' => $this->getTranslation(),
            ]);
        }
    }

}
