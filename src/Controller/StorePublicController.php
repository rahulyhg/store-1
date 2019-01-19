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
      return $this->render('store_public.html.twig', array(
        'translation' => $this->getTranslation(),
      ));
    }
}
