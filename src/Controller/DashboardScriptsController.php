<?php
namespace App\Controller;

use App\Controller\MainDashboardController;

use Symfony\Component\HttpFoundation\Request;
//use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
//use Symfony\Component\HttpFoundation\RedirectResponse;

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
    public function getScriptAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $script_id = $request->request->get('script_id');
            $store_scripts_repository = $this->getDoctrine()->getRepository('App:StoreScripts');
            $store_script_object = $store_scripts_repository->findOneByScriptId($script_id);

            $script = array(
              'id' => $store_script_object->getScriptId(),
              'name' => $store_script_object->getScriptName(),
              'description' => $store_script_object->getScriptDescription(),
              'data' => $store_script_object->getScriptData()
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
            $public_scripts = new PublicScripts();
            $em = $this->getDoctrine()->getManager();

            $public_scripts->setScriptName($form['name']);
            $public_scripts->setScriptDescription($form['description']);
            $public_scripts->setScriptData($form['data']);

            $em->persist($public_scripts);
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
            $script_object = $em->getRepository('App:PublicScripts')->findOneByScriptId($form['id']);

            $script_object->setScriptName($form['name']);
            $script_object->setScriptDescription($form['description']);
            $script_object->setScriptData($form['data']);
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
            $em = $this->getDoctrine()->getManager();
            $seo_object = $em->getRepository('App:PublicSeo')->findOneBySeoId($seo_id);

            $em->remove($seo_object);
            $em->flush();

            return true;
        } else {
            $this->writeLog("Controller/Dashboard/Logs/getLogs: Authorization Error");
            return $this->render('error_access.twig', array(
              'translation' => $this->getTranslation()
            ));
        }
    }
}
