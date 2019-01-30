<?php
namespace App\Controller;

use App\Controller\MainDashboardController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

//use App\Entity\DashboardAuthors;

class DashboardSettingsController extends MainDashboardController
{
    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function renderDashboardSettingsTemplateAction()
    {
        if ($this->checkAuthorization() == true) {
            $request = Request::createFromGlobals();
            return $this->render('dashboard_settings.twig', array(
            'translation' => $this->getTranslation(),
            'profile' => $this->getProfile($request->cookies->get('user_id')),

          ));
        } else {
            return $this->render('dashboard_authorization.twig', array(
            'translation' => $this->getTranslation()
          ));
        }
    }
}
