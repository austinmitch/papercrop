<?php
ini_set("post_max_size","1024M");
ini_set("max_file_uploads","200");

  error_reporting(0);
  require_once('Resize.php');

  function array_files(&$file_post)
  {
    $file_array = array();
    $file_count = count($file_post['name']);
    $file_keys = array_keys($file_post);

    for($i=0;$i<$file_count;$i++)
    {
      foreach($file_keys as $key)
      {
        $file_array[$i][$key] = $file_post[$key][$i];
      }//foreach
    }//for
    return $file_array;
  }//function

  //16:9 ratios
  $new_width = array(
    30000,
    20000,
    10000,
    5120,
    3840,
    3200,
    2560,
    1920
  );

  $new_height = array(
    30000,
    20000,
    10000,
    2880,
    2160,
    1800,
    1440,
    1080
  );

  if($_POST['submit']) {

    $file_array = array_files($_FILES['wallpaper']);

    foreach($file_array as $file) {

      //set up file path info
      $path_parts = pathinfo($file["name"]);
      $extension = $path_parts["extension"];
      $prefix = $_POST["prefix"];

      //set up new path info
      $target_path = 'images/'.$prefix.'/';
      $newname = $prefix.time().'.'.$extension;

      mkdir($target_path);

      $target_path .= $newname;

      move_uploaded_file($file["tmp_name"], $target_path);

      //get image resolution
      $data = getimagesize($target_path);
      $old_width = $data[0];
      $old_height = $data[1];

      $div_w = $old_width / 16;
      $div_h = $old_height / 9;

      if($old_width < 1920 || $old_height < 1080)
      {
        unlink($target_path);
      }

      $resize_wall = new Resize($target_path);
      //abs() gives absolute value of Number
      //this algorithm checks if the width and height are divisible by 8
      $width;
      $height;

      if($div_w == $div_h)
      {
        $resize_wall->saveImage($target_path,100);
      }
      else
      {
        $size_count = count($new_width) - 1;
        for($i=0;$i<=$size_count;$i++)
        {
          var_dump($new_width[$i]);
          if($old_width >= $new_width[$i])
          {
            $width = $new_width[$i];
            $height = $new_height[$i];
            var_dump($width);
            break;
          }
        }
      }
      $resize_wall->resizeImage($width,$height,"crop");
      $resize_wall->saveImage($target_path,100);
    }

    $zip = new ZipArchive();
    $zip_name = 'images/'.$prefix.'.zip';
    $zip->open($zip_name, ZipArchive::CREATE);
    $zip->addGlob('images/'.$prefix.'/*.jpg');
    $zip->addGlob('images/'.$prefix.'/*.png');
    $zip->close($zip_name);

    $download = '<a class="download" href="'.$zip_name.'">Download</a>';
  }

 ?>
