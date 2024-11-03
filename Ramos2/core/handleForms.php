<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once '../core/dbConfig.php'; 
require_once '../core/models.php'; 

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../login.php');
    exit();
}

$userId = $_SESSION['user_id'];

if (isset($_POST['insertCBtn'])) {
    $query = insertChef($pdo, $_POST['Chef_Name'], $_POST['Chef_Specialty'], $userId);
    
    if ($query) {
        header("Location: ../index.php");
        exit();
    } else {
        echo "Insertion failed: " . implode(", ", $pdo->errorInfo());
    }
}

if (isset($_POST['editCBtn'])) {
    $query = updateChef($pdo, $_POST['Chef_Name'], $_POST['Chef_Specialty'], $_GET['Chef_ID'], $userId);
    
    if ($query) {
        header("Location: ../index.php");
        exit();
    } else {
        echo "Edit failed: " . implode(", ", $pdo->errorInfo());
    }
}

if (isset($_POST['deleteCBtn'])) {
    $ChefId = $_POST['Chef_id']; 
    
    if ($query) {
        header("Location: ../index.php");
        exit();
    } else {
        echo "Deletion failed: " . implode(", ", $pdo->errorInfo());
    }
}

if (isset($_POST['insertNewDBtn'])) {
    $query = insertDishes($pdo, $_POST['Dishes_Menu'], $_POST['Chef_ID'], $_POST['Dishes_Cost'], $userId);
    
    if ($query) {
        header("Location: ../viewDishes.php?Chef_ID=" . $_POST['Chef_ID']);
        exit();
    } else {
        echo "Insertion failed: " . implode(", ", $pdo->errorInfo());
    }
}

if (isset($_POST['editDBtn'])) {
    $query = updateDishes($pdo, $_POST['Dishes_Menu'], $_POST['Dishes_Cost'], $_GET['Dishes_ID'], $userId);
    
    if ($query) {
        header("Location: ../viewDishes.php?Chef_ID=" . $_GET['Chef_ID']);
        exit();
    } else {
        echo "Update failed: " . implode(", ", $pdo->errorInfo());
    }
}

if (isset($_POST['deleteDBtn'])) {
    $DishesId = $_POST['Dishes_ID'];
    $query = deleteDishes($pdo, $DishesId);
    
    if ($query) {
        header("Location: ../viewDishes.php?Chef_ID=" . $_POST['Chef_ID']);
        exit();
    } else {
        echo "Deletion failed: " . implode(", ", $pdo->errorInfo());
    }
}
?>