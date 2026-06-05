<?php
session_start();
// Security Layer: Agar admin authenticated nahi hai toh block kar do
if (!isset($_SESSION['is_admin']) || $_SESSION['is_admin'] !== true) {
    header('Location: login.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>👑 Admin Control Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-900 text-gray-100 font-sans min-h-screen">

    <div class="max-w-5xl mx-auto p-4 sm:p-6 lg:p-8">
        <!-- Main Header -->
        <header class="flex justify-between items-center mb-8 bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-xl">
            <div>
                <h1 class="text-2xl font-bold text-indigo-400">👑 Master Admin Control Center</h1>
                <p class="text-gray-400 text-xs mt-1">Yahan se aap naye accounts bana sakte hain aur unhe control/delete kar sakte hain</p>
            </div>
            <a href="logout.php" class="bg-rose-600 hover:bg-rose-700 text-white px-4 py-2 rounded-lg font-medium text-sm transition">Secure Logout 🚪</a>
        </header>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- ➕ LEFT: NAYA ACCOUNT GENERATE KARNE KA FORM -->
            <div class="bg-gray-800 p-6 rounded-xl border border-gray-700 shadow-xl h-fit">
                <h2 class="text-lg font-semibold text-gray-200 mb-4 flex items-center space-x-2">
                    <span>➕</span> <span>Naya Account Banayein</span>
                </h2>
                <form id="createUserForm" class="space-y-4">
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1">Dost Ka Username</label>
                        <input type="text" id="newUsername" required placeholder="e.g., rahul123" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-400 mb-1">Dost Ka Password</label>
                        <input type="text" id="newPassword" required placeholder="e.g., pass@123" class="w-full bg-gray-700 border border-gray-600 rounded-lg px-3 py-2 text-sm text-white focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <button type="submit" class="w-full bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-medium py-2 px-4 rounded-lg transition shadow-md">
                        Create Account 🚀
                    </button>
                </form>
            </div>

            <!-- 👥 RIGHT: REGISTERED USERS KI LIST WITH CONTROLS -->
            <div class="md:col-span-2 bg-gray-800 rounded-xl p-6 border border-gray-700 shadow-xl">
                <h2 class="text-lg font-semibold text-gray-200 mb-4 flex items-center space-x-2">
                    <span>👥</span> <span>Active Members Database</span>
                </h2>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-700 text-gray-400 text-xs uppercase bg-gray-750">
                                <th class="p-3 font-medium">User ID</th>
                                <th class="p-3 font-medium">Username</th>
                                <th class="p-3 font-medium text-center">Actions Control</th>
                            </tr>
                        </thead>
                        <tbody id="adminUsersBody" class="divide-y divide-gray-700 text-sm">
                            <!-- Data dynamically loads here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        const createForm = document.getElementById('createUserForm');

        // 1. Sabhi Users ko load karne ka function
        async function loadAllUsers() {
            try {
                const res = await fetch('auth.php?action=get_users');
                const users = await res.json();
                const tbody = document.getElementById('adminUsersBody');
                tbody.innerHTML = '';

                if(users.length === 0) {
                    tbody.innerHTML = `<tr><td colspan="3" class="p-4 text-center text-gray-500 italic">Abhi tak aapne koi account nahi banaya hai.</td></tr>`;
                    return;
                }

                users.forEach(user => {
                    tbody.innerHTML += `
                        <tr class="hover:bg-gray-750 transition">
                            <td class="p-3 font-mono text-xs text-indigo-300">${user.id}</td>
                            <td class="p-3 font-semibold text-gray-200">${user.username}</td>
                            <td class="p-3 text-center space-x-2">
                                <button onclick="resetUserPassword('${user.id}', '${user.username}')" class="bg-indigo-600 hover:bg-indigo-700 text-white text-xs px-2.5 py-1.5 rounded-md font-medium transition shadow-sm inline-flex items-center">
                                    🔑 Password
                                </button>
                                <button onclick="deleteUserAccount('${user.id}', '${user.username}')" class="bg-rose-600 hover:bg-rose-700 text-white text-xs px-2.5 py-1.5 rounded-md font-medium transition shadow-sm inline-flex items-center">
                                    🗑️ Delete
                                </button>
                            </td>
                        </tr>
                    `;
                });
            } catch (error) {
                console.error("Error loading database", error);
            }
        }

        // 2. Naya user generate karne ka code
        createForm.addEventListener('submit', async (e) => {
            e.preventDefault();
            const username = document.getElementById('newUsername').value;
            const password = document.getElementById('newPassword').value;

            const res = await fetch('auth.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ action: 'signup', username, password })
            });

            const data = await res.json();
            alert(data.message);
            
            if(data.status === 'success') {
                createForm.reset();
                loadAllUsers(); 
            }
        });

        // 3. Password reset functionality
        async function resetUserPassword(userId, username) {
            const newPass = prompt(`User "${username}" ke liye naya password enter karein:`);
            if (newPass === null) return;
            
            if(newPass.trim() === '') {
                alert('Password khali nahi ho sakta!');
                return;
            }

            try {
                const res = await fetch('auth.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'change_password', user_id: userId, new_password: newPass })
                });
                const data = await res.json();
                alert(data.message);
                loadAllUsers();
            } catch (error) {
                alert('Password update nahi ho paya!');
            }
        }

        // 4. 🔥 USER DELETE KARNE KA FUNCTON
        async function deleteUserAccount(userId, username) {
            if (confirm(`⚠️ DHYAN DEIN:\nKya aap sach me "${username}" ka account hamesha ke liye delete karna chahte hain?`)) {
                try {
                    const res = await fetch('auth.php', {
                        method: 'POST',
                        headers: { 'Content-Type': 'application/json' },
                        body: JSON.stringify({ action: 'delete_user', user_id: userId })
                    });
                    const data = await res.json();
                    alert(data.message);
                    loadAllUsers(); // List refresh ho jayegi instantly
                } catch (error) {
                    alert('User delete karne me error aayi!');
                }
            }
        }

        // Initialize table
        loadAllUsers();
    </script>
</body>
</html>