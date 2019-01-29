<?php
namespace App\Controller;

use App\Controller\MainDashboardController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Filesystem\Filesystem;

class DashboardProfileController extends MainDashboardController
{
    /* ##################################################################################### */
    // ============================ RENDER DASHBOARD PROFILE TEMPLATE ======================
    //
    /* ##################################################################################### */
    public function renderDashboardProfileAction()
    {
        if ($this->checkAuthorization() == true) {
            $request = Request::createFromGlobals();
            return $this->render('dashboard_profile.twig', array(
              'translation' => $this->getTranslation(),
              'profile' => $this->getProfile($request->cookies->get('user_id')),
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
    public function editProfileAction(Request $request)
    {
        if ($this->checkAuthorization() == false) {
            $this->writeLog("Controller/Dashboard/Profile/editProfile: Authorization Error");
            return $this->render('error_access.twig', array(
                'translation' => $this->getTranslation()
            ));
        }

        $em = $this->getDoctrine()->getManager();
        $author_id = $this->getProfile($request->request->get('input_author_id'));
        $author_object = $em->getRepository('App:DashboardAuthors')->findOneByAuthorId($author_id);



        $file_system = new Filesystem();
        $path_authors_images = $this->getAppDir() . '/public/images/authors/';

        $image_token_name = $request->request->get('image_token_name');


        $author_object->setAuthorName($request->request->get('input_author_name'));
        $author_object->setAuthorAbout($request->request->get('input_author_about'));
        $author_object->setAuthorEmail($request->request->get('input_author_email'));

        if (strcmp($author_object->getAuthorImage(), $image_token_name) !== 0 && strlen($image_token_name) == 32) {
            $x_o = $request->request->get('image_x1');
            $y_o = $request->request->get('image_y1');
            $w_o = $request->request->get('image_w');
            $h_o = $request->request->get('image_h');

            $author_image_file = $request->files->get('input_author_image_file');
            $author_image_file->move($path_authors_images, $image_token_name);
            $this->cropImage($path_authors_images . $image_token_name, $x_o, $y_o, $w_o, $h_o);

            $path_old_image_file = $path_authors_images . $author_object->getAuthorImage();

            if (file_exists($path_old_image_file)) {
                $file_system->remove($path_old_image_file);
            }

            $author_object->setAuthorImage($image_token_name);
        }

        $author_object->setAuthorLogin($request->request->get('input_author_login'));

        if ($request->request->get('input_author_password') && strlen($request->request->get('input_author_password')) >= 6) {
            $author_object->setAuthorPassword($this->getPasswordHash($request->request->get('input_author_password')));
        }

        $author_object->setAuthorStatus(1);

        $em->flush();

        return new RedirectResponse($this->generateUrl('dashboard_profile'));
    }
}
