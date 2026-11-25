<?php
// Run ONCE after importing schema.sql to create the admin login,
// then delete this file. Default credentials: admin / admin123 - change them after first login.
require __DIR__ . '/db.php';

$username = 'admin';
$password = 'admin123';

$stmt = db()->prepare('SELECT COUNT(*) AS n FROM users WHERE username = ?');
$stmt->execute([$username]);
if ($stmt->fetch()['n'] > 0) {
    exit('Admin user already exists. Delete this file.');
}

$stmt = db()->prepare('INSERT INTO users (username, pass_hash) VALUES (?, ?)');
$stmt->execute([$username, password_hash($password, PASSWORD_DEFAULT)]);
echo "Admin user created (admin / admin123). Change the password after first login and delete setup.php.";
