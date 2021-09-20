  <?php
  // Database connection 
  $pdo = new PDO('mysql:host=localhost; port=3306; dbname=product_crud', 'root', '');
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

  // echo '<pre>';
  // var_dump($_FILES);
  // echo '</pre>';
  // // exit;  

  $errors = [];

  $title = '';
  $description = '';
  $price = '';
  // echo $_SERVER['REQUEST_METHOD' 
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];
    $date = date('Y-m-d H:i:s');



    if (!$title) {
      $errors[] = 'product title is required';
    }
    if (!$price) {
      $errors[] = 'product price is required';
    }

    if(!is_dir('images')){
    mkdir('images');
    }

    if (empty($errors)) {
      $image = $_FILES['image'] ?? null;
      $imagepath = '';
      // echo '<pre>';
      // var_dump($image);
      // echo '</pre>';
      // exit;
      if ($image && $image['tmp_name']) {

      $imagepath = 'images/' .randomString(9). '/' .$image['name'];
      mkdir(dirname($imagepath));
      
      // echo '<pre>';
      // var_dump($imagepath);
      // echo '</pre>';
      // exit;   

        move_uploaded_file($image['tmp_name'], $imagepath);
      }
      // exit;
    $statement = $pdo->prepare("INSERT INTO products (title, image, description, price, create_date)
                    VALUES (:title, :image, :description, :price, :date)");



      $statement->bindValue(':title', $title);
      $statement->bindValue(':image', $imagepath);
      $statement->bindValue(':description', $description);
      $statement->bindValue(':price', $price);
      $statement->bindValue(':date', $date);
      $statement->execute();
      header('Location: index.php');
    }
  }

  function randomString($n)
  {
    $characters ='0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $str='';
    for($i = 0; $i < $n; $i++) {
    $index = rand(0, strlen($characters) - 1);
    $str = $characters[$index];
  }
  return $str;
}



  // print_r($pdo); die();

  ?>

  <!doctype html>
  <html lang="en">

  <head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.0/dist/css/bootstrap.min.css" integrity="sha384-KyZXEAg3QhqLMpG8r+8fhAXLRk2vvoC2f3B09zVXn8CA5QIVfZOJ3BCsw2P0p/We" crossorigin="anonymous">
    <link rel="stylesheet" href="app.css">
    <title>Product CRUD</title>

  </head>

  <body>
    <h1>Create new product</h1>

    <?php if (!empty($errors)) : ?>
      <div class="alert alert-danger">
        <?php foreach ($errors as $error) :  ?>
          <div><?php echo $error ?></div>
        <?php endforeach ?>
      </div>

    <?php endif;  ?>


    <form action="" method="post" enctype="multipart/form-data">
      <div class="form-group">
        <label>Product Image</label>
        <br>
        <input type="file" name="image">
      </div>
      <div class="form-group">
        <label>Product Title</label>
        <input type="text" name="title" class="form-control" value="<?php echo $title ?>">
      </div>
      <div class="form-group">
        <label>Product Description</label>
        <textarea class="form-control" name="description"><?php echo $description ?></textarea>
      </div>
      <div class="form-group">
        <label>Product Price</label>
        <input type="number" step=".01" name="price" value="<?php echo $price ?>" class="form-control">
      </div><br>

      <button type="submit" class="btn btn-primary">Submit</button>
    </form>

  </body>

  </html>