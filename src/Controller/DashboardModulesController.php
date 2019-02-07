<?php
namespace App\Controller;

use App\Controller\MainDashboardController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Entity\StoreModules;

class DashboardModulesController extends MainDashboardController
{
    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function renderDashboardModulesTemplateAction()
    {
        $request = Request::createFromGlobals();
        $user_id = $request->cookies->get('user_id');
        if ($this->checkAuthorization() == true) {
            return $this->render('dashboard_modules.twig', array(
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
    public function getModulesAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $store_modules_repository = $this->getDoctrine()->getRepository('App:StoreModules');
            $store_modules_object = $store_modules_repository->findAll();

            if (count($store_modules_object) < 1) {
                $modules['status'] = false;
                return new JsonResponse($modules);
            }

            foreach ($store_modules_object as $module) {
                $modules[] = array(
                  'id' => $module->getModuleId(),
                  'name' => $module->getModuleName(),
                  'data' => $module->getModuleData(),
                  'status' => $module->getModuleStatus(),
                );
            }

            return new JsonResponse($modules);
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
    public function getModuleAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $module_id = $request->request->get('module_id');
            $store_modules_repository = $this->getDoctrine()->getRepository('App:StoreModules');
            $store_module_object = $store_modules_repository->findOneByModuleId($module_id);

            $module = array(
              'id' => $store_module_object->getModuleId(),
              'name' => $store_module_object->getModuleName(),
              'data' => $store_module_object->getModuleData(),
              'status' => $store_module_object->getModuleStatus()
            );

            return new JsonResponse($module);
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
    public function addModuleAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $form = $request->request->get('form');
            $store_modules = new StoreModules();
            $em = $this->getDoctrine()->getManager();

            $store_modules->setModuleName($form['name']);
            $store_modules->setModuleData($form['data']);
            $store_modules->setModuleStatus($form['status']);

            $em->persist($store_modules);
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
    public function editModuleAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $form = $request->request->get('form');
            $em = $this->getDoctrine()->getManager();
            $module_object = $em->getRepository('App:StoreModules')->findOneByModuleId($form['id']);

            $module_object->setModuleName($form['name']);
            $module_object->setModuleData($form['data']);
            $module_object->setModuleStatus($form['status']);

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
    public function deleteModuleAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $module_id = $request->request->get('module_id');
            $em = $this->getDoctrine()->getManager();
            $module_object = $em->getRepository('App:StoreModules')->findOneByModuleId($module_id);

            $em->remove($module_object);
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
