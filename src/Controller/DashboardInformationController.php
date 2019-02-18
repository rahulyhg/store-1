<?php
namespace App\Controller;

use App\Controller\MainDashboardController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class DashboardInformationController extends MainDashboardController
{
    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function renderDashboardInformationTemplateAction()
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
    //
    /* ##################################################################################### */
    public function getInformationAction(Request $request)
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
