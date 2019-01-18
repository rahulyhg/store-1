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
          'meta' => [
               'title' => "hdhdhd",
               'description' => 'hxbxbddb',
               'keywords' => 'hdhdhdhdhd'
          ],
      );
    return $this->render('store_public_template.html.twig', array(

    ));
  }

}
