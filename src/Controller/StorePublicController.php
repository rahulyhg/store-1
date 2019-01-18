<?php
namespace App\Controller;

use App\Controller\MainController;

class StorePublicController extends MainStoreController
{
    /* ##################################################################################### */
    //
    /* ##################################################################################### */
    public function renderPublicTemplateAction()
    {
      $content = array(
        'translation' => $this->getTranslation(),
        'meta' => [
          'title' => "hdhdhd",
          'description' => 'hxbxbddb',
          'keywords' => 'hdhdhdhdhd'
        ],
      );
      $qwer = $this->getTranslation();
      var_dump($qwer);
      return $this->render('temp.html.twig', $content);
    }
}
