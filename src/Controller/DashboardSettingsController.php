<?php
namespace App\Controller;

use App\Controller\MainDashboardController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

use App\Entity\CommonSettings;

class DashboardSettingsController extends MainDashboardController
{
    /* ##################################################################################### */
    // SETTINGS MAIN TEMPLATE
    /* ##################################################################################### */
    public function renderDashboardSettingsMainTemplateAction()
    {
        if ($this->checkAuthorization() == true) {
          $request = Request::createFromGlobals();
          return $this->render('dashboard_settings_main.twig', array(
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
    // SETTINGS GENERAL TEMPLATE
    /* ##################################################################################### */
    public function renderDashboardSettingsGeneralTemplateAction()
    {
        if ($this->checkAuthorization() == true) {
          $request = Request::createFromGlobals();
          return $this->render('dashboard_settings_general.twig', array(
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
    public function saveSettingsAction(Request $request)
    {
        if ($this->checkAuthorization() == true) {
          // yaml

        } else {
            return $this->render('dashboard_authorization.twig', [
              'translation' => $this->getTranslation(),
              'authorization' => $this->checkAuthorization(),
            ]);
        }
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function saveStorePaginationStepAction(Request $request)
    {
      if ($this->checkAuthorization() == true) {


      } else {
          $this->writeLog('App/Controller/DashboardSettingsController::saveStorePaginationStepAction > Authorization Error');
          return $this->render('dashboard_authorization.twig', [
            'translation' => $this->getTranslation(),
          ]);
      }
    }

}
