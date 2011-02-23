<?php
/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

class Imageprocess{

    function makeImageRound( $folder = '' , $filename = '' , $topleft = true ,$bottomleft = true , $bottomright = true , $topright = true )
    {
        $image_file = $filename;
        $corner_radius = isset($_GET['radius']) ? $_GET['radius'] : 20; // The default corner radius is set to 20px
        $angle = isset($_GET['angle']) ? $_GET['angle'] : 0; // The default angle is set to 0Âº
        /*$topleft = (isset($_GET['topleft']) and $_GET['topleft'] == "no") ? false : true; // Top-left rounded corner is shown by default
        $bottomleft = (isset($_GET['bottomleft']) and $_GET['bottomleft'] == "no") ? false : true; // Bottom-left rounded corner is shown by default
        $bottomright = (isset($_GET['bottomright']) and $_GET['bottomright'] == "no") ? false : true; // Bottom-right rounded corner is shown by default
        $topright = (isset($_GET['topright']) and $_GET['topright'] == "no") ? false : true; // Top-right rounded corner is shown by default*/
        
        //$images_dir = 'D:\phpdev\wamp\www\imagegallery\project_gallery\\';
        $images_dir = $folder;

        if(!file_exists(MISC_PATH.DS.'rounded_corner.png'))
            return ;

        if(!file_exists(MISC_PATH.DS.'rounded_corner.png'))
            return ;

        $mime = getimagesize($images_dir.$image_file);

        //$corner_source = imagecreatefrompng(MISC_PATH.DS.'rounded_corner.png');
        $corner_source = imagecreatefromgif(MISC_PATH.DS.'rounded_corner.gif');

        $corner_width = imagesx($corner_source);
        $corner_height = imagesy($corner_source);
        $corner_resized = ImageCreateTrueColor($corner_radius, $corner_radius);
        ImageCopyResampled($corner_resized, $corner_source, 0, 0, 0, 0, $corner_radius, $corner_radius, $corner_width, $corner_height);

        $corner_width = imagesx($corner_resized);
        $corner_height = imagesy($corner_resized);
        $image = imagecreatetruecolor($corner_width, $corner_height);

        switch($mime['mime'])
        {
            case "image/jpeg" :
                    $image = imagecreatefromjpeg($images_dir . $image_file);
                    break;
            case "image/png" :
                    $image = imagecreatefrompng($images_dir . $image_file);
                    break;
            case "image/gif" :
                    $image = imagecreatefromgif($images_dir . $image_file);
                    break;
            default:
                    return ;
        }

        //$image = imagecreatefromjpeg($images_dir . $image_file); // replace filename with $_GET['src']
        
        //$size = getimagesize($images_dir . $image_file); // replace filename with $_GET['src']
        $size = $mime;//this is mime of image it contains same
        $white = ImageColorAllocate($image,255,255,255);
        $black = ImageColorAllocate($image,0,0,0);

        // Top-left corner
        if ($topleft == true) {
            $dest_x = 0;
            $dest_y = 0;
            imagecolortransparent($corner_resized, $black);
            imagecopymerge($image, $corner_resized, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);
        }

        // Bottom-left corner
        if ($bottomleft == true) {
            $dest_x = 0;
            $dest_y = $size[1] - $corner_height;
            $rotated = imagerotate($corner_resized, 90, 0);
            imagecolortransparent($rotated, $black);
            imagecopymerge($image, $rotated, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);
        }

        // Bottom-right corner
        if ($bottomright == true) {
            $dest_x = $size[0] - $corner_width;
            $dest_y = $size[1] - $corner_height;
            $rotated = imagerotate($corner_resized, 180, 0);
            imagecolortransparent($rotated, $black);
            imagecopymerge($image, $rotated, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);
        }

        // Top-right corner
        if ($topright == true) {
            $dest_x = $size[0] - $corner_width;
            $dest_y = 0;
            $rotated = imagerotate($corner_resized, 270, 0);
            imagecolortransparent($rotated, $black);
            imagecopymerge($image, $rotated, $dest_x, $dest_y, 0, 0, $corner_width, $corner_height, 100);
        }

        // Rotate image
        $image = imagerotate($image, $angle, $white);

        // Output final image
        switch($mime['mime'])
        {
            case "image/jpeg" :
                    imagejpeg($image , $images_dir . $image_file , 95);
                    break;
            case "image/png" :
                    imagepng($image , $images_dir . $image_file);
                    break;
            case "image/gif" :
                    imagegif($image , $images_dir . $image_file);
                    break;
            default:
                    return ;
        }
        //imagejpeg($image , $images_dir . $image_file);

        imagedestroy($image);
        imagedestroy($corner_source);

        return true;
    }

}

?>
