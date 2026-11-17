<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreOutletRequest;
use App\Http\Requests\UpdateOutletRequest;
use App\Models\Outlet;
use App\Models\OutletTable;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class OutletController extends Controller
{
    /**
     * Display outlet & QR management page.
     */
    public function index(): View
    {
        $outlets = Outlet::with('tables')->orderBy('name')->get();

        // Statistik
        $totalOutlets = Outlet::count();
        $activeOutlets = Outlet::where('is_active', true)->count();
        $totalTables = OutletTable::count();
        $activeTables = OutletTable::where('is_active', true)->count();

        return view('admin.outlet.index', compact(
            'outlets',
            'totalOutlets',
            'activeOutlets',
            'totalTables',
            'activeTables'
        ));
    }

    /**
     * Show the form for creating a new outlet.
     */
    public function create(): View
    {
        return view('admin.outlet.create');
    }

    /**
     * Store a newly created outlet.
     */
    public function store(StoreOutletRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('outlets', 'public');
        }

        $data['is_active'] = $request->boolean('is_active');

        Outlet::create($data);

        return redirect()->route('admin.outlet.index')
            ->with('success', 'Outlet berhasil ditambahkan!');
    }

    /**
     * Show the form for editing the specified outlet.
     */
    public function edit(Outlet $outlet): View
    {
        return view('admin.outlet.edit', compact('outlet'));
    }

    /**
     * Update the specified outlet.
     */
    public function update(UpdateOutletRequest $request, Outlet $outlet): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('image')) {
            // Hapus gambar lama sebelum ganti
            if ($outlet->image) {
                Storage::disk('public')->delete($outlet->image);
            }
            $data['image'] = $request->file('image')->store('outlets', 'public');
        }

        $data['is_active'] = $request->boolean('is_active');

        $outlet->update($data);

        return redirect()->route('admin.outlet.index')
            ->with('success', "Outlet {$outlet->name} berhasil diperbarui!");
    }

    /**
     * Remove the specified outlet (tables & QR ikut terhapus, pesanan outlet_id jadi null).
     */
    public function destroy(Outlet $outlet): RedirectResponse
    {
        // Hapus gambar
        if ($outlet->image) {
            Storage::disk('public')->delete($outlet->image);
        }

        $outlet->delete();

        return redirect()->route('admin.outlet.index')
            ->with('success', "Outlet {$outlet->name} berhasil dihapus!");
    }

    /**
     * Toggle outlet active status directly.
     */
    public function toggleOutlet(Outlet $outlet)
    {
        $outlet->update(['is_active' => !$outlet->is_active]);

        return back()->with(
            'success',
            "Outlet {$outlet->name} berhasil " . ($outlet->is_active ? 'diaktifkan' : 'dinonaktifkan') . "!"
        );
    }

    /**
     * Add a new table + QR code to an outlet.
     */
    public function storeTable(Request $request, Outlet $outlet)
    {
        $validated = $request->validate([
            'table_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('outlet_tables', 'table_number')->where('outlet_id', $outlet->id),
            ],
        ]);

        OutletTable::create([
            'outlet_id' => $outlet->id,
            'table_number' => $validated['table_number'],
            'qr_token' => OutletTable::generateQrToken(),
            'is_active' => true,
        ]);

        return back()->with(
            'success',
            "Meja {$validated['table_number']} berhasil ditambahkan dengan QR code baru!"
        );
    }

    /**
     * Toggle table active status directly.
     */
    public function toggleTable(OutletTable $table)
    {
        $table->update(['is_active' => !$table->is_active]);

        return back()->with(
            'success',
            "Meja {$table->table_number} berhasil " . ($table->is_active ? 'diaktifkan' : 'dinonaktifkan') . "!"
        );
    }

    /**
     * Delete a table (QR code ikut terhapus).
     */
    public function destroyTable(OutletTable $table)
    {
        $table->delete();

        return back()->with('success', "Meja {$table->table_number} berhasil dihapus!");
    }
}