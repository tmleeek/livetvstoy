<?php
class Kwanso_FtpPhotoUploader_Model_Cron
{
  private $_ftpServer    = null;
  private $_ftpUserName = null;
  private $_ftpUserPass = null;
  private $_orderIds;
  private $_counter = 0;

  public function photoUploader()
  {
      $ftpDaysInterval = Mage::getStoreConfig('ftpphotouploader/general/ftp_images');

      if(!$ftpDaysInterval) {
        $ftpDaysInterval = '-1'; 
      }
      $orders = Mage::getModel('sales/order')->getCollection()
                                             ->addAttributeToSelect('*')
                                             ->addFieldToFilter('created_at', array(
                                                  'from'     => strtotime("$ftpDaysInterval day", time()),
                                                  'to'       => time(),
                                                  'datetime' => true
                                              ));
      $orders->getSelect()->where('status = "pending_fulfillment" or status = "pending"'); 
      $this->_orderIds = array();
      $images_to_upload = array();
      foreach ($orders as $order) {
        //array_push($order_ids, $order->getIncrementId());
        $orderId = Mage::getModel('sales/order')
                                  ->loadByIncrementId($order->getIncrementId());

        $items = $orderId->getAllVisibleItems();
        foreach($items as $i):
          if($i->getProductImageIsuploaded()) {
            continue;
          }
          $images = $this->saveImageToFileAction($i, $order->getIncrementId());
          $images_to_upload = array_merge($images_to_upload,$images);
        endforeach;
      }

      $this->connect_to_ftp($images_to_upload);

      foreach ($orders as $order) {
        $orderId = Mage::getModel('sales/order')
                                  ->loadByIncrementId($order->getIncrementId());
        $items = $orderId->getAllVisibleItems();
        $this->updateItemAfterImageUpload($items,$order->getIncrementId());
      }

      $this->order_ids_csv($this->_orderIds);
  }

  private  function updateItemAfterImageUpload($items,$incrementId)
  {
      foreach($items as $i):
          if(!$i->getCpsPhotoSubmissionId()) {
            continue;
          }
          if($i->getProductImageIsuploaded()) {
            continue;
          }
          array_push($this->_orderIds, $incrementId);
          if($i->getProductImageUrl() != null){
            $item = Mage::getModel('sales/order_item')->load($i->getItemId());
            $item->setProductImageIsuploaded(1);
            $item->save();
          }
       endforeach;
  }

  private function saveImageToFileAction($order_item, $orderId)
  {
      $images = array();
      if($order_item->getProductImageUrl() != null){
        $image = $this->createImage($order_item,$order_item->getProductImageUrl(), "processed", $orderId);
        array_push($images, $image);
      }
      if($order_item->getProductSubmissionImageUrl() != null){
        $image = $this->createImage($order_item,$order_item->getCroppedImageUrl(), "original", $orderId);
        array_push($images, $image);
      }
      if($order_item->getBackgroundCroppedUrl() != null){
        $image = $this->createImage($order_item,$order_item->getBackgroundCroppedUrl(), "cropped", $orderId);
        array_push($images, $image);
      }
      return $images;
  }

  public function createImage($order_item,$url, $image_type, $orderId)
  {
      $content = file_get_contents($url);
      //Store in the filesystem.
      $io = new Varien_Io_File();
      $image_name = $orderId."_".$order_item->getCpsPhotoSubmissionId()."_".$image_type.".jpg";
      $fp = fopen(Mage::getBaseDir('media').DS.$image_name, "w");
      fwrite($fp, $content);
      fclose($fp);
      return $image_name;
  }

