<?php
/**
 * Filename      : fifo_queue.class.php
 * Date created  : 30/10-2016, Ivan
 * Date last edit: 30/10-2016, Ivan
 *
 * @author Ivan Mark Andersen <ivanonof@gmail.com>
 *
 * Description:
 *  Wraps operations on a First-In-First-Out (FIFO) queue in this class.
 *
 * Example:
 *  require_once('fifo_queue.class.php');
 *  $fifoQueue = FifoQueue::getInstance();
 *  $fifoQueue->addToQueue($dataRecord);
 *
 *  // Handle all in the FIFO-queue.
 *  while ($nextDataRecord = $fifoQueue->getNextInQueue())
 *  {
 *     // Do something.
 *  }
 */
class FifoQueue
{
   private $arrQueueData;

   /**
    * @return FifoQueue
    */
   public function __construct() {
      $arrQueueData = array();  
   }

   public function __destruct() {
      unset($this->arrQueueData);
   }

   /**
    * @return FifoQueue
    */
   public static function getInstance() : FifoQueue {
      return new FifoQueue();
   } // method getInstance

   public function addToQueue($p_record) {
      if (is_array($this->arrQueueData)) {
        array_push($this->arrQueueData, $p_record);
      } else {
        trigger_error('Pre-condition was not met - The queue is not an array ...', E_USER_ERROR);
      }
   } // method addToQueue

   /**
    * @return mixed|boolean Returns the next record in the FIFO-queue else it returns boolean FALSE.
    */
   public function getNextInQueue() {
      if (is_array($this->arrQueueData)) {
        if (!empty($this->arrQueueData)) {
          return array_pop($this->arrQueueData);
        } else {
          return FALSE;
        }
      } else {
        return FALSE;
      }
   } // method getNextInQueue

   /**
    * Returns the number of records in the queue.
    * @return int
    */
   public function getNumOfRecords() {
      if (is_array($this->arrQueueData)) {
        return count($this->arrQueueData);
      } else {
        return 0;
      }
   } // method getNumOfRecords
} // End class