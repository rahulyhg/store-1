<?php
namespace App\Controller;

use App\Controller\MainDashboardController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Entity\StoreScripts;

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
    public function getScriptsAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $store_scripts_repository = $this->getDoctrine()->getRepository('App:StoreScripts');
            $store_scripts_object = $store_scripts_repository->findAll();

            if (count($store_scripts_object) < 1) {
                $scripts['status'] = false;
                return new JsonResponse($scripts);
            }

            foreach ($store_scripts_object as $script) {
                $scripts[] = array(
                  'id' => $script->getScriptId(),
                  'name' => $script->getScriptName(),
                  'data' => $script->getScriptData(),
                  'status' => $script->getScriptStatus(),
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
    public function getScriptAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $script_id = $request->request->get('script_id');
            $store_scripts_repository = $this->getDoctrine()->getRepository('App:StoreScripts');
            $store_script_object = $store_scripts_repository->findOneByScriptId($script_id);

            $script = array(
              'id' => $store_script_object->getScriptId(),
              'name' => $store_script_object->getScriptName(),
              'data' => $store_script_object->getScriptData(),
              'status' => $store_script_object->getScriptStatus()
            );

            return new JsonResponse($script);
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
    public function addScriptAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $form = $request->request->get('form');
            $store_scripts = new StoreScripts();
            $em = $this->getDoctrine()->getManager();

            $store_scripts->setScriptName($form['name']);
            $store_scripts->setScriptData($form['data']);
            $store_scripts->setScriptStatus($form['status']);

            $em->persist($store_scripts);
            $em->flush();

            $result['result'] = true;

            return new JsonResponse($result);
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
    public function editScriptAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $form = $request->request->get('form');
            $em = $this->getDoctrine()->getManager();
            $script_object = $em->getRepository('App:StoreScripts')->findOneByScriptId($form['id']);

            $script_object->setScriptName($form['name']);
            $script_object->setScriptData($form['data']);
            $script_object->setScriptStatus($form['status']);

            $em->flush();

            $result['result'] = true;

            return new JsonResponse($result);
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
    public function deleteScriptAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $script_id = $request->request->get('script_id');
            $em = $this->getDoctrine()->getManager();
            $script_object = $em->getRepository('App:StoreScripts')->findOneByScriptId($script_id);

            $em->remove($script_object);
            $em->flush();

            $result['result'] = true;

            return new JsonResponse($result);
        } else {
            $this->writeLog("Controller/Dashboard/Logs/getLogs: Authorization Error");
            return $this->render('error_access.twig', array(
              'translation' => $this->getTranslation()
            ));
        }
    }
}
