<?php

// Get the product data
$food_id = filter_input(INPUT_POST, 'food_id', FILTER_VALIDATE_INT);
$name = filter_input(INPUT_POST, 'name');
$address = filter_input(INPUT_POST, 'address');
$email = filter_input(INPUT_POST, 'email');
$number = filter_input(INPUT_POST, 'number', FILTER_VALIDATE_INT);
$delivery_id = 3;

// Validate inputs
if ($name == null || $food_id == null || $food_id == false ||
    $address == null || $email == null || $number == null || $number == false ) {
    $error = "Invalid product data. Check all fields and try again.";
    include('error.php');
    exit();
} else {
    
    require_once('database.php');

    // Add the product to the database 
    $query = "INSERT INTO orders
                 (foodID, deliveryID, name, address, email, number)
              VALUES
                 (:food_id, :delivery_id, :name, :address, :email, :number)";
    $statement = $db->prepare($query);
    $statement->bindValue(':delivery_id', $delivery_id);
    $statement->bindValue(':food_id', $food_id);
    $statement->bindValue(':name', $name);
    $statement->bindValue(':address', $address);
    $statement->bindValue(':email', $email);
    $statement->bindValue(':number', $number);
    $statement->execute();
    $statement->closeCursor();

    // Display the Product List page
    include('buy_thank_you.php');
}