<?php
namespace App\Controller;

use App\Controller\MainDashboardController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

use App\Entity\DashboardAuthors;

class DashboardRegistrationController extends MainDashboardController
{
    /* ##################################################################################### */
    // ============================ RENDER DASHBOARD REGISTRATION TEMPLATE =================
    //
    /* ##################################################################################### */
    public function renderDashboardRegistrationAction()
    {
        $session = new Session();
        $session->invalidate();

        return $this->render('dashboard_registration.html.twig', array(
          'translation' => $this->getTranslation(),
        ));
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    private function cropImage($image, $x_o, $y_o, $w_o, $h_o)
    {
        if (($x_o < 0) || ($y_o < 0) || ($w_o <= 0) || ($h_o <= 0)) {
            //echo "Некорректные входные параметры";
            return false;
        }

        list($w_i, $h_i, $type) = getimagesize($image);
        $types = array('', 'gif', 'jpeg', 'png');
        $ext = $types[$type];

        if ($ext) {
            $func = 'imagecreatefrom' . $ext;
            $img_i = $func($image);
        } else {
            //echo 'Некорректное изображение';
            return false;
        }

        if ($x_o + $w_o > $w_i) {
            $w_o = $w_i - $x_o;
        }
        if ($y_o + $h_o > $h_i) {
            $h_o = $h_i - $y_o;
        }

        $img_o = imagecreatetruecolor($w_o, $h_o);

        imagecopy($img_o, $img_i, 0, 0, $x_o, $y_o, $w_o, $h_o);

        $func = 'image'.$ext;

        $func($img_o, $image);
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function registerUserAction(Request $request)
    {
        $session = new Session();
        $session->invalidate();

        $path_authors_images = $this->getAppDir() . '/public/images/users/';

        $image_token_name = $request->request->get('image_token_name');

        $em = $this->getDoctrine()->getManager();
        $dashboard_authors = new DashboardAuthors();

        if (strcmp($image_token_name, '0') !== 0 && strlen($image_token_name) == 32) {
            $x_o = $request->request->get('image_x1');
            $y_o = $request->request->get('image_y1');
            $w_o = $request->request->get('image_w');
            $h_o = $request->request->get('image_h');

            $author_image_file = $request->files->get('input_author_image_file');
            $author_image_file->move($path_authors_images, $image_token_name);
            $this->cropImage($path_authors_images . $image_token_name, $x_o, $y_o, $w_o, $h_o);
        } else {
            $image_token_name = 0;
        }

        $dashboard_authors->setAuthorName($request->request->get('input_author_name'));
        $dashboard_authors->setAuthorAbout($request->request->get('input_author_about'));
        $dashboard_authors->setAuthorEmail($request->request->get('input_author_email'));
        $dashboard_authors->setAuthorImage($image_token_name);
        $dashboard_authors->setAuthorLogin($request->request->get('input_author_login'));
        $dashboard_authors->setAuthorPassword($this->getPasswordHash($request->request->get('input_author_password')));
        $dashboard_authors->setAuthorStatus(0);

        $em->persist($dashboard_authors);
        $em->flush();

        return new RedirectResponse($this->generateUrl('dashboard_dashboard'));
    }
}
