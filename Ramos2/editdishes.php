<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateDishesBtn'])) {
    $DishesId = $_POST['Dishes_id'];
    $menu = trim($_POST['DishesMenu']);
    $ChefId = $_POST['ChefID'];
    $cost = (int)$_POST['DishesCost'];

    if (!empty($menu) && !empty($ChefId) && $cost >= 0) {
        $stmt = $pdo->prepare("UPDATE Dishes SET Dishes_Menu = :menu, Chef_ID = :ChefId, Dishes_Cost = :cost, updated_by = :userId, last_updated = CURRENT_TIMESTAMP WHERE Dishes_ID = :DishesId");
        if ($stmt->execute(['menu' => $menu, 'ChefId' => $ChefId, 'cost' => $cost, 'userId' => $userId, 'DishesId' => $DishesId])) {
            header('Location: index.php'); 
            exit();
        }
    }
}

$DishesId = $_GET['Dishes_id'] ?? null;
$Dishes = fetchDishesById($pdo, $DishesId);

if (!$Dishes) {
    header('Location: index.php'); 
    exit();
}

$Chefs = fetchAllChefs($pdo); 
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Dishes</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Edit Dishes</h2>
    <form action="" method="POST">
        <input type="hidden" name="Dishes_id" value="<?php echo htmlspecialchars($Dishes['Dishes_ID']); ?>">
        <label for="DishesMenu">Dishes Menu:</label>
        <input type="text" name="DishesMenu" value="<?php echo htmlspecialchars($Dishes['Dishes_Menu']); ?>" required>
        <label for="ChefID">Chef:</label>
        <select name="ChefID" required>
            <option value="">Select a Chef</option>
            <?php foreach ($Chefs as $Chef): ?>
                <option value="<?php echo htmlspecialchars($Chef['Chef_ID']); ?>" <?php echo $Chef['Chef_ID'] == $Dishes['Chef_ID'] ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($Chef['Chef_Name']); ?>
                </option>
            <?php endforeach; ?>
        </select>
        <label for="DishesCost">Dishes Cost:</label>
        <input type="number" name="DishesCost" value="<?php echo htmlspecialchars($Dishes['Dishes_Cost']); ?>" required>
        <input type="submit" name="updateDishesBtn" value="Update Dishes">
    </form>
    
    <h3>Last Updated By:</h3>
    <p>
        <?php 
            $lastUpdatedByUser = fetchUserById($pdo, $Dishes['updated_by']);
            echo htmlspecialchars($lastUpdatedByUser ? $lastUpdatedByUser['Username'] : 'Unknown');
        ?>
    </p>
    
    <h3>Last Updated At:</h3>
    <p><?php echo htmlspecialchars($Dishes['last_updated'] ?? 'Not updated'); ?></p>

    <p><a href="index.php">Back to Dishes Shop Management</a></p>
</body>
</html>