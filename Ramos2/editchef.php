<?php
require_once 'core/dbConfig.php';
require_once 'core/models.php';

session_start();


if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$userId = $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['updateChefBtn'])) {
    $ChefId = $_POST['Chef_id'];
    $name = trim($_POST['ChefName']);
    $specialty = trim($_POST['ChefSpecialty']);

    if (!empty($name) && !empty($specialty)) {
        $query = updateChef($pdo, $name, $specialty, $ChefId, $userId);
        
        if ($query) {
            header('Location: index.php'); 
            exit();
        } else {
            echo "Update failed: " . implode(", ", $pdo->errorInfo());
        }
    } else {
        echo "Please fill in all fields.";
    }
}

$ChefId = $_GET['Chef_ID'] ?? null; 
$stmt = $pdo->prepare("SELECT * FROM Chef WHERE Chef_ID = :ChefId");
$stmt->execute(['ChefId' => $ChefId]);
$Chef = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$Chef) {
    header('Location: index.php'); 
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Chef</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <h2>Edit Chef</h2>
    <form action="" method="POST">
        <input type="hidden" name="Chef_id" value="<?php echo htmlspecialchars($Chef['Chef_ID']); ?>">
        <label for="ChefName">Chef Name:</label>
        <input type="text" name="ChefName" value="<?php echo htmlspecialchars($Chef['Chef_Name']); ?>" required>
        <label for="ChefSpecialty">Specialty:</label>
        <input type="text" name="ChefSpecialty" value="<?php echo htmlspecialchars($Chef['Chef_Specialty']); ?>" required>
        <input type="submit" name="updateChefBtn" value="Update Chef">
    </form>
    <p><a href="index.php">Back to Food Shop Management</a></p>
</body>
</html>