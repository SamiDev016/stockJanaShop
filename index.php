<?php
session_start();

// تهيئة الجلسة إذا لم تكن موجودة
if (!isset($_SESSION['products'])) {
    $_SESSION['products'] = [];
}

// إضافة منتج جديد
if (isset($_POST['add_product'])) {
    $name = $_POST['name'];
    $purchase_price = floatval($_POST['purchase_price']);
    $quantity = intval($_POST['quantity']);
    
    $_SESSION['products'][] = [
        'name' => $name,
        'purchase_price' => $purchase_price,
        'quantity' => $quantity,
        'total_profit' => 0 // إجمالي الربح الصافي
    ];
}

// بيع منتج
if (isset($_POST['sell_product'])) {
    $index = $_POST['index'];
    $sell_price = floatval($_POST['sell_price']);
    $sell_quantity = intval($_POST['sell_quantity']);

    if ($sell_quantity <= $_SESSION['products'][$index]['quantity']) {
        $_SESSION['products'][$index]['quantity'] -= $sell_quantity;
        $profit = ($sell_price - $_SESSION['products'][$index]['purchase_price']) * $sell_quantity;
        $_SESSION['products'][$index]['total_profit'] += $profit;
    }
}
?>
<!DOCTYPE html>
<html lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>إدارة المخزون</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
    <h2 class="text-center">إدارة المخزون</h2>
    
    <!-- إضافة منتج جديد -->
    <form method="post" class="mb-3">
        <div class="input-group">
            <input type="text" name="name" class="form-control" placeholder="اسم المنتج" required>
            <input type="number" name="purchase_price" class="form-control" placeholder="سعر الشراء" step="0.01" required>
            <input type="number" name="quantity" class="form-control" placeholder="الكمية" required>
            <button type="submit" name="add_product" class="btn btn-primary">إضافة</button>
        </div>
    </form>

    <!-- جدول المنتجات -->
    <table class="table table-bordered text-center">
        <thead>
            <tr>
                <th>اسم المنتج</th>
                <th>سعر الشراء</th>
                <th>الكمية المتاحة</th>
                <th>بيع</th>
                <th>الربح الصافي</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($_SESSION['products'] as $index => $product): ?>
            <tr>
                <td><?= htmlspecialchars($product['name']) ?></td>
                <td><?= $product['purchase_price'] ?> د.ج</td>
                <td><?= $product['quantity'] ?></td>
                <td>
                    <form method="post" class="d-flex">
                        <input type="hidden" name="index" value="<?= $index ?>">
                        <input type="number" name="sell_price" class="form-control me-2" placeholder="سعر البيع" step="0.01" required>
                        <input type="number" name="sell_quantity" class="form-control me-2" placeholder="الكمية" required>
                        <button type="submit" name="sell_product" class="btn btn-success">بيع</button>
                    </form>
                </td>
                <td><?= $product['total_profit'] ?> د.ج</td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>
</html>
