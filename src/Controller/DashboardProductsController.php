<?php
namespace App\Controller;

use App\Controller\MainDashboardController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

//use App\Entity\DashboardAuthors;

class DashboardProductsController extends MainDashboardController
{
    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function renderDashboardProductsTemplateAction()
    {
        if ($this->checkAuthorization() == true) {
            $request = Request::createFromGlobals();
            return $this->render('dashboard_products.twig', array(
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
}
