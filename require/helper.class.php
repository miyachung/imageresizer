<?php

define('MB', 1048576);

$resizer = new resizeHelper();

class resizeHelper{

    public $zip_dir           = __DIR__.'/../tmp/archives/';
    public $images_dir        = __DIR__.'/../tmp/images/';
    public $resize_dir        = __DIR__.'/../tmp/resized/';
    public $allowed_types  = ['application/x-zip-compressed','image/gif','image/png','image/jpeg'];

    public function image_resize($image,$width,$height){

        $pathinfo = pathinfo($image);

        if($pathinfo['extension'] == 'jpg'){

            $img      = imagecreatefromjpeg($image);

            $resized  = imagescale($img,$width,$height);

            $filename = $pathinfo['filename'].'_'.$width.'x'.$height.'.'.$pathinfo['extension'];

            $path    = $this->resize_dir.$filename;

            imagejpeg($resized,$path);

            if(file_exists($path)){
                return $filename;
            }else{
                return false;
            }
        }elseif($pathinfo['extension'] == 'gif'){

            $img      = imagecreatefromgif($image);

            $resized  = imagescale($img,$width,$height);

            $filename = $pathinfo['filename'].'_'.$width.'x'.$height.'.'.$pathinfo['extension'];

            $path    = $this->resize_dir.$filename;

            imagegif($resized,$path);

            if(file_exists($path)){
                return $filename;
            }else{
                return false;
            }

        }elseif($pathinfo['extension'] == 'png'){

            $img      = imagecreatefrompng($image);

            $resized  = imagescale($img,$width,$height);

            $filename = $pathinfo['filename'].'_'.$width.'x'.$height.'.'.$pathinfo['extension'];

            $path    = $this->resize_dir.$filename;

            imagepng($resized,$path);

            if(file_exists($path)){
                return $filename;
            }else{
                return false;
            }
        }

    }
    public function upload_file($temp_name,$new_name,$type){

        if(stristr($type,'image')){
            $path = $this->images_dir.strtolower($new_name);
        }else{
            $path = $this->zip_dir.strtolower($new_name);
        }

        if(move_uploaded_file($temp_name,$path)){
            return $path;
        }else{
            return false;
        }

    }

    public function check_total_size($sizes){
        $s = 0;
        
        foreach($sizes as $size){
            $s += $size;
        }

        if($s > 48*1048576){
            return false;
        }else{
            return true;
        }
    }

    public function delete_file($file){
        unlink($file);
    }

    public function extract_zip($path){

        $zip = new ZipArchive;
        $pathinf = pathinfo($path);
        
        if($zip->open($path) === TRUE){
            $zip->extractTo($this->zip_dir.$pathinf['filename']);
            $zip->close();
        }

        if(is_dir($this->zip_dir.$pathinf['filename'])){
            return $this->zip_dir.$pathinf['filename'];
        }else{
            return false;
        }
    }

    public function delete_dir($dir) { 
        $files = array_diff(scandir($dir), array('.','..')); 
         foreach ($files as $file) 
           (is_dir("$dir/$file")) ? delTree("$dir/$file") : @unlink("$dir/$file"); 
         return @rmdir($dir); 
    } 

}