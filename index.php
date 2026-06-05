<?php
session_start();
// Agar user logged in nahi hai, toh direct login page par bhej do
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
    <title>📊 Daily Hisab-Kitaab</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 font-sans text-gray-800">

    <nav class="bg-white shadow-sm border-b border-gray-200 py-3 px-4 sm:px-6 lg:px-8 mb-6">
        <div class="max-w-6xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <span class="text-xl">🛒</span>
                <span class="font-bold text-gray-800 text-lg hidden sm:inline">Kirana & Sabji Tracker</span>
            </div>
            <div id="userAuthSection" class="flex items-center space-x-4">
                <div class="animate-pulse bg-gray-200 h-8 w-24 rounded-lg"></div>
            </div>
        </div>
    </nav>

    <div class="max-w-5xl mx-auto p-4 sm:p-6 lg:p-8">
        <nav class="bg-white shadow-sm rounded-xl p-4 mb-6 flex justify-around font-medium text-sm sm:text-base">
            <a href="index.php" class="text-indigo-600 hover:text-indigo-800 border-b-2 border-indigo-600 pb-1">🛒 Kirana & Sabji</a>
            <a href="udhaar.html" class="text-indigo-600 hover:text-indigo-800">👥 Dosto Ka Udhaar</a>
            <a href="room.html" class="text-indigo-600 hover:text-indigo-800">🏠 Room & Meter</a>
            <a href="routine.html" class="text-indigo-600 hover:text-indigo-800">📅 Room Routine</a>
        </nav>

        <header class="mb-8 text-center">
            <h1 class="text-3xl font-bold text-indigo-600">📊 Shared Expenses Tracker</h1>
            <p class="text-gray-500 mt-1">Kirana aur Sabji ka tracker - Kisne kitna pay kiya</p>
        </header>

        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-8">
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
                <p class="text-sm font-medium text-gray-400 uppercase">Aaj Ka Total</p>
                <p id="dailyTotal" class="text-2xl font-bold text-gray-900">₹0.00</p>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
                <p class="text-sm font-medium text-gray-400 uppercase">Is Hafte Ka</p>
                <p id="weeklyTotal" class="text-2xl font-bold text-gray-900">₹0.00</p>
            </div>
            <div class="bg-white p-5 rounded-xl shadow-sm border border-gray-200">
                <p class="text-sm font-medium text-gray-400 uppercase">Is Mahine Ka</p>
                <p id="monthlyTotal" class="text-2xl font-bold text-gray-900">₹0.00</p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 h-fit">
                <h2 class="text-lg font-semibold mb-4 text-gray-700">Naya Kharcha Jodein</h2>
                <form id="expenseForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Item Ka Naam</label>
                        <input type="text" id="itemName" required placeholder="e.g., Aloo, Ashirvaad Atta" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Rupee (₹)</label>
                        <input type="number" id="amount" step="any" required placeholder="0.00" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Payment Kisne Kiya? (Payee)</label>
                        <input type="text" id="payeeName" required placeholder="e.g., My Name, Partner Name" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Category</label>
                        <select id="category" class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="Kirana">🛒 Kirana</option>
                            <option value="Sabji">🥦 Sabji</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-600 mb-1">Date</label>
                        <input type="date" id="date" required class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                    <button type="submit" class="w-full bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-lg transition">Save Karein</button>
                </form>
            </div>

            <div class="md:col-span-2 bg-white p-6 rounded-xl shadow-sm border border-gray-200">
                <h2 class="text-lg font-semibold mb-4 text-gray-700">Kharcho Ki List</h2>
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-200 text-gray-400 text-sm uppercase">
                                <th class="pb-3 font-medium">Item</th>
                                <th class="pb-3 font-medium">Paid By</th>
                                <th class="pb-3 font-medium">Category</th>
                                <th class="pb-3 font-medium text-right">Amount</th>
                                <th class="pb-3 font-medium text-center">Action</th>
                            </tr>
                        </thead>
                        <tbody id="expenseTableBody" class="divide-y divide-gray-100 text-sm"></tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('date').valueAsDate = new Date();
        const form = document.getElementById('expenseForm');

        async function checkUserStatus() {
            try {
                const res = await fetch('auth.php?action=check_status');
                const data = await res.json();
                const authSection = document.getElementById('userAuthSection');

                if (data.logged_in) {
                    authSection.innerHTML = `
                        <div class="flex items-center space-x-2 sm:space-x-3">
                            <div class="text-right hidden sm:block">
                                <p class="text-xs text-gray-400">Logged in as</p>
                                <p class="text-sm font-semibold text-indigo-600">${data.username}</p>
                            </div>
                            <div class="bg-indigo-100 text-indigo-700 h-8 w-8 rounded-full flex items-center justify-center font-bold uppercase text-sm">
                                ${data.username.charAt(0)}
                            </div>
                            <a href="dashboard.php" class="bg-indigo-600 hover:bg-indigo-700 text-white px-3 py-1.5 rounded-lg text-xs font-medium transition shadow-sm">My Dashboard 🏠</a>
                            <a href="logout.php" class="text-gray-400 hover:text-rose-600 text-xs font-medium transition pl-1">Logout</a>
                        </div>
                    `;
                } else {
                    window.location.href = 'login.html';
                }
            } catch (error) {
                console.error("Auth status check failed", error);
            }
        }

        async function fetchExpenses() {
            const res = await fetch('server.php');
            const data = await res.json();
            
            let daily = 0, weekly = 0, monthly = 0;
            const today = new Date();
            const tableBody = document.getElementById('expenseTableBody');
            tableBody.innerHTML = '';

            data.forEach(item => {
                const itemAmt = parseFloat(item.amount) || 0;
                const itemDate = new Date(item.date);
                
                const diffTime = Math.abs(today - itemDate);
                const diffDays = Math.ceil(diffTime / (1000 * 60 * 60 * 24));

                if(itemDate.toDateString() === today.toDateString()) daily += itemAmt;
                if(diffDays <= 7) weekly += itemAmt;
                if(itemDate.getMonth() === today.getMonth() && itemDate.getFullYear() === today.getFullYear()) monthly += itemAmt;

                const paidBy = item.payeeName || 'Unknown';

                tableBody.innerHTML += `
                    <tr class="text-gray-700 hover:bg-gray-50 transition-colors">
                        <td class="py-3 font-medium">
                            <div>${item.itemName}</div>
                            <div class="text-xs text-gray-400">${item.date}</div>
                        </td>
                        <td class="py-3 text-indigo-600 font-bold">${paidBy}</td>
                        <td class="py-3"><span class="px-2 py-1 text-xs rounded-full ${item.category === 'Kirana' ? 'bg-blue-100 text-blue-700' : 'bg-green-100 text-green-700'}">${item.category}</span></td>
                        <td class="py-3 text-right font-bold text-gray-900">₹${itemAmt.toFixed(2)}</td>
                        <td class="py-3 text-center">
                            <button onclick="deleteExpense('${item.id}')" class="text-red-500 hover:text-red-700 transition">Delete</button>
                        </td>
                    </tr>
                `;
            });

            document.getElementById('dailyTotal').innerText = '₹' + daily.toFixed(2);
            document.getElementById('weeklyTotal').innerText = '₹' + weekly.toFixed(2);
            document.getElementById('monthlyTotal').innerText = '₹' + monthly.toFixed(2);
        }

        form.addEventListener('submit', async (e) => {
            e.preventDefault();
            const itemName = document.getElementById('itemName').value;
            const amount = document.getElementById('amount').value;
            const payeeName = document.getElementById('payeeName').value;
            const category = document.getElementById('category').value;
            const date = document.getElementById('date').value;

            await fetch('server.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ itemName, amount, category, date, payeeName })
            });
            form.reset();
            document.getElementById('date').valueAsDate = new Date();
            fetchExpenses();
        });

        async function deleteExpense(id) {
            if(confirm('Kya aap waqai is kharche ko delete karna chahte hain?')) {
                await fetch('server.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ action: 'delete', id })
                });
                fetchExpenses();
            }
        }

        checkUserStatus();
        fetchExpenses();
    </script>
</body>
</html>