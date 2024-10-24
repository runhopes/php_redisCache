<?php
require 'redis.php';

$redisCache = new redisConnectionCache();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];

    if ($_POST['action'] === 'add') {
        $redisCache->addUser($id, $name, $email);
        echo "<p>Kullanıcı eklendi: $name</p>";
    } elseif ($_POST['action'] === 'update') {
        $redisCache->updateUser($id, $name, $email);
        echo "<p>Kullanıcı güncellendi: $name</p>";
    }
}

if (isset($_GET['delete_id'])) {
    $redisCache->deleteUser($_GET['delete_id']);
    echo "<p>Kullanıcı silindi.</p>";
}

$users = $redisCache->getAllUsers();
?>

<!DOCTYPE html>
<html lang="tr">
<head>
    <meta charset="UTF-8">
    <title>PHP Redis Development</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Kullanıcı Ekle</h2>
    <form method="POST">
        ID: <input type="text" name="id" required><br>
        İsim: <input type="text" name="name" required><br>
        Email: <input type="email" name="email" required><br>
        <input type="hidden" name="action" value="add">
        <button type="submit">Ekle</button>
    </form>

    <h2>Kullanıcıları Listele</h2>
    <table>
        <tr>
            <th>ID</th>
            <th>İsim</th>
            <th>Email</th>
            <th>İşlemler</th>
        </tr>
        <?php foreach ($users as $key => $user){?>
            <tr>
                <td><?php echo htmlspecialchars(basename($key)); ?></td>
                <td><?php echo htmlspecialchars($user['name']); ?></td>
                <td><?php echo htmlspecialchars($user['email']); ?></td>
                <td>
                    <a href="?delete_id=<?php echo htmlspecialchars(basename($key)); ?>">Sil</a>
                    <form method="POST" style="display:inline;">
                        <input type="hidden" name="id" value="<?php echo htmlspecialchars(basename($key)); ?>">
                        <input type="hidden" name="name" value="<?php echo htmlspecialchars($user['name']); ?>">
                        <input type="hidden" name="email" value="<?php echo htmlspecialchars($user['email']); ?>">
                        <input type="hidden" name="action" value="update">
                        <button type="submit">Güncelle</button>
                    </form>
                </td>
            </tr>
        <?php } ?>
    </table>
</body>
</html>
