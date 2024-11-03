<?php   
require_once 'core/dbConfig.php';
require_once 'core/models.php';

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];
$user = fetchUserById($pdo, $userId);
$Chefs = fetchAllChefs($pdo);
$Dishess = fetchAllDishess($pdo);

$successMessage = '';
$errorMessage = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['insertNewChefBtn'])) {
    $name = trim($_POST['ChefName']);
    $specialty = trim($_POST['ChefSpecialty']);
    
    if (!empty($name) && !empty($specialty)) {
        if (insertChef($pdo, $name, $specialty, $userId)) {
            $successMessage = "Chef added successfully!";
            $Chefs = fetchAllChefs($pdo); 
        } else {
            $errorMessage = "Failed to add Chef.";
        }
    } else {
        $errorMessage = "Please fill in all fields.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['insertNewDishesBtn'])) {
    $menu = trim($_POST['DishesMenu']);
    $ChefId = trim($_POST['ChefID']);
    $cost = (int)$_POST['DishesCost'];
    
    if (!empty($menu) && !empty($ChefId) && $cost >= 0) {
        if (insertDishes($pdo, $menu, $ChefId, $cost, $userId)) {
            $successMessage = "Dishes added successfully!";
            $Dishess = fetchAllDishess($pdo); 
        } else {
            $errorMessage = "Failed to add Dishes.";
        }
    } else {
        $errorMessage = "Please fill in all fields.";
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteDishesBtn'])) {
    $DishesId = $_POST['Dishes_id'];
    deleteDishes($pdo, $DishesId); 
    header('Location: index.php'); 
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['deleteChefBtn'])) {
    $ChefId = $_POST['Chef_id'];
    deleteChef($pdo, $ChefId); 
    header('Location: index.php'); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Food Shop Management</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="header">
        <h1>Welcome, <?php echo htmlspecialchars($user['Username']); ?>!</h1>
        <form action="logout.php" method="POST" style="display:inline;">
            <button type="submit" name="logoutBtn" class="small-button">Logout</button>
        </form>
    </div>

    <div class="container">
        <?php if ($successMessage): ?>
            <div class="message success"><?php echo $successMessage; ?></div>
        <?php endif; ?>
        <?php if ($errorMessage): ?>
            <div class="message error"><?php echo $errorMessage; ?></div>
        <?php endif; ?>

        <h2>Add New Chef</h2>
        <form action="" method="POST">
            <input type="text" name="ChefName" placeholder="Chef Name" required>
            <input type="text" name="ChefSpecialty" placeholder="Specialty" required>
            <button type="submit" name="insertNewChefBtn">Add Chef</button>
        </form>

        <h2>Chef List</h2>
        <table>
            <tr>
                <th>Name</th>
                <th>Specialty</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($Chefs as $Chef): ?>
                <tr>
                    <td><?php echo htmlspecialchars($Chef['Chef_Name']); ?></td>
                    <td><?php echo htmlspecialchars($Chef['Chef_Specialty']); ?></td>
                    <td>
                        <a href="editChef.php?Chef_ID=<?php echo $Chef['Chef_ID']; ?>">Edit</a>
                        <form action="" method="POST" style="display:inline;">
                            <input type="hidden" name="Chef_id" value="<?php echo $Chef['Chef_ID']; ?>">
                            <button type="submit" name="deleteChefBtn" onclick="return confirm('Are you sure you want to delete this Chef?');">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>

        <h2>Add New Dishes</h2>
        <form action="" method="POST">
            <input type="text" name="DishesMenu" placeholder="Dishes Menu" required>
            <input type="number" name="DishesCost" placeholder="Cost" required>
            <select name="ChefID" required>
                <option value="">Select Chef</option>
                <?php foreach ($Chefs as $Chef): ?>
                    <option value="<?php echo $Chef['Chef_ID']; ?>"><?php echo htmlspecialchars($Chef['Chef_Name']); ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" name="insertNewDishesBtn">Add Dishes</button>
        </form>

        <h2>Dishes List</h2>
        <table>
            <tr>
                <th>Menu</th>
                <th>Cost</th>
                <th>Added By</th>
                <th>Last Updated By</th>
                <th>Last Updated At</th>
                <th>Actions</th>
            </tr>
            <?php foreach ($Dishess as $Dishes): ?>
                <tr>
                    <td><?php echo htmlspecialchars($Dishes['Dishes_Menu']); ?></td>
                    <td>$<?php echo htmlspecialchars($Dishes['Dishes_Cost']); ?></td>
                    <td><?php echo htmlspecialchars($Dishes['Added_By']); ?></td>
                    <td>
                        <?php 
                            $lastUpdatedByUser = fetchUserById($pdo, $Dishes['updated_by']);
                            echo htmlspecialchars($lastUpdatedByUser ? $lastUpdatedByUser['Username'] : 'Unknown');
                        ?>
                    </td>
                    <td><?php echo htmlspecialchars($Dishes['last_updated'] ?? 'Not updated'); ?></td>
                    <td>
                        <a href="editDishes.php?Dishes_id=<?php echo $Dishes['Dishes_ID']; ?>">Edit</a>
                        <form action="" method="POST" style="display:inline;">
                            <input type="hidden" name="Dishes_id" value="<?php echo $Dishes['Dishes_ID']; ?>">
                            <button type="submit" name="deleteDishesBtn" onclick="return confirm('Are you sure you want to delete this Dishes?');">Delete</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </table>
    </div>
</body>
</html>