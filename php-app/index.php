<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type, Authorization");

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(204);
    exit;
}

$host = getenv('DB_HOST');
$dbname = getenv('DB_NAME');
$username = getenv('DB_USER');
$password = getenv('DB_PASSWORD');

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_PERSISTENT => true
    ]);

    $pdo->exec("CREATE TABLE IF NOT EXISTS contacts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        name VARCHAR(255) NOT NULL,
        email VARCHAR(255) NOT NULL,
        phone VARCHAR(15) NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    if ($_POST['action'] === 'add') {
        $name = $_POST['name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';

        if ($name && $email && $phone) {
            $stmt = $pdo->prepare("INSERT INTO contacts (name, email, phone) VALUES (?, ?, ?)");
            $stmt->execute([$name, $email, $phone]);
        }
    } elseif ($_POST['action'] === 'delete' && isset($_POST['id'])) {
        $id = $_POST['id'];
        $stmt = $pdo->prepare("DELETE FROM contacts WHERE id = ?");
        $stmt->execute([$id]);
    }

    header("Location: " . $_SERVER['PHP_SELF']);
    exit;
}

$contacts = $pdo->query("SELECT id, name, email, phone FROM contacts")->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,100;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900&family=Raleway:ital,wght@0,100..900;1,100..900&display=swap"
        rel="stylesheet">
    <title>Contacts Manager</title>
</head>
<body style="font-family: Poppins, sans-serif; margin: 0; padding: 0; background-color: #f4f4f4;">
    <header style="display: flex; justify-content: space-between; align-items: center; background: #333; color: white; padding: 15px;">
        <h1 style="margin: 0;">Contacts Manager</h1>
        <nav>
            <a href="#" onclick="showForm()" style="color: white; margin-right: 15px;">Add Contact</a>
            <a href="#" onclick="showContacts()" style="color: white;">View Contacts</a>
        </nav>
    </header>

    <main style="max-width: 600px; margin: 20px auto; background: white; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);">
        <div id="form-section">
            <h2>Add Contact</h2>
            <form method="POST">
                <input type="hidden" name="action" value="add">
                <label>Name:</label>
                <input type="text" name="name" required style="width: 100%; padding: 8px; margin: 5px 0;">
                <label>Email:</label>
                <input type="email" name="email" required style="width: 100%; padding: 8px; margin: 5px 0;">
                <label>Phone:</label>
                <input type="text" name="phone" required style="width: 100%; padding: 8px; margin: 5px 0;">
                <button type="submit" style="background: green; color: white; padding: 10px; border: none; margin-top: 10px; cursor: pointer;">Submit</button>
            </form>
        </div>

        <div id="contacts-section" style="display: none;">
            <h2>Contacts</h2>
            <table border="1" width="100%" style="border-collapse: collapse;">
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Phone</th>
                    <th>Action</th>
                </tr>
                <?php foreach ($contacts as $contact): ?>
                <tr>
                    <td><?= htmlspecialchars($contact['name']) ?></td>
                    <td><?= htmlspecialchars($contact['email']) ?></td>
                    <td><?= htmlspecialchars($contact['phone']) ?></td>
                    <td>
                        <form method="POST" style="margin: 0; display: inline;">
                            <input type="hidden" name="action" value="delete">
                            <input type="hidden" name="id" value="<?= $contact['id'] ?>">
                            <button type="submit" style="background: red; color: white; padding: 5px; border: none; cursor: pointer;">Delete</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </main>

    <script>
        function showForm() {
            document.getElementById('form-section').style.display = 'block';
            document.getElementById('contacts-section').style.display = 'none';
        }

        function showContacts() {
            document.getElementById('form-section').style.display = 'none';
            document.getElementById('contacts-section').style.display = 'block';
        }
    </script>
</body>
</html>
