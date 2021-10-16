<?php

header('Content-type: application/json');
require __DIR__.'/../require/helper.class.php';

$output = array();


if($_POST['files']){

    $files = explode(",",$_POST['files']);
    $files = array_filter($files);
    $files = array_map('trim',$files);

    $zip         = new ZipArchive;
    $random_name = 'images-'.md5(uniqid()).'.zip';
    $random      = $resizer->zip_dir.$random_name;

    if($zip->open($random,ZipArchive::CREATE)) {

        foreach($files as $file){
            $path    = $resizer->resize_dir.$file;
            $zip->addFile($path,$file);
        }
        $zip->close();

        if(file_exists($random)){
            $output['download_url'] = $random_name;

            foreach($files as $file){
                unlink($resizer->resize_dir.$file);
            }
        }else{
            $output['error'] = 'file_error';
            die(json_encode($output));
        }

    }else{
        $output['error'] = 'zip_file_create_error';
        die(json_encode($output));
    }


}else{
    $output['error'] = 'emptyerror';
    die(json_encode($output));
}

print json_encode($output);