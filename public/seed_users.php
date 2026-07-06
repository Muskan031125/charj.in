<?php
/**
 * Charj.in — User Seeder
 * Creates 1 admin + 1 customer account.
 * Run once: http://localhost/Charj/public/seed_users.php
 */

$pdo = new PDO('mysql:host=localhost;dbname=u504377054_charj;charset=utf8mb4', 'root', '');
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$users = [
    [
        'name'          => 'Charj Admin',
        'email'         => 'admin@charj.in',
        'password_hash' => password_hash('Admin@1234', PASSWORD_BCRYPT),
        'phone'         => '9999999999',
        'role'          => 'admin',
        'status'        => 'active',
    ],
    [
        'name'          => 'Test Customer',
        'email'         => 'customer@charj.in',
        'password_hash' => password_hash('Customer@1234', PASSWORD_BCRYPT),
        'phone'         => '8888888888',
        'role'          => 'customer',
        'status'        => 'active',
    ],
];

$results = [];
foreach ($users as $u) {
    $exists = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $exists->execute([$u['email']]);
    if ($exists->fetch()) {
        $results[] = ['email' => $u['email'], 'status' => 'already exists'];
        continue;
    }
    $pdo->prepare("INSERT INTO users (name, email, password_hash, phone, role, status) VALUES (?,?,?,?,?,?)")
        ->execute([$u['name'], $u['email'], $u['password_hash'], $u['phone'], $u['role'], $u['status']]);
    $results[] = ['email' => $u['email'], 'status' => 'created'];
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>User Seeder — Charj.in</title>
<style>
  body { font-family: sans-serif; max-width: 600px; margin: 60px auto; background: #f1f3f4; }
  .card { background: white; border: 1px solid #e4e4e7; padding: 2rem; }
  h1 { font-size: 1.2rem; color: #1B1C25; margin: 0 0 1.5rem; letter-spacing: .05em; text-transform: uppercase; font-size: .85rem; }
  table { width: 100%; border-collapse: collapse; margin-bottom: 1.5rem; }
  th { text-align: left; font-size: .65rem; text-transform: uppercase; letter-spacing: .15em; color: #999; border-bottom: 2px solid #e4e4e7; padding: .5rem 0; }
  td { padding: .6rem 0; border-bottom: 1px solid #f4f4f5; font-size: .85rem; }
  .role-admin { color: #006044; font-weight: 600; }
  .role-customer { color: #2563eb; font-weight: 600; }
  .created { color: #16a34a; }
  .exists { color: #999; }
  .creds { background: #f8f8f8; border: 1px solid #e4e4e7; padding: 1rem; font-size: .8rem; line-height: 2; }
  .creds strong { display: inline-block; width: 90px; color: #666; font-size: .65rem; text-transform: uppercase; letter-spacing: .1em; }
  a { color: #006044; text-decoration: none; font-weight: 600; font-size: .8rem; }
</style>
</head>
<body>
<div class="card">
  <h1>Charj.in — User Seeder</h1>
  <table>
    <thead><tr><th>Email</th><th>Role</th><th>Result</th></tr></thead>
    <tbody>
    <?php foreach ($results as $r): ?>
    <tr>
      <td><?= htmlspecialchars($r['email']) ?></td>
      <td class="role-<?= strpos($r['email'],'admin') !== false ? 'admin' : 'customer' ?>">
        <?= strpos($r['email'],'admin') !== false ? 'Admin' : 'Customer' ?>
      </td>
      <td class="<?= $r['status'] === 'created' ? 'created' : 'exists' ?>">
        <?= $r['status'] === 'created' ? '✓ Created' : '— Already exists' ?>
      </td>
    </tr>
    <?php endforeach; ?>
    </tbody>
  </table>

  <div class="creds">
    <div><strong>Admin login</strong> <a href="/Charj/public/index.php/admin/login">/admin/login</a></div>
    <div><strong>Email</strong> admin@charj.in</div>
    <div><strong>Password</strong> Admin@1234</div>
    <br>
    <div><strong>Customer</strong> <a href="/Charj/public/index.php/login">/login</a></div>
    <div><strong>Email</strong> customer@charj.in</div>
    <div><strong>Password</strong> Customer@1234</div>
  </div>
</div>
</body>
</html>
