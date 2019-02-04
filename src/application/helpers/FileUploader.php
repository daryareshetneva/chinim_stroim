<?php

/**
 * Uploading and handling file on server
 */
class Helper_FileUploader extends Zend_Controller_Action_Helper_Abstract 
{
    /**
     * Upload file
     * @param string $tmpFile
     * @param string $filepath
     * @param string $path
     * @param array $extensions
     * @return string
     * @throws Exception
     */
    public function uploadFile( $tmpFile, $file, $path ) {
	$extension = pathinfo( $file, PATHINFO_EXTENSION );
        $filename = date('Ymdhis') . substr((string)microtime(), 2, 8);

	if ( !move_uploaded_file( $tmpFile, $filepath = $path . DIRECTORY_SEPARATOR . $filename . '.' . $extension ) )
	    throw new Exception( 'uploadError' );

	return $filepath;
    }

    /**
     * Upload and cut image
     * @param string $tmpFile
     * @param string $file
     * @param string $savepath path for new image
     * @param int $width max width for new image
     * @return string path for new image
     */
    public function uploadAndCutImage($tmpFile, $file, $savepath, $width) 
    {
        $origImage = $this->uploadFile($tmpFile, $file, $savepath);
        $filename = date('Ymdhis');
        $extension = pathinfo($origImage, PATHINFO_EXTENSION);
        
        try {
            list($origWidth, $origHeight) = getimagesize($origImage);
            $path = $savepath . DIRECTORY_SEPARATOR .  $filename .  '.' . $extension;
            if ($origWidth > $width) {
                $ratio = $origWidth/$width;
                $height = $origHeight/$ratio;

                $cutImage = imagecreatetruecolor($width, $height);
                $source = imagecreatefromjpeg($origImage);

                imagecopyresized($cutImage, $source, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight);
                imagejpeg($cutImage, $path);
            } else {
                $path = $origImage;
            } 
           return $path;  
       } catch (Exception $e) {
           echo $e->getMessage(); exit;
       }
    }
    
    /**
     * Cut and save already uploaded image
     * Supported file types: JPG, PNG
     * 
     * @param string $file
     * @param string $savepath
     * @param int $width
     * @return type
     */
    public function cutImage( $file, $savepath, $width, $height = 220 ) {
	$origImage = $file;
	$extension = strtolower( pathinfo( $origImage, PATHINFO_EXTENSION ) );
	if ( $extension !== 'jpg' && $extension !== 'jpeg' && $extension !== 'png' ) {
	    return $file;
	}
	$filename = date( 'Ymdhis' ) . substr( ( string ) microtime(), 2, 8 );

	try {
	    list($origWidth, $origHeight) = getimagesize( $origImage );
	    $path = $savepath . DIRECTORY_SEPARATOR . $filename . '.' . $extension;
	    if ( $origWidth >= $width || $origHeight >= $height ) {
		if ( $width > $height ) {
		    $ratio = $origWidth / $width;
		    $height = $origHeight / $ratio;
		} else {
		    $ratio = $origHeight / $height;
		    $width = $origWidth / $ratio;
		}

		$cutImage = imagecreatetruecolor( $width, $height );

		switch ( $extension ) {
		    case 'jpg':
		    case 'jpeg':
			$source = imagecreatefromjpeg( $origImage );
			imagecopyresampled( $cutImage, $source, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight );
			imagejpeg( $cutImage, $path, 100 );
			imagedestroy( $cutImage );
			break;
		    case 'png':
			$source = imagecreatefrompng( $origImage );
			imagealphablending( $cutImage, false );
			imagesavealpha( $cutImage, true );
			imagecopyresampled( $cutImage, $source, 0, 0, 0, 0, $width, $height, $origWidth, $origHeight );
			imagepng( $cutImage, $path );
			imagedestroy( $cutImage );
			break;
		    default: throw new Exception( 'Forbidden extension for image file' );
		}
	    } else {
		$path = $origImage;
	    }
	    return $path;
	} catch ( Exception $e ) {
	    throw new Zend_Exception( $e->getMessage() );
	}
    }

}