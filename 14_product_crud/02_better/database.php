<?php

// Database connection 
$pdo = new PDO('mysql:host=localhost; port=3306; dbname=product_crud', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
