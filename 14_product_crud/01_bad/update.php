<?php
  // Database connection 
  $pdo = new PDO('mysql:host=localhost; port=3306; dbname=product_crud', 'root', '');
  $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    
  $id = $_GET['id'] ?? null;

  if(!$id) {
      header('Location: index.php');
      exit;   
  }
  
  $statement = $pdo->prepare('SELECT * FROM products WHERE id = :id');
  $statement->bindValue(':id', $id);
  $statement->execute();
  $product = $statement->fetch(PDO::FETCH_ASSOC);
  
    // echo '<pre>';
    // var_dump($product);
    // echo '</pre>';
    // exit;  


  // echo '<pre>';
  // var_dump($_FILES);
  // echo '</pre>';
  // // exit;  

  $errors = [];

  $title = $product['title'];
  $description = $product['description'];
  $price = $product['price'];
  // echo $_SERVER['REQUEST_METHOD' 
  if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $title = $_POST['title'];
    $description = $_POST['description'];
    $price = $_POST['price'];



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
      $imagepath =$product['image'];

      // echo '<pre>';
      // var_dump($image);
      // echo '</pre>';
      // exit;
      if ($image && $image['tmp_name']) {

        if($product['image'])
        {
            unlink($product['image']);
        }


      $imagepath = 'images/' .randomString(9). '/' .$image['name'];
      mkdir(dirname($imagepath));
      
      // echo '<pre>';
      // var_dump($imagepath);
      // echo '</pre>';
      // exit;   

        move_uploaded_file($image['tmp_name'], $imagepath);
      }
      // exit;
   $statement = $pdo->prepare("UPDATE products SET title = :title, image = :image,
      description = :description, price = :price WHERE id = :id");  



      $statement->bindValue(':title', $title);
      $statement->bindValue(':image', $imagepath);
      $statement->bindValue(':description', $description);
      $statement->bindValue(':price', $price);
      $statement->bindValue(':id', $id);
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

  <p>
      <a href="index.php" class="btn btn-secondary">Go Back to Products</a> 
  </p>

  <h1>Update product <b><?php echo $product["title"] ?></b></h1>

    <?php if (!empty($errors)) : ?>
      <div class="alert alert-danger">
        <?php foreach ($errors as $error) :  ?>
          <div><?php echo $error ?></div>
        <?php endforeach ?>
      </div>

    <?php endif;  ?>


    <form action="" method="post" enctype="multipart/form-data">

    <?php if ($product['image']): ?>
            <img src="<?php echo $product['image'] ?>" class="update-image">
            <?php endif; ?>


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