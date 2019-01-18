<?php
namespace App\Controller;

use App\Controller\MainController;

/* ============= Запросы и ответы =============== */
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;

class MainStoreController extends MainController
{
    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    private function getPagesMetaData($page_name)
    {
        $pages_repository = $this->getDoctrine()->getRepository('App:PublicPages');
        $page_object = $pages_repository->findOneByPageName($page_name);

        $meta = array(
            'title' => $page_object->getPageMetatitle(),
           'description' => $page_object->getPageMetadescription(),
           'keywords' => $page_object->getPageMetakeywords()
        );

        return $meta;
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    private function getInformations()
    {
        $informations_filepath = $this->getAppDir() . 'translations/defaults/informations.html';
        $informations = file_get_contents($informations_filepath);

        return $informations;
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    private function getModuleLinks()
    {
        $module_links_repository = $this->getDoctrine()->getRepository('App:PublicModuleLinks');
        $module_links_object = $module_links_repository->findAll();

        if (count($module_links_object) == 0) {
          return $links = false;
        }

        foreach ($module_links_object as $link) {
            $links[] = [
                'name' => $link->getLinkName(),
                'description' => $link->getLinkDescription(),
                'path' => $link->getLinkPath()
            ];
        }

        return $links;
    }

    /* ##################################################################################### */
    // Метод получает код аналитики сайта для вывода в <head></head>
    // Используются таблицы: public_seo
    // Возвращается переменная типа string с html разметкой, для вывода требуюется twig фильтр {{ ... | raw }}
    /* ##################################################################################### */
    private function getAnalyticsList()
    {
        $analytics_repository = $this->getDoctrine()->getRepository('App:PublicSeo');
        $analytics_object = $analytics_repository->findBySeoName('analytics');

        if (count($analytics_object) == 0) {
          return $analytics_list = false;
        }

        foreach ($analytics_object as $analytics) {
            $analytics_list[] = $analytics->getSeoData();
        }

        return $analytics_list;
    }

    /* ##################################################################################### */
    // Метод получает код рекламного банера для размещения на страницах
    // Используются таблицы: public_seo
    // Возвращается переменная типа string с html разметкой, для вывода требуюется twig фильтр {{ ... | raw }}
    /* ##################################################################################### */
    private function getAdvertisements()
    {
        $advertisements_repository = $this->getDoctrine()->getRepository('App:PublicSeo');
        $advertisements_object = $advertisements_repository->findBySeoName('advertisement');

        if (count($advertisements_object) == 0) {
          return $advertisements = false;
        }

        foreach ($advertisements_object as $advertisement) {
            $advertisements[] = $advertisement->getSeoData();
        }

        return $advertisements;
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    protected function getPublicContent($page_name, $custom_meta)
    {
        $content = array(
           'informations' => $this->getInformations(),
           'advertisements' => $this->getAdvertisements(),
           'analytics_list' => $this->getAnalyticsList(),
           'module_links' => $this->getModuleLinks(),
        );

        if ($page_name !== false) {
            $content['meta'] = $this->getPagesMetaData($page_name);
        } else {
            $content['meta'] = $custom_meta;
        }

        return $content;
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function sendMessageAction(Request $request) {

       return new JsonResponse();
    }
}
