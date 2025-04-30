<?php
session_start();
$isAdmin = (isset($_SESSION['email']) && $_SESSION['email'] === 'sriragavandha@gmail.com');
?>

<!DOCTYPE html>
<html lang="en" class="dark">
<head>
  <meta charset="UTF-8">
  <title>Join Meeting - PeerConnect</title>
  <script src="https://cdn.tailwindcss.com"></script>
  <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600;700&display=swap" rel="stylesheet">
  <script src="https://meet.jit.si/external_api.js"></script>
  <style>
    body { font-family: 'Poppins', sans-serif; }
  </style>
</head>
<body class="bg-gradient-to-br from-[#1e1e2f] to-[#2a2a40] text-white">

  <!-- Navbar -->
  <header class="bg-[#2c2f48] px-6 py-3 shadow-md flex justify-between items-center sticky top-0 z-50">
    <div class="text-3xl font-bold text-pink-white">PeerConnect</div>
    <nav class="flex-grow flex justify-center">
      <ul class="flex gap-8 text-sm font-medium text-white items-center">
        <li><a href="AAindex.php" class="hover:text-indigo-300 transition">Home</a></li>
        <li><a href="about.php" class="hover:text-indigo-300 transition">About</a></li>
        <li><a href="review.php" class="hover:text-indigo-300 transition">Review</a></li>
        <li><a href="discussion.php" class="hover:text-indigo-300 transition">Discussion Area</a></li>
        <li><a href="dashboard.php" class="hover:text-indigo-300 transition">Upload</a></li>
        <li><a href="join.php" class="text-blue-400 font-semibold">Meetings</a></li>
      </ul>
    </nav>

    <!-- Display user name if logged in -->
    <div>
      <?php if (isset($_SESSION['user_name'])): ?>
        <!-- Show the username if logged in -->
        <span class="text-white font-semibold mr-4">Welcome, <?= htmlspecialchars($_SESSION['user_name']); ?>!</span>
        <a href="logout.php" class="bg-red-600 hover:bg-red-700 text-white mr-4 px-4 py-2 rounded-lg font-semibold transition">Logout</a>
      <?php else: ?>
        <!-- Show Login button if not logged in -->
        <a href="login1.php" class="bg-blue-600 hover:bg-blue-700 text-white mr-4 px-4 py-2 rounded-lg font-semibold transition">Login</a>
        <a href="login.php" class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg font-semibold transition">Signup</a>
      <?php endif; ?>
    </div>
  </header>

  <!-- Meeting Container -->
  <section class="py-20 px-6 max-w-7xl mx-auto">
    <h2 class="text-3xl font-bold text-indigo-300 text-center mb-8">🔗 Join the Meeting</h2>

    <div id="jitsi-container" class="w-full h-[600px] rounded-xl overflow-hidden shadow-lg border border-indigo-500"></div>

    <div id="after-meeting-message" class="hidden text-center mt-6 text-xl font-semibold text-indigo-300">
      👋 You have left the meeting. Thank you for using <span class="text-pink-400">PeerConnect</span>!
    </div>
  </section>

  <!-- Jitsi Script -->
  <script>
    const domain = "meet.jit.si";
    const options = {
      roomName: "PeerConnectRoom",
      width: "100%",
      height: 600,
      parentNode: document.querySelector('#jitsi-container'),
      configOverwrite: {
        prejoinPageEnabled: false
      },
      interfaceConfigOverwrite: {
        DEFAULT_BACKGROUND: "#1e1e2f",
      },
      userInfo: {
        email: "<?php echo $_SESSION['email'] ?? ''; ?>",
        displayName: "<?php echo $_SESSION['username'] ?? 'Guest'; ?>"
      }
    };

    const api = new JitsiMeetExternalAPI(domain, options);

    api.addEventListener('readyToClose', () => {
      document.getElementById("jitsi-container").style.display = "none";
      document.getElementById("after-meeting-message").classList.remove("hidden");
    });
  </script>

</body>
</html>