  private function connect_to_ftp($files_to_upload, $csv=0, $downloadCSV = 0)
  {
      $this->_ftpServer    = "ftp.cpscompany.com";
      $this->_ftpUserName = "Mgphotouploaduser";
      $this->_ftpUserPass = '2016MG$cps';
      // $remote_dir = "/MGPhotoUploadImages";
      // set up basic connection
      $conn_id = ftp_connect($this->_ftpServer) or die("Could not connect to $this->_ftpServer");

      $login_result = ftp_login($conn_id, $this->_ftpUserName, $this->_ftpUserPass) or die("<span style='color:#FF0000'><h2>You do not have access to this ftp server!</h2></span>");   // login with username and password, or give invalid user message
      if ((!$conn_id) || (!$login_result)) {  // check connection
          echo "<span style='color:#FF0000'><h2>FTP connection has failed! <br />";
          echo "Attempted to connect to $this->_ftpServer for user $this->_ftpUserName</h2></span>";
          exit;
      }

      if($downloadCSV == 1) {
          $path = Mage::getBaseDir('media').DS.'designProductCSVs';
          $local_file = $path.DS.'remote_orders'.Mage::getModel('core/date')->date('d-m-Y').'.csv';
          $server_file = 'orders-combined-'.Mage::getModel('core/date')->date('d-m-Y').'.csv';

          // try to download $server_file and save to $local_file
          if (ftp_get($conn_id, $local_file, $server_file, FTP_BINARY)) {
            echo "Successfully written to $local_file\n";
            Mage::log("Successfully written to $local_file\n", null, 'ftp_photouploader_'.date("j.n.Y").'.log');
          } else {

            $ftpContentsList = ftp_nlist($conn_id, '/');
            $ftpContentsList = str_replace('/', '', $ftpContentsList);

            if(in_array($server_file, $ftpContentsList)) {
              Mage::getSingleton('core/session')->addError("There was a problem while downloading $server_file");
              Mage::log("There was a problem while downloading $server_file\n", null, 'ftp_photouploader_'.date("j.n.Y").'.log');
              ftp_close($conn_id);
              return;
            }
          }

          ftp_close($conn_id);
          return $local_file;
      } else {
          foreach ($files_to_upload as $file) {

            if($csv) {
                $imagePath = Mage::getBaseDir("media").DS.'designProductCSVs'.DS.$file;
                Mage::log("Uploading CSV : ".$imagePath, null, 'ftp_photouploader_'.date("j.n.Y").'.log');
            } else {
                $imagePath = Mage::getBaseDir("media").DS.$file;
                Mage::log("Uploading Image : ".$imagePath, null, 'ftp_photouploader_'.date("j.n.Y").'.log');
            }
            
            if(file_exists($imagePath)) {
                // $remoteDir = $remote_dir.DS.$file;
                Mage::log($file." Exists", null, 'ftp_photouploader_'.date("j.n.Y").'.log');

                if (ftp_put($conn_id, $file, $imagePath, FTP_BINARY)) {
                Mage::log("File uploaded", null, 'ftp_photouploader_'.date("j.n.Y").'.log');

                $ftpContentsList = ftp_nlist($conn_id, '/'); // get list of all the files on ftp
                $ftpContentsList = str_replace('/', '', $ftpContentsList); //remove slash in front of sile paths/names

                if(!in_array($file, $ftpContentsList)) {
                  $this->retryFileUpload($conn_id, $file, $imagePath);
                }

                if(!$csv) { // do not delete if its a CSV file
                    unlink($imagePath);
                  }
                echo "uploaded";
              } else  {
                  Mage::log("Error uploading $imagePath", null, 'ftp_photouploader_'.date("j.n.Y").'.log');
              }

            } else {
              Mage::log($file." Does Not Exists on Path ".$imagePath, null, 'ftp_photouploader_'.date("j.n.Y").'.log');
            }
        }
      }
      ftp_close($conn_id);
  }

  public function retryFileUpload($conn_id,$file,$path)
  {
    $this->_counter++;

    if($this->_counter < 25) {
      if(ftp_put($conn_id, $file, $path, FTP_BINARY)) {
            Mage::log("File uploaded retried and uploaded $this->_counter", null, 'ftp_photouploader_'.date("j.n.Y").'.log');

            $ftpContentsList = ftp_nlist($conn_id, '/');
            $ftpContentsList = str_replace('/', '', $ftpContentsList);

            if(!in_array($file, $ftpContentsList)) {
              $this->retryFileUpload($conn_id, $file, $path);
            }

            if(!$csv) { // do not delete if its a CSV file
                unlink($imagePath);
              }

          } else  {
              Mage::log("Error uploading $path", null, 'ftp_photouploader_'.date("j.n.Y").'.log');
          }
    }
    
  }

  public function order_ids_csv($order_ids)
  {
      $images_to_upload = array();
      $path = Mage::getBaseDir('media').DS.'designProductCSVs';
      $file = 'order'.Mage::getModel('core/date')->date('d-m-Y').'.csv';
      $file_name=$path.DS.$file;
      $df = fopen($file_name, 'w');
      if(count($order_ids)) {
        fputcsv($df, array("order_id"));
      }
      foreach($order_ids as $i):
        fputcsv($df, array($i));
      endforeach;
      fclose($df);
      
      $remoteFileName =  'orders-combined-'.Mage::getModel('core/date')->date('d-m-Y').'.csv';
      $remoteCSV = $this->connect_to_ftp($remoteFileName,0,1);

      $joinedFile = 'orders-combined-'.Mage::getModel('core/date')->date('d-m-Y').'.csv';

      file_put_contents($path.DS.$joinedFile,
        file_get_contents($file_name) .
        file_get_contents($remoteCSV)
      );
      //$this->joinCSVs(array($file,$remoteCSV), $joinedFile);
      
      array_push($images_to_upload, $joinedFile);
      $this->connect_to_ftp($images_to_upload,1);
  }

    public function joinCSVs($files, $joinedFile)
  {
      $wH = fopen($joinedFile, "w");
      foreach($files as $file) {
          $fh = fopen($file, "r");
          while(!feof($fh)) {
              fwrite($wH, fgets($fh));
          }
          fclose($fh);
          unset($fh);
          fwrite($wH, "\n"); //usually last line doesn't have a newline
      }
      fclose($wH);
      unset($wH);
  }

}
