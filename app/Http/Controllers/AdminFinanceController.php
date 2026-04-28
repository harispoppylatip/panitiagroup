<?php

namespace App\Http\Controllers;

use App\Models\Datasikadmodel;
use App\Models\FinanceSetting;
use App\Models\GrubkasActivityLog;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class AdminFinanceController extends Controller
{
    public function index()
    {
        $setting = FinanceSetting::singleton();

        $members = Datasikadmodel::with('latestIuran')
            ->orderBy('nama')
            ->get();

        $recentExpenses = GrubkasActivityLog::where('direction', 'out')
            ->latest('occurred_at')
            ->latest('id')
            ->limit(10)
            ->get();

        return view('admin.finance.index', compact('setting', 'members', 'recentExpenses'));
    }

    public function updateWeeklyFee(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'weekly_fee' => ['required', 'integer', 'min:0', 'max:100000000'],
        ]);

        $setting = FinanceSetting::singleton();
        $setting->update([
            'weekly_fee' => (int) $validated['weekly_fee'],
        ]);

        return redirect()->route('admin.finance.index')->with('success', 'Iuran mingguan berhasil diperbarui.');
    }

    public function updateMemberBalance(Request $request, string $nim): RedirectResponse
    {
        $validated = $request->validate([
            'nominal' => ['required', 'integer', 'min:0', 'max:100000000'],
            'saldo_lebih' => ['required', 'integer', 'min:0', 'max:100000000'],
            'keterangan' => ['nullable', 'string', 'max:255'],
        ]);

        $member = Datasikadmodel::where('Nim', $nim)->firstOrFail();
        $latestIuran = $member->iuran()->latest('id')->first();

        $payload = [
            'Nominal' => (int) $validated['nominal'],
            'Saldo_Lebih' => (int) $validated['saldo_lebih'],
            'Status_Bayar' => (int) $validated['nominal'] === 0 ? 1 : 0,
            'Keterangan' => $validated['keterangan'] ?? 'Penyesuaian manual oleh admin',
        ];

        if ($latestIuran) {
            $latestIuran->update($payload);
        } else {
            $member->iuran()->create(array_merge($payload, [
                'user_nim' => $member->Nim,
            ]));
        }

        return redirect()->route('admin.finance.index')->with('success', 'Utang/saldo anggota berhasil diperbarui.');
    }

    public function storeExpense(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'amount' => ['required', 'integer', 'min:1', 'max:100000000'],
            'description' => ['required', 'string', 'max:255'],
        ]);

        GrubkasActivityLog::create([
            'activity_type' => 'expense',
            'direction' => 'out',
            'amount' => (int) $validated['amount'],
            'title' => 'Pengeluaran Kas',
            'description' => $validated['description'],
            'order_id' => 'EXP-' . now()->format('YmdHis') . '-' . random_int(1000, 9999),
            'transaction_status' => 'manual',
            'occurred_at' => now(),
        ]);

        return redirect()->route('admin.finance.index')->with('success', 'Pengeluaran manual berhasil dicatat.');
    }
}
