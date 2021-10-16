<?php

header('Content-type: application/json');
require __DIR__.'/../require/helper.class.php';


$output = array();


if(empty($_POST['width']) || empty($_POST['height'])){
    $output['error'] = 'empty_error';
    die(json_encode($output));
}

if($resizer->check_total_size($_FILES['files']['size'])){

    foreach($_FILES['files']['name'] as $key => $f){
        
        if($upload = $resizer->upload_file($_FILES['files']['tmp_name'][$key],rand(1,5000).'_'.$_FILES['files']['name'][$key],$_FILES['files']['type'][$key])){


            if(stristr($_FILES['files']['type'][$key],'x-zip')){

                if($zipDir = $resizer->extract_zip($upload)){
                    $scan_dir = scandir($zipDir);

                    foreach($scan_dir as $scan){
                        if(is_file($zipDir.'/'.$scan)){
                            $pathinfo = pathinfo($zipDir.'/'.$scan);

                            if($pathinfo['extension'] == 'jpeg' || $pathinfo['extension'] == 'gif' || $pathinfo['extension'] == 'png' || $pathinfo['extension'] == 'jpg'){

                                if($resized = $resizer->image_resize($zipDir.'/'.$scan,$_POST['width'],$_POST['height']))
                                {
                                    $output['resized'][] = $resized;
                                }
                            }
                        }

                    }
                    
                }else{
                    $output['fails'][] = $_FILES['files']['name'][$key];
                }
               $resizer->delete_dir($zipDir);
               $resizer->delete_file($upload);
            }else{


                if($resized = $resizer->image_resize($upload,$_POST['width'],$_POST['height']))
                {
                    $output['resized'][] = $resized;
                }
                $resizer->delete_file($upload);

            }

        
            

        }else{
            $output['fails'][] = $_FILES['files']['name'][$key];
        }

    }

    $output['resized_count'] = @count($output['resized']);

}else{
    $output['error'] = 'size_error';
    die(json_decode($output));
}

print json_encode($output);