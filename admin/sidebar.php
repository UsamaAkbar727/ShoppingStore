<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>

<body>
  <div class="w-64 bg-white shadow-lg min-h-screen max-h-full p-4 border-r border-gray-200">
    <h2 class="text-2xl font-bold mb-6 text-gray-800 border-b pb-2">Admin Panel</h2>
    <ul class="space-y-3 text-sm">
      <li>
        <a href="index.php?page=dashboard" class="block px-3 py-2 rounded-md text-blue-600 font-medium hover:bg-blue-100 transition">
          Dashboard
        </a>
      </li>
      <li>
        <a href="index.php?page=addproduct" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 transition">
          Add Products
        </a>
      </li>
      <li>
        <a href="index.php?page=products" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 transition">
          View Products
        </a>
      </li>
      <li>
        <a href="index.php?page=orders" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 transition">
          Orders
        </a>
      </li>
      <li>
        <a href="index.php?page=users" class="block px-3 py-2 rounded-md text-gray-700 hover:bg-gray-100 transition">
          Users
        </a>
      </li>
      <li>
        <a href="logout.php" class="block px-3 py-2 rounded-md text-red-600 hover:bg-red-100 transition">
          Logout
        </a>
      </li>
    </ul>
  </div>
</body>

</html>