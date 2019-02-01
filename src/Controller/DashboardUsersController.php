<?php
namespace App\Controller;

use App\Controller\MainDashboardController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Filesystem\Filesystem;

use App\Entity\DashboardUsers;

class DashboardUsersController extends MainDashboardController
{
    /* ##################################################################################### */
    // ============================ RENDER DASHBOARD AUTHORS TEMPLATE ======================
    //
    /* ##################################################################################### */
    public function renderDashboardUsersTemplateAction()
    {
        if ($this->checkAuthorization() == true) {
            $request = Request::createFromGlobals();
            return $this->render('dashboard_users.twig', array(
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
    // Проверка зависимости автора от постов в блоге и документов в портфолио
    // если есть зависимости, то запрещать удаление автора
    /* ##################################################################################### */
    private function checkAuthorDependences($author_id)
    {
        $document_author_repository = $this->getDoctrine()->getRepository('App:PublicDocumentAuthor');
        $document_author_object = $document_author_repository->findByAuthorId($author_id);

        if (count($document_author_object) >= 1) {
            $portfolio_status = true;
        } else {
            $portfolio_status = false;
        }

        $post_author_repository = $this->getDoctrine()->getRepository('App:PublicPostAuthor');
        $post_author_object = $post_author_repository->findByAuthorId($author_id);

        if (count($post_author_object) >= 1) {
            $blog_status = true;
        } else {
            $blog_status = false;
        }

        if ($portfolio_status == true || $blog_status == true) {
            return true;
        } else {
            return false;
        }
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    private function getAuthors()
    {
        $authors_repository = $this->getDoctrine()->getRepository('App:DashboardAuthors');
        $authors_object = $authors_repository->findAll();

        foreach ($authors_object as $author) {
            $authors[$author->getAuthorId()] = array(
                'id' => $author->getAuthorId(),
                'name' => $author->getAuthorName(),
                'about' => $author->getAuthorAbout(),
                'email' => $author->getAuthorEmail(),
                'image' => $author->getAuthorImage(),
                'status' => $author->getAuthorStatus(),
                'dependence' => $this->checkAuthorDependences($author->getAuthorId()),
            );
        }

        return $authors;
    }

    public function getAuthorsAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $authors = $this->getAuthors();
            return new JsonResponse($authors);
        } else {
            $this->writeLog("Controller/Dashboard/Authors/getAuthors: Authorization Error");
            return $this->render('common_access_error.html.twig', array(
                'translation' => $this->getTranslation('access_error')
            ));
        }
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    private function changeAuthor($form_data)
    {
        $em = $this->getDoctrine()->getManager();
        $author_object = $em->getRepository('App:DashboardAuthors')->findOneByAuthorId($form_data['id']);

        $author_object->setAuthorStatus($form_data['status']);

        $em->flush();

        return true;
    }

    public function changeAuthorAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $form_data = $request->request->get('form_data');
            $result['result'] = $this->changeAuthor($form_data);
            return new JsonResponse($result);
        } else {
            $this->writeLog("Controller/Dashboard/Authors/changeAuthor: Authorization Error");
            return $this->render('common_access_error.html.twig', array(
                'translation' => $this->getTranslation('access_error')
            ));
        }
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    private function deleteAuthor($author_id)
    {
        $em = $this->getDoctrine()->getManager();

        $file_system = new Filesystem();
        $distination_path = $this->getAppDir() . "public/images/authors/";

        $author_object = $em->getRepository('App:DashboardAuthors')->findOneByAuthorId($author_id);
        $author_image = $author_object->getAuthorImage();

        if ($this->checkAuthorDependences($author_id) == true) {
           $path_author_image = $distination_path . $author_image;
           if (file_exists($path_author_image)) {
               $file_system->remove($path_author_image);
           }

           $em->remove($author_object);
           $em->flush();

           return true;
        } else {
        	return false;
        }
    }

    public function deleteAuthorAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $author_id = $request->request->get('author_id');
            $result['result'] = $this->deleteAuthor($author_id);
            return new JsonResponse($result);
        } else {
            $this->writeLog("Controller/Dashboard/Authors/deleteAuthor: Authorization Error");
            return $this->render('common_access_error.html.twig', array(
                'translation' => $this->getTranslation('access_error')
            ));
        }
    }
}
