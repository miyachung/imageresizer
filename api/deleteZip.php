<?php

require __DIR__.'/../require/helper.class.php';


$file = $_POST['file'];

if(file_exists($resizer->zip_dir.$file)){
    $resizer->delete_file($resizer->zip_dir.$file);
}