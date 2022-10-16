<?php
/**
 * Script-name  : handled_product_renderer.class.php
 * Language     : PHP v7.x
 * Date created : IMA, 20/01-2017
 * Last modified: IMA, 09/02-2017
 * Developers   : @author IMA, Ivan Mark Andersen <ima@dectel.dk>
 *
 * @copyright Copyright (C) 2017 by Ivan Mark Andersen
 *
 * Description
 *  Rendering of handled-product objects.
 */
require_once(PATH_COMMON_RENDERS .'std_renderer.class.php');

class HandledProductRenderer extends StdRenderer
{
  /**
   * @param resource $p_languageFileHandlerObj
   * @param boolean $p_isPrintPage
   *
   * @return HandledProductRenderer
   */
  public function __construct($p_languageFileHandlerObj, $p_isPrintPage =false)
  {
     parent::__construct($p_languageFileHandlerObj, $p_isPrintPage);
  } // method constructor

  public function __destruct()
  {
     parent::__destruct();
  } // method destructor

  /**
   * @return HandledProductRenderer
   */
  public static function getInstance($p_languageFileHandlerObj, $p_isPrintPage =false)
  {
     return new HandledProductRenderer($p_languageFileHandlerObj, $p_isPrintPage);      
  } // method getInstance

  public function renderWarrantyHTMLResponse($p_wasFound, $p_handledProductObj)
  {
     $languageHandlerObj = $this->getInstance_languageFileHandler();
     $sectionTitle = $languageHandlerObj->getLocalizedContent('HANDLED_PRODUCT_WARRANTY_TITLE');
     $labelTextSerialno = $languageHandlerObj->getLocalizedContent('HANDLED_PRODUCT_SERIALNUMBER');
     $labelTextDateSent = $languageHandlerObj->getLocalizedContent('HANDLED_PRODUCT_WE_SENT');
     $labelTextWarrantyPeriod = $languageHandlerObj->getLocalizedContent('HANDLED_PRODUCT_WARRANTY_PERIOD');
     $mesgTextNotFound = $languageHandlerObj->getLocalizedContent('HANDLED_PRODUCT_NOT_FOUND');
     $mesgTextContactUs = $languageHandlerObj->getLocalizedContent('HANDLED_PRODUCT_CONTACT_US');

     // Get instance of template.
     $template = Template::getInstance('product_warranty.tpl');

     // Send the variables to the template.
     $template->assign('sectionTitle', $sectionTitle);
     $template->assign('wasFound', $p_wasFound);
     $template->assign('handledProductObj', $p_handledProductObj);
     $template->assign('labelTextSerialno', $labelTextSerialno);
     $template->assign('labelTextDateSent', $labelTextDateSent);
     $template->assign('labelTextWarrantyPeriod', $labelTextWarrantyPeriod);
     $template->assign('mesgTextNotFound', $mesgTextNotFound);
     $template->assign('mesgTextContactUs', $mesgTextContactUs);

     $template->display();
  } // method renderWarrantyHTMLResponse
} // Ends class
?>