<?php
namespace App\Controller;

use App\Controller\MainDashboardController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

//use App\Entity\DashboardAuthors;

class DashboardDashboardController extends MainDashboardController
{
    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function renderDashboardDashboardTemplateAction() {
      if ($this->checkAuthorization() == true) {
          //$request = Request::createFromGlobals();
          return $this->render('dashboard_dashboard.html.twig', array(
            'translation' => $this->getTranslation('dashboard'),
            //'profile' => $this->getProfile($request->cookies->get('author_id')),

          ));
      } else {
          return $this->render('dashboard_authorization.html.twig', array(
            'translation' => $this->getTranslation('authorization')
          ));
      }
    }
}
