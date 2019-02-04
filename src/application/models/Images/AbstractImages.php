<?php

class Model_Images_AbstractImages implements Zend_Filter_Interface {

    const IMAGE_HEIGHT = 400;
    const IMAGE_QUALITY = 100;

    protected $_imagesDirectory = 'tmp/';

    public function filter($value, $imageName = '', $maxWidth = '', $maxHeight = '', $rewriteName = '') {
        list($origWidth, $origHeight, $type) = getimagesize($value);
        if (empty($imageName)) {
            $imageName = $this->_getImageName($value);
        } else {
            $imageName = $this->_getImageName($imageName);
        }
        if (!$this->_checkFolder()) {
            if (!$this->_createFolder())
                throw new Exception('Can not save image. Directory doesn\'t exist and can\'t be created. Check folder permissions or contact Administrator.');
        }

        $origImage = null;
        switch ($type) {
            case 1:
                $origImage = @imagecreatefromgif($value);
                break;
            case 2:
                $origImage = @imagecreatefromjpeg($value);
                break;
            case 3:
                $origImage = @imagecreatefrompng($value);
                break;
            default: break;
        }

        if (!empty($rewriteName)) {
            $extension = $this->_getExtensionByFilename($imageName);
            $imageName = $rewriteName . '.' . $extension;
        }

        $newSizes = $this->getResizeImageWidthAndHeight($origWidth, $origHeight, $maxWidth, $maxHeight);


        if (!$this->saveImage($imageName, $origImage, $newSizes[0], $newSizes[1], $type)) {
            throw new Exception('Can not save image. Please contact Administrator.');
        }
        return $imageName;
    }

    /**
     * Save image on disk
     * @param $imageName string Image name
     * @param $image resource Image
     * @param $width int Image Width
     * @param $height int Image height
     * @return bool
     */
    public function saveImage($imageName, $image, $width, $height, $type = '') {
        $content = $this->resizeImage($image, $width, $height);

        switch ($type) {
            case 3:
                imagealphablending($content, false);
                imagesavealpha($content, true);
                $result = imagepng($content, $this->_imagesDirectory . DIRECTORY_SEPARATOR . $imageName, self::IMAGE_QUALITY / 100);
                break;
            default:
                $result = imagejpeg($content, $this->_imagesDirectory . DIRECTORY_SEPARATOR . $imageName, self::IMAGE_QUALITY);
        }

        imagedestroy($content);
        return $result;
    }

    /**
     * Get Images Folder
     * @return string
     */
    public function getImagesFolder() {
        if (!$this->_checkFolder()) {
            $this->_createFolder();
        }
        return $this->_imagesDirectory;
    }

    /**
     * Resize image with new width and height
     * @param $image resource Image Object
     * @param $width int New width
     * @param null $height int New Height
     * @return resource Resize Image object
     */
    public function resizeImage($image, $width, $height = null) {
        $imageWidth = imagesx($image);
        $imageHeight = imagesy($image);
        $aspect = $imageWidth / $imageHeight;

        if ($imageWidth === $width && $imageHeight === $height) {
            return $image;
        }

        if ($imageHeight > $imageWidth && !$width) {
            $height = ($imageHeight < self::IMAGE_HEIGHT) ? $imageHeight : self::IMAGE_HEIGHT;
            $width = round($height * $aspect);
        }

        if (!$height) {
            $height = round($width * $aspect);
            if ($height > $imageHeight) $height = $imageHeight;
        }

        if ($width < 1) {
            $width = 1;
        }
        if ($height < 1) {
            $height = 1;
        }

        $resizeImage = $this->_createBlankImage($width, $height);
        imagecopyresampled($resizeImage, $image,
            0, 0, 0, 0,
            $width, $height, $imageWidth, $imageHeight);

        return $resizeImage;
    }

    public function getResizeImageWidthAndHeight($origWidth, $origHeight, $maxWidth = '', $maxHeight = '') {
        $newWidth = 0;
        $newHeight = 0;
        if (empty($maxWidth) && empty($maxHeight)) {
          return [$origWidth, $origHeight];
        }
        if (!empty($maxWidth) && !empty($maxHeight)) {
            if ($maxHeight > $maxWidth) {
                $newHeight = $maxHeight;
                $newWidth = round($maxHeight * $origWidth / $origHeight);
                if ($newWidth > $maxWidth) {
                    $newWidth = $maxWidth;
                    $newHeight = round($maxWidth * $origHeight / $origWidth);
                }
            } else {
                $newWidth = $maxWidth;
                $newHeight = round($maxWidth * $origHeight / $origWidth);
                if ($newHeight > $maxHeight) {
                    $newHeight = $maxHeight;
                    $newWidth = round($maxHeight * $origWidth / $origHeight);
                }
            }
        } else {
            if (!empty($maxWidth)) {
                $newWidth = $maxWidth;
                $newHeight = round($maxWidth * $origHeight / $origWidth);
            } else if (!empty($maxHeight)) {
                $newHeight = $maxHeight;
                $newWidth = round($maxHeight * $origWidth / $origHeight);
            }
        }

        return [$newWidth, $newHeight];

    }

    /**
     * @param $image
     * @param null $width
     * @param null $height
     * @return string
     */
    public function url($image, $width = null, $height = null) {
        $imageUrl = $this->_imagesDirectory . DIRECTORY_SEPARATOR . $image;
        if (!empty($width) && !(empty($height))) {
            $imageUrl = $this->_imagesDirectory . DIRECTORY_SEPARATOR . $image . '_' . $width . '_'. $height;
        }
        return $imageUrl;
    }

    /**
     * Creates blank image
     *
     * @param int $width
     * @param int $height
     * @return resource
     */
    protected function _createBlankImage($width, $height) {
        $image = imagecreatetruecolor($width, $height);
        imagealphablending($image, false);
        imagesavealpha($image, true);
        $transparent = imagecolorallocatealpha($image, 255, 255, 255, 127);
        imagefilledrectangle($image, 0, 0, $width, $height, $transparent);
        return $image;
    }

    /**
     * Gets image name from temp string
     *
     * @param string $image
     * @return string
     * @throws Exception
     */
    protected function _getImageName($image) {
        $parts = explode(DIRECTORY_SEPARATOR, $image);
        $img = end($parts);

        if (empty($img)) throw new Exception('Can not parse image name. Please contact Administrator');
        return $img;
    }

    /**
     * Check if folder exist and writable
     * @return bool
     */
    protected function _checkFolder() {
        return (is_dir($this->_imagesDirectory) && is_writable($this->_imagesDirectory));
    }

    /**
     * Create image directory
     * @return bool
     * FIXME! Add correct way in testing environment
     */
    protected function _createFolder() {
        if (APPLICATION_ENV !== 'testing') {
            return mkdir($this->_imagesDirectory, 0777);
        } else {
            return '';
        }
    }

    protected function _getExtensionByFilename($imageName) {
        $parts = explode('.', $imageName);
        $ext = end($parts);

        return $ext;
    }
}