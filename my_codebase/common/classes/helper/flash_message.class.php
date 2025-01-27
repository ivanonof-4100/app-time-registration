<?php
namespace Common\Classes\Helper;

/**
 * Filename     : flash_message.class.php
 * Language     : PHP v7.4
 * Date created : 10/08-2024, Ivan
 * Last modified: 10/08-2024, Ivan
 * Developers   : @author Ivan Mark Andersen <ivanonof@gmail.com>
 * @copyright   : Copyright (C) 2024 by Ivan Mark Andersen
 *
 * DESCRIPTION:
 * A flash-message is like a status-message that corresponds to the users interaction with the web-application.
 * Eg. like: "Your data was saved successfully to the database ...", if the user pressed the Save-button.
 * Each flash-message is displayed in different ways according to the type of flash-message.
 * 
 * @example
 * use Common\Classes\Helper\FlashMessage;
 * $flashMessage = FlashMessage::getInstance('Your data was saved successfully ...', FlashMessage::MESSAGE_TYPE_SUCCESS);
 */
class FlashMessage {
    const MESSAGE_TYPE_WARNING ='w';
    const MESSAGE_TYPE_ERROR ='e';
    const MESSAGE_TYPE_SUCCESS ='s';
    const MESSAGE_TYPE_INFO ='i';

    /**
     * @var string
     */
    protected $flash_message;

    /**
     * @var string
     */
    protected $flash_message_type;

    public function __construct(string $p_flashMessage ='', string $p_mesgType =self::MESSAGE_TYPE_INFO) {
      $this->setAttr_flashMessage($p_flashMessage);
      $this->setAttr_flashMessageType($p_mesgType);
    }

    public function __destruct() {
    }

    /**
     * @param string $p_mesg
     * @param string $p_mesgType Default 'i'
     * @return FlashMessage
     */
    public static function getInstance(string $p_mesg, string $p_mesgType =self::MESSAGE_TYPE_INFO) : FlashMessage {
      return new FlashMessage($p_mesg, $p_mesgType);
    }

    /**
     * @return string
     */
    public function __toString() : string {
      return sprintf("%s:%s", $this->getAttr_flashMessageType(), $this->getAttr_flashMessage());
    }

    // Getter and setter methods

    /**
     * @param string $p_flashMessage Default blank.
     * @return void
     */
    public function setAttr_flashMessage(string $p_flashMessage ='') : void {
      $this->flash_message = (string) $p_flashMessage;
    }

    /**
     * @return string
     */
    public function getAttr_flashMessage() : string {
      return $this->flash_message;
    }

    /**
     * @param string $p_messageType
     * @return void
     */
    public function setAttr_flashMessageType(string $p_messageType) : void {
        $this->flash_message_type = $p_messageType;
    }

    /**
     * @return string
     */
    public function getAttr_flashMessageType() : string {
        return $this->flash_message_type;
    }

    /**
     * Returns the cooresponding template to use to get the right effect.
     * @return string
     */
    public function getTemplateName() : string {
        switch ($this->flash_message_type) {
            case self::MESSAGE_TYPE_INFO:
              return 'widget_alert_info.tpl';
              break;
            case self::MESSAGE_TYPE_SUCCESS:
              return 'widget_alert_success.tpl';
              break;
            case self::MESSAGE_TYPE_WARNING:
              return 'widget_alert_warning.tpl';
              break;
            case self::MESSAGE_TYPE_ERROR:
              return 'widget_alert_danger.tpl';
              break;
            default:
              return 'widget_alert_info.tpl';
        }
    }
}