<?php
include("../configshoppingstore.php");

// Handle status updates
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $order_id = (int)$_POST['order_id'];
    $new_status = $_POST['status'];
    $valid_statuses = ['pending', 'processing', 'shipped', 'delivered', 'cancelled'];
    
    if (in_array($new_status, $valid_statuses)) {
        try {
            $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
            $stmt->execute([$new_status, $order_id]);
            echo "<script>window.location.href='index.php?page=orders';</script>";
            exit;
        } catch (Exception $e) {
            error_log($e->getMessage());
        }
    }
}

// Fetch all orders
$orders = [];
try {
    $stmt = $conn->query("SELECT * FROM orders ORDER BY created_at DESC");
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    error_log($e->getMessage());
}
?>

<div class="space-y-12">
    <!-- Header -->
    <div class="flex flex-col md:flex-row justify-between items-start md:items-end gap-6">
        <div class="space-y-2">
            <h2 class="font-serif text-5xl text-white">Acquisition Logs</h2>
            <p class="text-gray-500 text-sm tracking-[0.2em] uppercase font-bold">Monitor client transactions and shipping routes</p>
        </div>
        <div class="px-6 py-3 glass-dark rounded-full flex items-center gap-4 border border-gold/20">
            <i class="fas fa-shopping-bag text-gold"></i>
            <span class="text-[10px] font-black uppercase tracking-widest text-white"><?php echo count($orders); ?> Orders</span>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="glass-dark rounded-[40px] overflow-hidden luxury-shadow border border-white/5">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-white/5 bg-white/5">
                        <th class="px-8 py-6 text-[10px] uppercase tracking-[0.3em] text-gold font-black">Identifier</th>
                        <th class="px-8 py-6 text-[10px] uppercase tracking-[0.3em] text-gold font-black">Client Dossier</th>
                        <th class="px-8 py-6 text-[10px] uppercase tracking-[0.3em] text-gold font-black">Purchased items</th>
                        <th class="px-8 py-6 text-[10px] uppercase tracking-[0.3em] text-gold font-black">Financials</th>
                        <th class="px-8 py-6 text-[10px] uppercase tracking-[0.3em] text-gold font-black text-right">Status Action</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    <?php if (empty($orders)): ?>
                        <tr>
                            <td colspan="5" class="px-8 py-16 text-center text-gray-500 font-light italic">
                                No acquisition logs registered in the database.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($orders as $order): 
                            // Fetch items for this order
                            $items_stmt = $conn->prepare("
                                SELECT oi.*, p.productName 
                                FROM order_items oi 
                                JOIN product p ON p.id = oi.product_id 
                                WHERE oi.order_id = ?
                            ");
                            $items_stmt->execute([$order['id']]);
                            $items = $items_stmt->fetchAll(PDO::FETCH_ASSOC);

                            $item_details = [];
                            foreach ($items as $item) {
                                $item_details[] = htmlspecialchars($item['productName']) . " (x" . $item['quantity'] . ")";
                            }
                            $item_text = implode(", ", $item_details);
                        ?>
                        <tr class="hover:bg-white/[0.02] transition-colors group">
                            <!-- Identifier -->
                            <td class="px-8 py-6">
                                <span class="font-mono text-xs font-bold text-white">#FS-<?php echo str_pad($order['id'], 6, '0', STR_PAD_LEFT); ?></span>
                                <p class="text-[9px] text-gray-500 font-medium mt-1"><?php echo htmlspecialchars($order['created_at']); ?></p>
                            </td>

                            <!-- Client Dossier -->
                            <td class="px-8 py-6 space-y-1">
                                <p class="text-sm font-bold text-white"><?php echo htmlspecialchars($order['fullname']); ?></p>
                                <p class="text-[10px] text-gray-400 font-medium"><?php echo htmlspecialchars($order['phone']); ?></p>
                                <p class="text-[10px] text-gray-500 font-light"><?php echo htmlspecialchars($order['address'] . ", " . $order['city']); ?></p>
                            </td>

                            <!-- Items -->
                            <td class="px-8 py-6 max-w-xs">
                                <p class="text-xs text-gray-300 font-medium leading-relaxed line-clamp-2" title="<?php echo $item_text; ?>">
                                    <?php echo $item_text ?: 'No details registered'; ?>
                                </p>
                            </td>

                            <!-- Financials -->
                            <td class="px-8 py-6">
                                <p class="text-sm font-serif text-white font-bold">$<?php echo number_format($order['total'], 2); ?></p>
                                <p class="text-[9px] text-gray-500 font-medium uppercase tracking-widest mt-1"><?php echo strtoupper($order['payment_method']); ?></p>
                            </td>

                            <!-- Status Actions -->
                            <td class="px-8 py-6 text-right">
                                <form method="POST" action="index.php?page=orders" class="inline-block">
                                    <input type="hidden" name="order_id" value="<?php echo $order['id']; ?>">
                                    <input type="hidden" name="update_status" value="1">
                                    <select name="status" onchange="this.form.submit()" 
                                            class="bg-[#151515] border border-white/10 text-xs text-gray-300 rounded-xl px-4 py-2 hover:border-gold/50 focus:border-gold focus:outline-none cursor-pointer transition-all">
                                        <option value="pending" <?php echo $order['status'] === 'pending' ? 'selected' : ''; ?>>Pending</option>
                                        <option value="processing" <?php echo $order['status'] === 'processing' ? 'selected' : ''; ?>>Processing</option>
                                        <option value="shipped" <?php echo $order['status'] === 'shipped' ? 'selected' : ''; ?>>Shipped</option>
                                        <option value="delivered" <?php echo $order['status'] === 'delivered' ? 'selected' : ''; ?>>Delivered</option>
                                        <option value="cancelled" <?php echo $order['status'] === 'cancelled' ? 'selected' : ''; ?>>Cancelled</option>
                                    </select>
                                </form>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
