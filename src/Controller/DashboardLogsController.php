<?php
namespace App\Controller;

use App\Controller\MainDashboardController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

use App\Entity\CommonLogs;

class DashboardLogsController extends MainDashboardController
{
    /* ##################################################################################### */
    // ============================ RENDER DASHBOARD LOGS TEMPLATE =================
    //
    /* ##################################################################################### */
    public function renderDashboardLogsTemplateAction()
    {
        if ($this->checkAuthorization() == true) {
            $request = Request::createFromGlobals();
            return $this->render('dashboard_logs.twig', array(
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
    private function getLogs()
    {
        $logs_repository = $this->getDoctrine()->getRepository('App:CommonLogs');
        $logs_object = $logs_repository->findAll();

        if (count($logs_object) == 0) {
            return $logs = false;
        }

        foreach ($logs_object as $log) {
            $logs[] = array(
                'id' => $log->getLogId(),
                'data' => $log->getLogData(),
                'datetime' => $log->getLogDatetime()
            );
        }
        $logs = array_reverse($logs);
        return $logs;
    }

    public function getLogsAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $logs = $this->getLogs();
            return new JsonResponse($logs);
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
    private function deleteLog($log_id)
    {
        $em = $this->getDoctrine()->getManager();
        $common_log = $em->getRepository('App:CommonLogs')->findOneByLogId($log_id);

        $em->remove($common_log);
        $em->flush();

        return true;
    }

    public function deleteLogAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $log_id = $request->request->get('log_id');
            $result['result'] = $this->deleteLog($log_id);
            return new JsonResponse($result);
        } else {
            $this->writeLog("Controller/Dashboard/Logs/deleteLog: Authorization Error");
            return $this->render('error_access.twig', array(
                'translation' => $this->getTranslation()
            ));
        }
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    private function deleteLogs()
    {
        $em = $this->getDoctrine()->getManager();
        $common_logs = $em->getRepository('App:CommonLogs')->findAll();

        foreach ($common_logs as $log) {
            $em->remove($log);
            $em->flush();
        }

        return true;
    }

    public function deleteLogsAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $result['result'] = $this->deleteLogs();
            return new JsonResponse($result);
        } else {
            $this->writeLog("Controller/Dashboard/Logs/deleteLogs: Authorization Error");
            return $this->render('error_access.twig', array(
                'translation' => $this->getTranslation()
            ));
        }
    }
}
