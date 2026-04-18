<?php
session_start();

$host = 'localhost';
$db = 'art_gallery';
$user = 'root';
$pass = 'amna12345';
$dsn = "mysql:host=$host;dbname=$db;charset=utf8mb4";

try {
    $pdo = new PDO($dsn, $user, $pass, [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (Exception $e) {
    die("DB connection failed: " . $e->getMessage());
}

$artworksStmt = $pdo->query("SELECT artid, title, price FROM artwork ORDER BY title");
$artworks = $artworksStmt->fetchAll(PDO::FETCH_ASSOC);

$customersStmt = $pdo->query("SELECT custid, fname, lname FROM customer ORDER BY fname");
$customers = $customersStmt->fetchAll(PDO::FETCH_ASSOC);

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$message = '';

// Handle add to cart
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_to_cart'])) {
    $custid = $_POST['custid'] ?? '';
    $artid = $_POST['artid'] ?? '';
    $quantity = intval($_POST['quantity'] ?? 1);
    $quantity = max(1, $quantity);

    $artworkStmt = $pdo->prepare("SELECT price, gid FROM artwork WHERE artid = ?");
    $artworkStmt->execute([$artid]);
    $artwork = $artworkStmt->fetch(PDO::FETCH_ASSOC);

    if ($artwork && $custid && $artid) {
        $price = $artwork['price'];
        $gid = $artwork['gid'];

        $_SESSION['cart'][] = [
            'custid' => $custid,
            'artid' => $artid,
            'quantity' => $quantity,
            'price' => $price,
            'gid' => $gid,
        ];
        $message = "Artwork added to cart!";
    } else {
        $message = "Failed to add artwork to cart. Please check your selections.";
    }
}

// Handle order submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit_orders']) && !empty($_SESSION['cart'])) {
    $orderDate = date('Y-m-d');
    $stmt = $pdo->prepare("INSERT INTO orders (custid, artid, order_date, quantity, price, gid) VALUES (?, ?, ?, ?, ?, ?)");

    foreach ($_SESSION['cart'] as $item) {
        $stmt->execute([
            $item['custid'],
            $item['artid'],
            $orderDate,
            $item['quantity'],
            $item['price'],
            $item['gid']
        ]);
    }
    $_SESSION['cart'] = [];
    $message = "Order placed successfully!";
}

$total = 0;
foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Art Gallery - Place Order</title>
<link href="https://fonts.googleapis.com/css2?family=Aclonica&family=Raleway:wght@400;700&display=swap" rel="stylesheet" />
<style>
  /* Reset and base */
  * {
      box-sizing: border-box;
  }
  body, html {
      margin: 0; padding: 0; height: 100%;
      font-family: 'Raleway', sans-serif;
      background-color: #0a0a0a;
      color: #f8a1c1; /* soft pink */
      display: flex;
      justify-content: center;
      align-items: flex-start;
      min-height: 100vh;
      padding: 40px 15px;
  }

  /* Background gradient fade */
  body::before {
      content: "";
      position: fixed;
      top: 0; left: 0; right: 0; bottom: 0;
      background: radial-gradient(circle at top right, #ff2994dd, #0a0a0a 80%);
      opacity: 0.35;
      z-index: 0;
      pointer-events: none;
  }

  .container {
      position: relative;
      z-index: 1;
      max-width: 900px;
      width: 100%;
      background: #121212;
      border-radius: 15px;
      padding: 45px 55px;
      box-shadow:
          0 0 20px 3px #ff2994aa,
          inset 0 0 40px #ff2994bb;
      border: 2px solid #ff2994;
  }

  h1 {
      font-family: 'Aclonica', sans-serif;
      font-size: 3rem;
      text-align: center;
      margin-bottom: 35px;
      color: #ff2994;
      text-shadow: 0 0 15px #ff2994bb;
      letter-spacing: 3px;
  }

  label {
      display: block;
      margin-top: 12px;
      font-weight: 700;
      color: #ff7bb5;
      letter-spacing: 0.8px;
      font-size: 1.1rem;
  }

  select, input[type=number] {
      width: 100%;
      margin-top: 6px;
      padding: 12px 14px;
      border-radius: 10px;
      border: none;
      font-size: 1.1rem;
      background: #1b1b1b;
      color: #ff8cc0;
      box-shadow: inset 0 0 12px #ff2994aa;
      transition: background 0.3s ease, color 0.3s ease;
  }

  select:focus, input[type=number]:focus {
      outline: none;
      background: #ff2994;
      color: #1b1b1b;
      box-shadow:
          0 0 12px 4px #ff2994cc,
          inset 0 0 18px 6px #ff2994ff;
  }

  .btn {
      margin-top: 20px;
      background: linear-gradient(45deg, #ff2994, #ff60b1);
      border: none;
      padding: 14px 0;
      width: 100%;
      font-weight: 800;
      font-size: 1.2rem;
      color: #1b1b1b;
      border-radius: 15px;
      cursor: pointer;
      box-shadow:
          0 6px 25px #ff2994cc,
          0 0 15px #ff60b1cc;
      transition: all 0.3s ease;
      user-select: none;
      text-transform: uppercase;
      letter-spacing: 1.5px;
  }
  .btn:hover {
      background: linear-gradient(45deg, #ff3aab, #ff4a9f);
      box-shadow:
          0 10px 35px #ff3aabdd,
          0 0 30px #ff4a9fdd;
      transform: translateY(-4px);
  }

  .artwork-list {
      display: grid;
      grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
      gap: 25px;
      margin-bottom: 40px;
  }

  .artwork-item {
      background: #1f1f1f;
      padding: 20px 20px 30px 20px;
      border-radius: 15px;
      box-shadow: 0 0 18px #ff2994aa;
      display: flex;
      flex-direction: column;
      justify-content: space-between;
  }

  .artwork-title {
      font-weight: 700;
      font-size: 1.3rem;
      color: #ff7bb5;
      margin-bottom: 8px;
      text-shadow: 0 0 8px #ff2994bb;
  }

  .artwork-price {
      color: #ff65b0;
      font-size: 1.1rem;
      margin-bottom: 14px;
      font-weight: 600;
  }

  /* Cart Table */
  table {
      width: 100%;
      border-collapse: collapse;
      color: #f7a2ca;
      font-size: 1rem;
      text-align: left;
      border-radius: 15px;
      overflow: hidden;
      background: #1f1f1f;
      box-shadow: 0 0 25px #ff2994aa;
      margin-bottom: 30px;
  }

  thead tr {
      background: #ff2994cc;
      color: #fff;
      letter-spacing: 1.2px;
  }

  th, td {
      padding: 15px 20px;
      border-bottom: 1px solid #ff29949c;
  }

  tfoot tr {
      background: #ff2994dd;
      color: #fff;
      font-weight: 700;
      letter-spacing: 1.1px;
  }

  .message {
      text-align: center;
      font-weight: 700;
      margin-bottom: 25px;
      color: #ff2994;
      font-size: 1.2rem;
      text-shadow: 0 0 8px #ff2994bb;
  }

  /* Responsive */
  @media (max-width: 600px) {
      .container {
          padding: 25px 20px;
      }
      .artwork-list {
          grid-template-columns: 1fr;
      }
  }
</style>
</head>
<body>
<div class="container">
    <h1>Place Your Order</h1>

    <?php if ($message): ?>
        <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <div class="artwork-list">
        <?php foreach ($artworks as $art): ?>
            <form method="POST" class="artwork-item">
                <div class="artwork-title"><?= htmlspecialchars($art['title']) ?></div>
                <div class="artwork-price">$<?= number_format($art['price'], 2) ?></div>

                <label for="custid-<?= $art['artid'] ?>">Select Customer</label>
                <select id="custid-<?= $art['artid'] ?>" name="custid" required>
                    <option value="" disabled selected>-- Choose Customer --</option>
                    <?php foreach ($customers as $cust): ?>
                        <option value="<?= $cust['custid'] ?>">
                            <?= htmlspecialchars($cust['fname'] . ' ' . $cust['lname']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <label for="quantity-<?= $art['artid'] ?>">Quantity</label>
                <input
                    type="number"
                    id="quantity-<?= $art['artid'] ?>"
                    name="quantity"
                    value="1"
                    min="1"
                    required
                />

                <input type="hidden" name="artid" value="<?= $art['artid'] ?>" />
                <button type="submit" name="add_to_cart" class="btn">Add to Cart</button>
            </form>
        <?php endforeach; ?>
    </div>

    <?php if (!empty($_SESSION['cart'])): ?>
        <table>
            <thead>
                <tr>
                    <th>Artwork ID</th>
                    <th>Customer ID</th>
                    <th>Quantity</th>
                    <th>Price Per Unit</th>
                    <th>Subtotal</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($_SESSION['cart'] as $item): ?>
                    <tr>
                        <td><?= htmlspecialchars($item['artid']) ?></td>
                        <td><?= htmlspecialchars($item['custid']) ?></td>
                        <td><?= htmlspecialchars($item['quantity']) ?></td>
                        <td>$<?= number_format($item['price'], 2) ?></td>
                        <td>$<?= number_format($item['price'] * $item['quantity'], 2) ?></td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="4" style="text-align: right;">Total:</td>
                    <td>$<?= number_format($total, 2) ?></td>
                </tr>
            </tfoot>
        </table>

        <form method="POST" style="text-align: center;">
            <button type="submit" name="submit_orders" class="btn">Submit Order</button>
        </form>
    <?php else: ?>
        <p style="text-align:center; color: #ff5cad;">Your cart is empty. Add artworks to cart above.</p>
    <?php endif; ?>
</div>
</body>
</html>
