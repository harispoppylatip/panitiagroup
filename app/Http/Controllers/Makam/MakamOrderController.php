<?php

namespace App\Http\Controllers\Makam;

use App\Http\Controllers\Controller;
use App\Models\MakamOrder;
use App\Models\MakamType;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;

class MakamOrderController extends Controller
{
    /**
     * Menampilkan daftar pesanan (Admin)
     */
    public function index(): View
    {
        $orders = MakamOrder::with('makamType')->orderBy('created_at', 'desc')->paginate(10);
        return view('makam.orders.index', compact('orders'));
    }

    /**
     * Menampilkan detail pesanan
     */
    public function show(int $id): View
    {
        $order = MakamOrder::with('makamType')->findOrFail($id);
        return view('makam.orders.show', compact('order'));
    }

    /**
     * Update status pesanan
     */
    public function updateStatus(Request $request, int $id): RedirectResponse
    {
        $request->validate([
            'status' => 'required|in:baru,diproses,selesai,dibatalkan',
        ]);

        $order = MakamOrder::findOrFail($id);
        $order->update(['status' => $request->status]);

        return redirect()->route('makam.orders.index')
            ->with('success', 'Status pesanan berhasil diperbarui.');
    }

    /**
     * Hapus pesanan
     */
    public function destroy(int $id): RedirectResponse
    {
        $order = MakamOrder::findOrFail($id);
        $order->delete();

        return redirect()->route('makam.orders.index')
            ->with('success', 'Pesanan berhasil dihapus.');
    }

    // =====================
    // API untuk Customer
    // =====================

    /**
     * API: Melihat daftar jenis makam yang tersedia
     */
    public function apiTypes(): JsonResponse
    {
        $types = MakamType::where('is_active', true)
            ->where('stok_tersedia', '>', 0)
            ->orderBy('nama')
            ->get()
            ->map(function ($type) {
                return [
                    'id' => $type->id,
                    'nama' => $type->nama,
                    'deskripsi' => $type->deskripsi,
                    'harga' => (float) $type->harga,
                    'blok' => $type->blok,
                    'stok_tersedia' => $type->stok_tersedia,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $types,
        ]);
    }

    /**
     * API: Customer memesan makam
     */
    public function apiOrder(Request $request): JsonResponse
    {
        $request->validate([
            'makam_type_id' => 'required|exists:makam_types,id',
            'nama_customer' => 'required|string|max:255',
            'email_customer' => 'nullable|email|max:255',
            'no_wa_customer' => 'required|string|max:20',
            'alamat_customer' => 'nullable|string',
            'jumlah' => 'required|integer|min:1',
            'catatan' => 'nullable|string',
        ]);

        $makamType = MakamType::findOrFail($request->makam_type_id);

        // Cek ketersediaan stok
        if ($makamType->stok_tersedia < $request->jumlah) {
            return response()->json([
                'success' => false,
                'message' => 'Stok tidak mencukupi. Stok tersedia: ' . $makamType->stok_tersedia,
            ], 400);
        }

        // Generate kode pesanan unik
        $kodePesanan = 'MKM-' . strtoupper(\Illuminate\Support\Str::random(8));

        $totalHarga = $makamType->harga * $request->jumlah;

        $order = MakamOrder::create([
            'kode_pesanan' => $kodePesanan,
            'makam_type_id' => $request->makam_type_id,
            'nama_customer' => $request->nama_customer,
            'email_customer' => $request->email_customer,
            'no_wa_customer' => $request->no_wa_customer,
            'alamat_customer' => $request->alamat_customer,
            'jumlah' => $request->jumlah,
            'total_harga' => $totalHarga,
            'status' => 'baru',
            'catatan' => $request->catatan,
        ]);

        // Kurangi stok
        $makamType->decrement('stok_tersedia', $request->jumlah);

        return response()->json([
            'success' => true,
            'message' => 'Pesanan berhasil dibuat.',
            'data' => [
                'id' => $order->id,
                'kode_pesanan' => $order->kode_pesanan,
                'jenis_makam' => $makamType->nama,
                'jumlah' => $order->jumlah,
                'total_harga' => (float) $order->total_harga,
                'status' => $order->status,
                'tanggal_pesan' => $order->created_at->toISOString(),
            ],
        ], 201);
    }

    /**
     * API: Cek status pesanan by kode pesanan
     */
    public function apiCekStatus(string $kodePesanan): JsonResponse
    {
        $order = MakamOrder::with('makamType')
            ->where('kode_pesanan', $kodePesanan)
            ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Pesanan tidak ditemukan.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'kode_pesanan' => $order->kode_pesanan,
                'jenis_makam' => $order->makamType->nama,
                'nama_customer' => $order->nama_customer,
                'jumlah' => $order->jumlah,
                'total_harga' => (float) $order->total_harga,
                'status' => $order->status,
                'tanggal_pesan' => $order->created_at->toISOString(),
            ],
        ]);
    }
}
