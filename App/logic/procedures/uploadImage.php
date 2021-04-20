<?php
    //require_once("utility.inc.php");

    /**
     * Images can be uploaded for feature image purposes or just for the article media
     * For feature images, they will be uploaded after the article has been saved.
     */
    $image = $_FILES['image'];
    switch (exif_imagetype($image['tmp_name'])) {
        case IMAGETYPE_PNG:
            $imageTmp=imagecreatefrompng($image['tmp_name']);
            break;
        case IMAGETYPE_JPEG:
            $imageTmp=imagecreatefromjpeg($image['tmp_name']);
            break;
        case IMAGETYPE_GIF:
            $imageTmp=imagecreatefromgif($image['tmp_name']);
            break;
        case IMAGETYPE_BMP:
            $imageTmp=imagecreatefrombmp($image['tmp_name']);
            break;
        // Defaults to JPG
        default:
            $imageTmp=imagecreatefromjpeg($image['tmp_name']);
            break;
    }

    $newImage = uniqid("img-").".jpeg";
    imagejpeg($imageTmp, "../../storage/images/$newImage");
    imagedestroy($imageTmp);

    //image uploading done.
    echo "{
        \"success\" : 1,
        \"file\": {
            \"url\" : \"http://localhost/pangaea/App/storage/images/$newImage\"
        }
    }"

?>