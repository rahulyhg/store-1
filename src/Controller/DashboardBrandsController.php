<?php
namespace App\Controller;

use App\Controller\MainDashboardController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Cookie;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;

use App\Entity\StoreBrands;

class DashboardBrandsController extends MainDashboardController
{
    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function renderDashboardBrandsTemplateAction()
    {
        $request = Request::createFromGlobals();
        $user_id = $request->cookies->get('user_id');
        if ($this->checkAuthorization() == true) {
            return $this->render('dashboard_brands.twig', array(
              'translation' => $this->getTranslation(),
              'authorization' => $this->checkAuthorization(),
              'profile' => $this->getProfile($user_id),
              'brands' => $this->getBrandsAction()
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
    private function checkBrandDependence($brand_id)
    {
      /*$settings = $this->getSettings();

      $store_currency = (int) $settings['store_currency'];

      if ($currency_id == $store_currency) {
        return true;
      }*/

      return false;
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
    private function getBrandsImagesDirectoryPath()
    {
      $brands_images_directory = $this->getAppDir() . 'public/images/brands/';

      return $brands_images_directory;
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function getBrandsAction()
    {
        if ($this->checkAuthorization() == true) {
            $store_brands_repository = $this->getDoctrine()->getRepository('App:StoreBrands');
            $store_brands_object = $store_brands_repository->findAll();

            if (count($store_brands_object) < 1) {
                return false;
            }

            foreach ($store_brands_object as $brand) {
                $brands[] = array(
                  'id' => $brand->getBrandId(),
                  'name' => $brand->getBrandName(),
                  'description' => $brand->getBrandDescription(),
                  'image' => $brand->getBrandImage(),
                  'link' => $brand->getBrandLink(),
                  // Зависимость сделать только после создания управления продуктами
                  //'dependence' => $this->checkBrandDependence($brand->getBrandId()),
                );
            }

            return $brands;
        } else {
            $this->writeLog("App/Controller/DashboardBrandsController::getBrandsAction > Authorization Error");
            return $this->render('error_access.twig', array(
              'translation' => $this->getTranslation(),
              'authorization' => $this->checkAuthorization(),
            ));
        }
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function getBrandAction(Request $request)
    {
        if ($this->checkAuthorization() == true) {
            $brand_id = $request->request->get('brand_id');
            $common_currencies_repository = $this->getDoctrine()->getRepository('App:CommonCurrencies');
            $store_currency_object = $common_currencies_repository->findOneByCurrencyId($currency_id);

            $currency = array(
              'id' => $store_currency_object->getCurrencyId(),
              'name' => $store_currency_object->getCurrencyName(),
              'code' => $store_currency_object->getCurrencyCode(),
              'symbol' => $store_currency_object->getCurrencySymbol(),
            );

            return new JsonResponse($currency);
        } else {
            $this->writeLog("App/Controller/DashboardCurrenciesController::getCurrencyAction Authorization Error");
            return $this->render('error_access.twig', array(
              'translation' => $this->getTranslation(),
            ));
        }
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function addBrandAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $form = $request->request->get('form');
            $common_currencies = new CommonCurrencies();
            $em = $this->getDoctrine()->getManager();

            $common_currencies->setCurrencyName($form['name']);
            $common_currencies->setCurrencyCode($form['code']);
            $common_currencies->setCurrencySymbol($form['symbol']);

            $em->persist($common_currencies);
            $em->flush();

            // Image CROP
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

            $result['result'] = true;

            return new JsonResponse($result);
        } else {
            $this->writeLog("App/Controller/DashboardCurrenciesController::addCurrencyAction Authorization Error");
            return $this->render('error_access.twig', array(
              'translation' => $this->getTranslation(),
            ));
        }
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function editBrandAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $form = $request->request->get('form');
            $em = $this->getDoctrine()->getManager();
            $currency_object = $em->getRepository('App:CommonCurrencies')->findOneByCurrencyId($form['id']);

            $currency_object->setCurrencyName($form['name']);
            $currency_object->setCurrencyCode($form['code']);
            $currency_object->setCurrencySymbol($form['symbol']);

            $em->flush();

            $result['result'] = true;

            return new JsonResponse($result);
        } else {
            $this->writeLog("App/Controller/DashboardCurrenciesController::editCurrencyAction > Authorization Error");
            return $this->render('error_access.twig', array(
              'translation' => $this->getTranslation()
            ));
        }
    }

    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function deleteBrandAction(Request $request)
    {
        if ($this->checkAuthorization() == true && $request->request->get('request') == true) {
            $currency_id = $request->request->get('currency_id');
            $em = $this->getDoctrine()->getManager();
            $currency_object = $em->getRepository('App:CommonCurrencies')->findOneByCurrencyId($currency_id);

            $em->remove($currency_object);
            $em->flush();

            $result['result'] = true;

            return new JsonResponse($result);
        } else {
            $this->writeLog("App/Controller/DashboardCurrenciesController::deleteCurrencyAction Authorization Error");
            return $this->render('error_access.twig', array(
              'translation' => $this->getTranslation()
            ));
        }
    }
}
