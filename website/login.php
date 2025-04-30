<?php
session_start();
$conn = new mysqli("localhost", "root", "", "auth_system");

$error = "";
$success = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $name = htmlspecialchars($_POST["name"]);
  $email = htmlspecialchars($_POST["email"]);
  $password = password_hash($_POST["password"], PASSWORD_DEFAULT);

  $stmt = $conn->prepare("SELECT id FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();
  if ($stmt->num_rows > 0) {
    $_SESSION['email_exists'] = true;
  } else {
    $stmt = $conn->prepare("INSERT INTO users (name, email, password) VALUES (?, ?, ?)");
    $stmt->bind_param("sss", $name, $email, $password);
    if ($stmt->execute()) {
      $_SESSION['user_name'] = $name;
      $success = "You have successfully registered! Redirecting...";
      header("refresh:3;url=AAindex.php");
    } else {
      $error = "Error during registration.";
    }
  }
  $stmt->close();
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sign Up | PeerConnect</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      position: relative;
      margin: 0;
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }

    body::before {
      content: "";
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      background-image: url('https://uniathenaprods3.uniathena.com/s3fs-public/2024-05/Peer-to-Peer-Learning-Platforms.jpg');
      background-size: cover;
      background-attachment: fixed;
      background-position: center;
      opacity: 0.5;
      z-index: -1;
    }
  </style>
</head>
<body class="bg-gray-900 min-h-screen flex flex-col">

  <!-- Navbar -->
  <header class="fixed top-0 left-0 w-full z-50 bg-[rgb(44,47,72)] px-6 py-4 flex justify-between items-center shadow-lg">

    <h1 class="text-3xl text-white font-bold">PeerConnect</h1>
    <a href="AAindex.php" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg transition">Back</a>
  </header>

  <!-- Main Signup Content -->
 
  <main class="flex flex justify-center items-center px-4 py-10 pt-28">

    <div class="w-full max-w-md p-8 bg-[#2c2f48] rounded-3xl shadow-2xl space-y-6">
      <h2 class="text-3xl font-bold text-center text-white">Create Your Account</h2>

      <?php if (!empty($error)) : ?>
        <div class="bg-red-600 text-white text-center px-4 py-2 rounded-md"><?= $error ?></div>
      <?php endif; ?>

      <?php if (!empty($success)) : ?>
        <div class="bg-green-600 text-white text-center px-4 py-2 rounded-md"><?= $success ?></div>
      <?php endif; ?>

      <form method="POST" action="">
        <label class="block mb-4">
          <span class="text-white font-medium">Full Name</span>
          <input type="text" name="name" required
            class="w-full mt-1 p-3 rounded-lg border border-gray-300 bg-white text-black focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </label>

        <label class="block mb-4">
          <span class="text-white font-medium">Email</span>
          <input type="email" name="email" required
            class="w-full mt-1 p-3 rounded-lg border border-gray-300 bg-white text-black focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </label>

        <label class="block mb-6">
          <span class="text-white font-medium">Password</span>
          <input type="password" name="password" id="password" required
            class="w-full mt-1 p-3 rounded-lg border border-gray-300 bg-white text-black focus:outline-none focus:ring-2 focus:ring-indigo-500">
          <button type="button" id="showPass" class="text-sm text-blue-400 mt-1">Show</button>
        </label>

        <button type="submit"
          class="w-full bg-gradient-to-r from-indigo-500 to-blue-600 text-white py-3 rounded-xl font-semibold hover:scale-105 transition-transform">
          Sign Up
        </button>
      </form>

      <div id="popup" class="hidden fixed top-20 left-1/2 transform -translate-x-1/2 bg-red-600 text-white px-6 py-4 rounded-lg shadow-lg z-50">
        Email already registered. Please <a href="login1.php" class="underline font-semibold">log in</a>.
        <button id="closePopup" class="ml-4 font-bold">X</button>
      </div>

      <p class="text-center text-white text-sm">
        Already have an account?
        <a href="login1.php" class="text-indigo-400 font-semibold hover:underline">Log in</a>
      </p>
    </div>
  </main>

  <!-- JS Scripts -->
  <script>
    // Show password toggle
    document.getElementById("showPass").onclick = function () {
      const passInput = document.getElementById("password");
      if (passInput.type === "password") {
        passInput.type = "text";
        this.textContent = "Hide";
      } else {
        passInput.type = "password";
        this.textContent = "Show";
      }
    };

    // Show popup for duplicate email
    <?php if (isset($_SESSION['email_exists']) && $_SESSION['email_exists'] === true): ?>
      document.addEventListener("DOMContentLoaded", function () {
        document.getElementById("popup").classList.remove("hidden");
      });
      <?php unset($_SESSION['email_exists']); ?>
    <?php endif; ?>

    // Close popup
    document.getElementById("closePopup")?.addEventListener("click", function () {
      document.getElementById("popup").classList.add("hidden");
    });
  </script>

</body>
</html>
