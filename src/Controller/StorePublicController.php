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
      return $this->render('store_public.twig', array(
        'translation' => $this->getTranslation(),
      ));
    }
}
