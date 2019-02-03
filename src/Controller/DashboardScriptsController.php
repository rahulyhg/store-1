<?php
namespace App\Controller;

use App\Controller\MainDashboardController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

use App\Entity\DashboardAnalytics;

class DashboardScriptsController extends MainDashboardController
{
    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function renderDashboardScriptsTemplateAction()
    {
        $request = Request::createFromGlobals();
        $user_id = $request->cookies->get('user_id');
        if ($this->checkAuthorization() == true) {
            return $this->render('dashboard_scripts.twig', array(
            'translation' => $this->getTranslation(),
            'authorization' => $this->checkAuthorization(),
            'profile' => $this->getProfile($user_id),

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
    public function getScripts()
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $store_scripts_repository = $this->getDoctrine()->getRepository('App:StoreScripts');
            $store_scripts_object = $store_scripts_repository->findAll();

            foreach ($store_scripts_object as $script) {
                $scripts[] = array(
                  'id' => $script->getScriptId(),
                  'name' => $script->getScriptName(),
                  'description' => $script->getScriptDescription(),
                  'data' => $script->getScriptData(),
                );
            }

            return new JsonResponse($scripts);
        } else {
            $this->writeLog("Controller/Dashboard/Logs/getLogs: Authorization Error");
            return $this->render('error_access.twig', array(
              'translation' => $this->getTranslation()
          ));
        }
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function getAnalytics()
    {
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function addAnalytics()
    {
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function editAnalytics()
    {
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function deleteAnalytics()
    {
    }
}
