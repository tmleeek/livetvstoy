<?php

// Adding Imagick CLass Methods

class ImagickDraw_ext extends ImagickDraw  {
    
	public function __construct() { 
        parent::__construct(); 
    } 
	
    public function setTextInterLineSpacing_EXT($spacing) {
      //v6.5.5-8, -interline-spacing XX
      $this->draw->covert[] = sprintf('-interline-spacing %s', $spacing);
      return $this;
    }
	public function setFontSize_EXT($pointsize) {

	  echo '<pre>';
	  
	  print_r($this);
	   echo '</pre>';
	   
	   exit;
      return $this;
    }
}

?>