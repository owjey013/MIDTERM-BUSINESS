<?php
// Fetch user by ID
function fetchUserById($pdo, $userId) {
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE User_ID = :id");
    $stmt->execute(['id' => $userId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Login function
function loginUser($pdo, $username, $password) {
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE Username = :username");
    $stmt->execute(['username' => $username]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && isset($user['Password']) && password_verify($password, $user['Password'])) {
        return $user;
    }
    return false;
}

// Register user
function registerUser($pdo, $username, $password) {
    // Check if username already exists
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE Username = :username");
    $stmt->execute(['username' => $username]);
    if ($stmt->fetch()) {
        return false; // Username already exists
    }

    // Hash the password
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    
    // Insert the new user
    $stmt = $pdo->prepare("INSERT INTO Users (Username, Password) VALUES (:username, :password)");
    return $stmt->execute(['username' => $username, 'password' => $hashedPassword]);
}

// Fetch all Chefs
function fetchAllChefs($pdo) {
    $stmt = $pdo->query("SELECT * FROM Chef");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch all Dishess
function fetchAllDishess($pdo) {
    $stmt = $pdo->query("SELECT d.Dishes_ID, d.Dishes_Menu, d.Dishes_Cost, c.Chef_Name, d.date_added, d.last_updated, u.Username AS Added_By, d.updated_by
                         FROM Dishes d
                         LEFT JOIN Chef c ON d.Chef_ID = c.Chef_ID
                         LEFT JOIN Users u ON d.added_by = u.User_ID");
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fetch Dishes by ID
function fetchDishesById($pdo, $DishesId) {
    $stmt = $pdo->prepare("SELECT * FROM Dishes WHERE Dishes_ID = :DishesId");
    $stmt->execute(['DishesId' => $DishesId]);
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Update Dishes
function updateDishes($pdo, $DishesId, $menu, $cost, $userId) {
    $stmt = $pdo->prepare("UPDATE Dishes SET Dishes_Menu = :menu, Dishes_Cost = :cost, updated_by = :updatedBy, last_updated = CURRENT_TIMESTAMP WHERE Dishes_ID = :DishesId");
    return $stmt->execute(['menu' => $menu, 'cost' => $cost, 'updatedBy' => $userId, 'DishesId' => $DishesId]);
}

// Insert Chef
function insertChef($pdo, $name, $specialty, $userId) {
    // Check if the user ID exists before inserting the Chef
    $stmt = $pdo->prepare("SELECT * FROM Users WHERE User_ID = :userId");
    $stmt->execute(['userId' => $userId]);
    if (!$stmt->fetch()) {
        throw new Exception("User ID does not exist.");
    }

    $stmt = $pdo->prepare("INSERT INTO Chef (Chef_Name, Chef_Specialty, added_by) VALUES (:name, :specialty, :added_by)");
    return $stmt->execute(['name' => $name, 'specialty' => $specialty, 'added_by' => $userId]);
}

// Insert Dishes
function insertDishes($pdo, $menu, $ChefId, $cost, $userId) {
    $stmt = $pdo->prepare("INSERT INTO Dishes (Dishes_Menu, Chef_ID, Dishes_Cost, added_by) VALUES (:menu, :ChefId, :cost, :added_by)");
    return $stmt->execute(['menu' => $menu, 'ChefId' => $ChefId, 'cost' => $cost, 'added_by' => $userId]);
}

// Update Chef
function updateChef($pdo, $name, $specialty, $ChefId, $userId) {
    $stmt = $pdo->prepare("UPDATE Chef SET Chef_Name = :name, Chef_Specialty = :specialty, last_updated = CURRENT_TIMESTAMP, updated_by = :updatedBy WHERE Chef_ID = :ChefId");
    return $stmt->execute(['name' => $name, 'specialty' => $specialty, 'ChefId' => $ChefId, 'updatedBy' => $userId]);
}

// Delete Chef
function deleteChef($pdo, $ChefId) {
    // First, delete related Dishes records
    $stmt = $pdo->prepare("DELETE FROM Dishes WHERE Chef_ID = :ChefId");
    $stmt->execute(['ChefId' => $ChefId]);

    // Then, delete the Chef record
    $stmt = $pdo->prepare("DELETE FROM Chef WHERE Chef_ID = :ChefId");
    return $stmt->execute(['ChefId' => $ChefId]);
}

// Delete Dishes
function deleteDishes($pdo, $DishesId) {
    $stmt = $pdo->prepare("DELETE FROM Dishes WHERE Dishes_ID = :DishesId");
    return $stmt->execute(['DishesId' => $DishesId]);
}
?>