<?php
include "dbcon.php";

$id = $_GET['id'] ?? '';
$name = '';
$email = '';
$error = '';

if ($id) {
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    if ($row = $result->fetch_assoc()) {
        $name = $row['name'];
        $email = $row['email'];
    }
    $stmt->close();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = trim($_POST["name"] ?? "");
    $email = trim($_POST["email"] ?? "");

    if ($name === "" || $email === "") {
        $error = "Please fill in all fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        $stmt = $conn->prepare("UPDATE users SET name = ?, email = ? WHERE id = ?");
        $stmt->bind_param("ssi", $name, $email, $id);
        if ($stmt->execute()) {
            header("Location: index.php?msg=updated");
            exit();
        } else {
            $error = "Error updating user.";
        }
        $stmt->close();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit User</title>
    <style>
        body { margin: 0; padding: 0; background: #eef3f8; font-family: Arial, sans-serif; }
        .container { width: 450px; max-width: 90%; margin: 80px auto; background: white; padding: 30px; border-radius: 5px; box-shadow: 0 2px 10px rgba(0,0,0,0.08); }
        h1 { text-align: center; font-size: 22px; color: #222; margin-top: 0; margin-bottom: 25px; }
        label { display: block; font-size: 12px; font-weight: bold; margin-bottom: 5px; color: #333; }
        input { width: 100%; height: 32px; padding: 7px 9px; margin-bottom: 13px; border: 1px solid #d0d0d0; border-radius: 3px; font-size: 12px; outline: none; }
        input:focus { border-color: #1677ff; }
        .submit-btn { width: 100%; height: 32px; border: none; border-radius: 3px; background: #15ff00; color: #222; font-size: 11px; font-weight: bold; cursor: pointer; }
        .submit-btn:hover { background: #00e000; }
        .alert { padding: 9px; margin-bottom: 20px; border-radius: 3px; font-size: 12px; text-align: center; background: #f8d7da; color: #842029; }
        .back-link { display: block; text-align: center; margin-top: 15px; font-size: 12px; text-decoration: none; color: #0879f9; }
    </style>
</head>
<body>
<div class="container">
    <h1>Edit User</h1>
    <?php if ($error !== ""): ?>
        <div class="alert"><?php echo $error; ?></div>
    <?php endif; ?>
    <form action="" method="POST">
        <label>Full Name</label>
        <input type="text" name="name" value="<?php echo htmlspecialchars($name); ?>">
        
        <label>Email Address</label>
        <input type="text" name="email" value="<?php echo htmlspecialchars($email); ?>">
        
        <button type="submit" class="submit-btn">Update User</button>
    </form>
    <a href="index.php" class="back-link">Back to Home</a>
</div>
</body>
</html>