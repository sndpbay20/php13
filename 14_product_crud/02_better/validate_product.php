<?php


  // echo $_SERVER['REQUEST_METHOD' 

    $title = $_POST['title']; //test
    $description = $_POST['description'];
    $price = $_POST['price'];
    $imagepath = '';


    if (!$title) {
      $errors[] = 'product title is required';
    }
    if (!$price) {
      $errors[] = 'product price is required';
    }

    if(!is_dir(__DIR__.'/public/images')){
    mkdir(__DIR__.'/public/images');
    }

    if (empty($errors)) {
      $image = $_FILES['image'] ?? null;
      $imagepath =$product['image'];

      // echo '<pre>';
      // var_dump($image);
      // echo '</pre>';
      // exit;
      if ($image && $image['tmp_name']) {

        if($product['image'])
        {
            unlink(__DIR__.'/public/'.$product['image']);
        }


      $imagepath = 'images/' .randomString(9). '/' .$image['name'];
      mkdir(dirname(__DIR__.'/public/'.$imagepath));
      
      // echo '<pre>';
      // var_dump($imagepath);
      // echo '</pre>';
      // exit;   

        move_uploaded_file($image['tmp_name'],__DIR__.'/public/'. $imagepath);
      }}
    