<?php
session_start();
$conn = new mysqli("localhost", "root", "", "auth_system");

$error = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
  $email = htmlspecialchars($_POST["email"]);
  $password = $_POST["password"];

  // Fetch id, name, and password
  $stmt = $conn->prepare("SELECT id, name, password FROM users WHERE email = ?");
  $stmt->bind_param("s", $email);
  $stmt->execute();
  $stmt->store_result();

  if ($stmt->num_rows === 1) {
    //Bind result to include name
    $stmt->bind_result($id, $name, $hashedPassword);
    $stmt->fetch();

    if (password_verify($password, $hashedPassword)) {
      //Store ID, email, and name in session
      $_SESSION["user_id"] = $id;
      $_SESSION["user"] = $email;
      $_SESSION["user_name"] = $name;

      // Redirect to home page
      header("Location: AAindex.php");
      exit();
    } else {
      $error = "Incorrect password.";
    }
  } else {
    $error = "User not found.";
  }
  $stmt->close();
}
$conn->close();
?>


<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Login Page</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <style>
    body {
      position: relative;
      min-height: 100vh;
      margin: 0;
      font-family: sans-serif;
      overflow-x: hidden;
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
  <header class="bg-[rgb(44,47,72)] px-6 py-4 flex justify-between items-center sticky top-0 z-50">

    <div class="text-xl font-bold text-white">PeerConnect</div>
    <a href="AAindex.php" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2 rounded-lg transition">Back</a>
  </header>

  <!-- Login Form -->
  <div class="flex justify-center items-center min-h-screen px-4">
    <div class="w-full max-w-md p-10 rounded-3xl bg-[#2c2f48] shadow-2xl space-y-6">
      <h2 class="text-3xl font-bold text-center text-white">Log in</h2>

      <?php if (!empty($error)) : ?>
        <div id="error-popup" class="bg-red-600 text-white p-3 rounded-lg text-center">
          <?= $error ?>
        </div>
      <?php endif; ?>

      <form method="POST" action="">
        <div class="mb-4">
          <label class="block text-sm font-semibold text-white">Email</label>
          <input type="email" name="email" required
            class="w-full p-3 border border-gray-600 bg-white text-black rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
        </div>

        <div class="mb-6">
          <label class="block text-sm font-semibold text-white">Password</label>
          <input type="password" name="password" id="password" required
            class="w-full p-3 border border-gray-600 bg-white text-black rounded-xl focus:outline-none focus:ring-2 focus:ring-indigo-500">
          <button type="button" id="showPass" style="margin-top:5px;" class="text-sm text-blue-600">Show</button>
        </div>

        <button type="submit"
          class="w-full bg-gradient-to-tr from-indigo-500 to-blue-600 text-white py-3 rounded-xl font-semibold hover:scale-105 transition">
          Log in
        </button>
      </form>

      <p class="text-center text-sm text-white">
        Don't have an account?
        <a href="login.php" class="text-indigo-400 font-semibold hover:underline">Sign up</a>
      </p>
    </div>
  </div>

  <script>
    // Show/hide password
    document.getElementById("showPass").onclick = function() {
      const pass = document.getElementById("password");
      if (pass.type === "password") {
        pass.type = "text";
        this.textContent = "Hide";
      } else {
        pass.type = "password";
        this.textContent = "Show";
      }
    };


    <?php if (!empty($error)) : ?>
      setTimeout(function() {
        document.getElementById("error-popup").style.display = "none";
      }, 3000);
    <?php endif; ?>
  </script>

</body>

</html>