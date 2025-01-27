<?php
namespace Common\Classes\Helper;

/**
 * Filename     : page_meta_data.class.php
 * Language     : PHP v7.4
 * Date created : 19/04-2023, Ivan
 * Last modified: 22/04-2023, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 * @copyright   : Copyright (C) 2023 by Ivan Mark Andersen
 *
 * Description:
 * My standard helper-class, that every renderer-class will use to support a uniform standardized and loose binding way to render a web-page.
 * This class holds data to be rendered as a web-page using templates.
 */
class PageMetaData
{
    /**
     * @var string
     */
    protected $pageTitle;

    /**
     * @var string
     */
    protected $pageMainContent;

    /**
     * @var array
     */
    protected $arrMetaData;

    /**
     * @var array
     */
    protected $arrLangSupported;

    /**
     * @var string
     */
    protected $pageSidebarContent;
    protected $pageCurrentLanguage;
    protected $pageMainMenu;
    protected $pageBreadCrumb;

    /**
     * @param string $p_pageTitle Default blank.
     * @param string $p_mainContent Default blank.
     */
    public function __construct(string $p_pageTitle ='', string $p_mainContent ='') {
        $this->setAttr_pageTitle($p_pageTitle);
        $this->setAttr_pageMainContent($p_mainContent);
        $this->arrMetaData = array();
        $this->setAttr_supportedLangs();
    }

    public function __destruct() {
    }

    // Setter and getter methods

    /**
     * @param string $p_pageTitle Default blank.
     */
    public function setAttr_pageTitle(string $p_pageTitle ='') : void {
        $this->pageTitle = $p_pageTitle;
    }

    /**
     * @return string
     */
    public function getAttr_pageTitle() : string {
        return $this->pageTitle;
    }

    /**
     * @param string $p_mainContent Default blank.
     */
    public function setAttr_pageMainContent(string $p_mainContent ='') : void {
        $this->pageMainContent = $p_mainContent;
    }

    /**
     * @return string
     */
    public function getAttr_pageMainContent() : string {
        return $this->pageMainContent;
    }

    /**
     * Sets the description meta-data of the page.
     * @param string $p_metaDescription Default blank.
     */
    public function setAttr_decriptionMetaData(string $p_metaDescription ='') : void {
        $this->arrMetaData['description'] = $p_metaDescription;
    }

    /**
     * @return string
     */
    public function getAttr_decriptionMetaData() : string {
        if (array_key_exists('description', $this->arrMetaData)) {
          return $this->arrMetaData['description'];
        } else {
          return '';
        }
    }

    /**
     * Returns the array of meta-data of the page.
     * @return array
     */
    public function getMetaData() : array {
        return $this->arrMetaData;
    }

    /**
     * Sets the keywords meta-data of the page.
     * @param string $p_metaKeywords Default blank.
     */
    public function setAttr_keywordsMetaData(string $p_metaKeywords ='') : void {
        $this->arrMetaData['keywords'] = $p_metaKeywords;
    }

    /**
     * @return string
     */
    public function getAttr_keywordsMetaData() : string {
        if (array_key_exists('keywords', $this->arrMetaData)) {
          return $this->arrMetaData['keywords'];
        } else {
          return '';
        }
    }

    /**
     * Sets the array of supported languages.
     * @param array $p_arrLangs Default empty array.
     */
    public function setAttr_supportedLangs(array $p_arrLangs =[]) : void {
        $this->arrLangSupported = $p_arrLangs;
    }

    /**
     * @return array
     */
    public function getAttr_supportedLangs() : array {
        return $this->arrLangSupported;
    }

    // Service methods

    /**
     * @param string $p_pageTitle Default blank.
     * @param string $p_pageMainContent Default blank.
     */
    public static function getInstance(string $p_pageTitle ='',
                                       string $p_pageMainContent ='') : PageMetaData {
        return new PageMetaData($p_pageTitle, $p_pageMainContent);
    }
} // End class