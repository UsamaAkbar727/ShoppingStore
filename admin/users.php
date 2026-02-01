  <!-- <script src="https://cdn.tailwindcss.com"></script> -->

<?php
include("../configshoppingstore.php");

$res_arr = [];
try {
    $data = $conn->prepare("SELECT * FROM user");
    $data->execute();
    $res = $data->fetchAll();
    if ($res) {
        $res_arr = $res;
    }
} catch (\Throwable $th) {
    echo $th;
}

?>
<script src="https://cdn.tailwindcss.com"></script>
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css">
<script>
tailwind.config = {
    theme: {
        extend: {
            colors: {
                primary: "#3b82f6",
                secondary: "#1e40af"
            }
        }
    }
}
</script>

<div class="flex-1 bg-white p-6 md:p-8 rounded-lg shadow-lg ">
    <h1 class="text-3xl font-bold mb-6 text-gray-800">User Management</h1>
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200 text-sm md:text-base">
            <thead class="bg-gray-100">
                <tr>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">ID</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Name</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Email</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Status</th>
                    <th class="px-4 py-3 text-left font-semibold text-gray-600">Action</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-gray-100">
                <?php foreach ($res_arr as $user): ?>
                    <tr class="hover:bg-gray-50 transition duration-150 ease-in-out">
                        <td class="px-4 py-3 text-gray-700"><?= printUserField($user['id']); ?></td>
                        <td class="px-4 py-3 text-gray-700"><?= printUserField($user['fullname']); ?></td>
                        <td class="px-4 py-3 text-gray-700 max-w-xs overflow-x-auto break-words"><?= printUserField($user['email']); ?></td>
                        <td class="px-4 py-3 font-medium <?= $user['isBlock'] == '1' ? 'text-red-500' : 'text-green-600'; ?>">
                            <?= $user['isBlock'] == '1' ? 'Blocked' : 'Active'; ?>
                        </td>
                        <td class="px-4 py-3">
                            <?php if ($user['isBlock'] === '0'): ?>
                                <a href="?action=block&id=<?= $user['id'] ?>"
                                    class="inline-block bg-red-500 hover:bg-red-600 text-white px-3 py-1 rounded-md text-xs font-semibold">
                                    Block
                                </a>
                            <?php else: ?>
                                <a href="?action=unblock&id=<?= $user['id'] ?>"
                                    class="inline-block bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded-md text-xs font-semibold">
                                    Unblock
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                <?php endforeach; ?>

                <?php if (empty($res_arr)): ?>
                    <tr>
                        <td colspan="5" class="px-4 py-6 text-center text-gray-500">No users found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
function printUserField($field)
{
    return htmlspecialchars($field, ENT_QUOTES, 'UTF-8');
}
?>