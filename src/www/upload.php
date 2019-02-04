<?php

if (@$_FILES['upload']['name']) {

    $url = 'images/upload/' . time() . "_" . $_FILES['upload']['name'];


    if (($_FILES['upload'] == "none") || (empty($_FILES['upload']['name']))) {
        $message = "No file uploaded.";
    } else if ($_FILES['upload']["size"] == 0) {
        $message = "The file is of zero length.";
    } else if (
            ($_FILES['upload']["type"] != "image/pjpeg")
            && ($_FILES['upload']["type"] != "image/jpeg")
            && ($_FILES['upload']["type"] != "image/png")
            && ($_FILES['upload']["type"] != "application/pdf")
            && ($_FILES['upload']["type"] != "application/msword") // doc
            && ($_FILES['upload']["type"] != "application/vnd.ms-excel") // xls
            && ($_FILES['upload']["type"] != "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet") // xlsx
            && ($_FILES['upload']["type"] != "application/vnd.openxmlformats-officedocument.wordprocessingml.document") // docx
            && ($_FILES['upload']["type"] != "application/rtf")
    ) {
        $message = "The image must be in either JPG or PNG format. Please upload a JPG or PNG instead.";
    } else if (!is_uploaded_file($_FILES['upload']["tmp_name"])) {
        $message = "You may be attempting to hack our server. We're on to you; expect a knock on the door sometime soon.";
    } else {

        if (!is_writable('images/upload')) {
            chmod('images/upload', 0777);
        }
        chmod($url, 0777);
        if (!move_uploaded_file($_FILES['upload']['tmp_name'], $url)) {
            $message = "Error moving uploaded file. Check the script is granted Read/Write/Modify permissions.";
        } else {
            chmod($url, 0777);
            $url = $_SERVER['REQUEST_URI'] . $url;
            $url = preg_replace('#upload.php(.*)images#', 'images', $url);
        }
    }

    $funcNum = $_GET['CKEditorFuncNum'];
    echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($funcNum, '$url', '$message');</script>";
}
?>