<?php
namespace App\Controller;

use App\Controller\MainDashboardController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;

use App\Entity\DashboardInbox;

class DashboardInboxController extends MainDashboardController
{
    /* ##################################################################################### */
    // ============================ RENDER DASHBOARD INBOX TEMPLATE =================
    //
    /* ##################################################################################### */
    public function renderDashboardInboxTemplateAction()
    {
        if ($this->checkAuthorization() == true) {
            $request = Request::createFromGlobals();
            return $this->render('dashboard_inbox.twig', array(
              'translation' => $this->getTranslation(),
              'profile' => $this->getProfile($request->cookies->get('user_id')),
              'messages' => $this->getMessages(),
            ));
        } else {
            return $this->render('dashboard_authorization.twig', array(
              'translation' => $this->getTranslation()
            ));
        }
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    private function getMessages()
    {
        $inbox_repository = $this->getDoctrine()->getRepository('App:DashboardInbox');
        $inbox_object = $inbox_repository->findAll();

        $messages = false;

        foreach ($inbox_object as $message) {
            $messages[] = array(
              'id' => $message->getMessageId(),
              'name' => $message->getMessageName(),
              'email' => $message->getMessageEmail(),
              'text' => $message->getMessageText(),
              'datetime' => $message->getMessageDatetime()
            );
        }

        return $messages;
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    private function deleteMessage($message_id)
    {
        $em = $this->getDoctrine()->getManager();
        $message_object = $em->getRepository('App:DashboardInbox')->findOneByMessageId($message_id);

        $em->remove($message_object);
        $em->flush();

        return true;
    }

    public function deleteMessageAction($message_id)
    {
        if ($this->checkAuthorization() == true) {
            $this->deleteMessage($message_id);
            return new RedirectResponse($this->generateUrl('dashboard_inbox'));
        } else {
            $this->writeLog("Controller/Dashboard/Inbox/deleteMessage: Authorization Error");
            return $this->render('error_access.twig', array(
              'translation' => $this->getTranslation()
            ));
        }
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function sendContactUsMessageAction(Request $request)
    {
        $dashboard_inbox = new DashboardInbox();
        $em = $this->getDoctrine()->getManager();

        $form_data = $request->request->get('form_data');

        if (strlen($form_data['name']) >= 2 && $this->checkEmail($form_data['email']) == true && strlen($form_data['text']) >= 10) {
            $dashboard_inbox->setMessageName($form_data['name']);
            $dashboard_inbox->setMessageEmail($form_data['email']);
            $dashboard_inbox->setMessageText($form_data['text']);
            $dashboard_inbox->setMessageDatetime(date("Y-m-d H:i:s"));

            $em->persist($dashboard_inbox);
            $em->flush();

            $result['result'] = true;
        } else {
            $result['result'] = false;
        }

        return new JsonResponse($result);
    }
}
