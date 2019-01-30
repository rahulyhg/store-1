<?php
namespace App\Controller;

use App\Controller\MainDashboardController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

//use App\Entity\DashboardAuthors;

class DashboardAnalyticsController extends MainDashboardController
{
    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function renderDashboardAnalyticsTemplateAction()
    {
        if ($this->checkAuthorization() == true) {
            $request = Request::createFromGlobals();
            return $this->render('dashboard_analytics.twig', array(
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
