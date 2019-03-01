<?php
namespace App\Controller;

use App\Controller\MainDashboardController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\RedirectResponse;

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
    public function getMetaAction()
    {

    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function saveMetaAction()
    {

    }

}
