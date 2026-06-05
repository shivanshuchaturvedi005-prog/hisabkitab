<?php
session_start();
// Agar user logged in nahi hai toh login page par redirect karo
if (!isset($_SESSION['user_id'])) {
    header('Location: login.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>🏠 Master Dashboard - Personal Hub</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans text-gray-800">

    <div class="max-w-6xl mx-auto p-4 sm:p-6 lg:p-8">
        <header class="flex justify-between items-center mb-8 bg-white p-6 rounded-xl shadow-sm border border-gray-200">
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Welcome back, <span class="text-indigo-600"><?php echo htmlspecialchars($_SESSION['username']); ?></span>! 👋</h1>
                <p class="text-gray-500 text-sm mt-0.5">Aapka personal daily routine aur management system</p>
            </div>
            <a href="logout.php" class="bg-rose-100 hover:bg-rose-200 text-rose-700 px-4 py-2 rounded-lg font-medium text-sm transition">Logout 🚪</a>
        </header>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex flex-col justify-between">
                <div>
                    <div class="text-3xl mb-3">🛒</div>
                    <h2 class="text-lg font-bold text-gray-800">Kirana & Sabji Tracker</h2>
                    <p class="text-gray-500 text-sm mt-1">Daily room partners ke sath khane-peene ke kharcho ka hisab-kitaab maintain karein.</p>
                </div>
                <a href="index.html" class="mt-4 inline-block text-center bg-indigo-50 hover:bg-indigo-100 text-indigo-600 font-semibold py-2 rounded-lg transition text-sm">Open Tracker</a>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex flex-col justify-between">
                <div>
                    <div class="text-3xl mb-3">👥</div>
                    <h2 class="text-lg font-bold text-gray-800">Dosto Ka Udhaar</h2>
                    <p class="text-gray-500 text-sm mt-1">Kis dost se kitna paisa lena hai ya kiske pass kitna pending settle karna baki hai.</p>
                </div>
                <a href="udhaar.html" class="mt-4 inline-block text-center bg-indigo-50 hover:bg-indigo-100 text-indigo-600 font-semibold py-2 rounded-lg transition text-sm">Manage Udhaar</a>
            </div>


            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex flex-col justify-between">
                <div>
                    <div class="text-3xl mb-3">👥</div>
                    <h2 class="text-lg font-bold text-gray-800">Dosto Ka Udhaar</h2>
                    <p class="text-gray-500 text-sm mt-1">Kis dost se kitna paisa lena hai ya kiske pass kitna pending settle karna baki hai.</p>
                </div>
                <a href="room.html" class="mt-4 inline-block text-center bg-indigo-50 hover:bg-indigo-100 text-indigo-600 font-semibold py-2 rounded-lg transition text-sm">Manage Udhaar</a>
            </div>



            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex flex-col justify-between">
                <div>
                    <div class="text-3xl mb-3">📅</div>
                    <h2 class="text-lg font-bold text-gray-800">Study & Work Time-Table</h2>
                    <p class="text-gray-500 text-sm mt-1">Apne padhai ka aur baki bache zaruri kamo ka din ke hisab se time schedule banayein.</p>
                </div>
                <a href="timetable.html" class="mt-4 inline-block text-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition text-sm">Manage Schedule</a>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex flex-col justify-between">
                <div>
                    <div class="text-3xl mb-3">💰</div>
                    <h2 class="text-lg font-bold text-gray-800">Mera Apna Kharcha</h2>
                    <p class="text-gray-500 text-sm mt-1">Room ke bahar aapka jo personal daily pocket kharcha hota hai, uski tracking (Private).</p>
                </div>
                <a href="personal_expenses.html" class="mt-4 inline-block text-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition text-sm">Add Private Expense</a>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex flex-col justify-between">
                <div>
                    <div class="text-3xl mb-3">📝</div>
                    <h2 class="text-lg font-bold text-gray-800">Rozana Ki Dincharya</h2>
                    <p class="text-gray-500 text-sm mt-1">Aaj din bhar me aapne kya-kya productive kam kiye, unhe yahan safe-store karein.</p>
                </div>
                <a href="dincharya.html" class="mt-4 inline-block text-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition text-sm">Write Daily Log</a>
            </div>

            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 flex flex-col justify-between">
                <div>
                    <div class="text-3xl mb-3">🔑</div>
                    <h2 class="text-lg font-bold text-gray-800">Secure Notes & Diary</h2>
                    <p class="text-gray-500 text-sm mt-1">Koi bhi important details, password links, ya thoughts ko digital diary me likhein.</p>
                </div>
                <a href="notes.html" class="mt-4 inline-block text-center bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-2 rounded-lg transition text-sm">View My Notes</a>
            </div>

        </div>
    </div>

</body>
</html>