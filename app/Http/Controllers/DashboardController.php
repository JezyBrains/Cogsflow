<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        // 1. Authorized Agents (Total Users)
        $total_users = User::count();

        // 2. Financial Stream (Recent Transactions)
        $recent_transactions = Transaction::latest()
            ->take(5)
            ->get();

        // 3. Financial Totals (Income vs Expense)
        $total_revenue = Transaction::where('type', 'income')->sum('amount');
        $total_expenses = Transaction::where('type', 'expense')->sum('amount');

        // Calculate flux (percentage change or just simple diff for now)
        $net_profit = $total_revenue - $total_expenses;

        // 4. Supply Chain Velocity (Procurement Volume)
        $procurement_volume = Batch::sum('expected_bags');

        // 5. System Integrity (Recent Audits)
        // Check if there are any recent 'critical' or 'danger' logs, otherwise 100%
        $integrity_score = 100; // Placeholder logic, could be based on failed logins etc.

        return view('dashboard', compact(
            'total_users',
            'recent_transactions',
            'total_revenue',
            'total_expenses',
            'net_profit',
            'procurement_volume',
            'integrity_score'
        ));
    }
}
