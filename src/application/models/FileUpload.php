<?php

class Model_FileUpload {

  const MARKETINGDIR = 'images/marketing/';
  const DIYDIR = 'images/blog/';
  const PORTFOLIODIR = 'images/portfolio/';
    const SERVICESDIR = 'images/services/';
    
  public function uploadMarketingPhoto($tmpFile, $filename) {
    $filename = self::MARKETINGDIR . time() . $filename;
    
    $this->_uploadFile($tmpFile, $filename);
    return $filename;
  }
  
  public function uploadDiyPhoto($tmpFile, $filename) {
    $filename = self::DIYDIR . time() . $filename;
    
    $this->_uploadFile($tmpFile, $filename);
    return $filename;
  }

  public function uploadPortfolioPhoto($tmpFile, $filename) {
    $filename = self::PORTFOLIODIR . time() . $filename;
    $this->_uploadFile($tmpFile, $filename);
    return $filename;
  }

    public function uploadServicePhoto($tmpFile, $filename) {
        $filename = self:: SERVICESDIR  . $filename;
        $this->_uploadFile($tmpFile, $filename);
        return $filename;
    }
    
  protected function _uploadFile($tmpFile, $filename) {
    if (!move_uploaded_file($tmpFile, $filename))
    throw new Exception('logoUploadError');
  }
  
}
