@extends('layouts.admin')

@section('title', 'Kelola Outlet & QR Code Meja')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="mb-8">
        <h1 class="text-3xl font-bold text-gray-900">Outlet & QR Code Meja</h1>
        <p class="text-gray-600 mt-2">Kelola outlet dan generate QR code untuk setiap meja</p>
    </div>

    @foreach($outlets as $outlet)
    <div class="bg-white rounded-xl shadow-sm border border-gray-200 mb-6">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-xl font-bold text-gray-900">{{ $outlet->name }}</h2>
                    <p class="text-sm text-gray-600 mt-1">{{ $outlet->address }}, {{ $outlet->city }}</p>
                    <p class="text-sm text-gray-500">{{ $outlet->phone }}</p>
                </div>
                <span class="px-3 py-1 rounded-full text-xs font-semibold {{ $outlet->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                    {{ $outlet->is_active ? 'Aktif' : 'Tidak Aktif' }}
                </span>
            </div>
        </div>

        <div class="p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Meja & QR Code</h3>
            <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-4">
                @foreach($outlet->tables as $table)
                <div class="border-2 border-gray-200 rounded-lg p-4 text-center hover:border-[#D26986] transition">
                    <div class="bg-white p-2 rounded-lg mb-3">
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=150x150&data={{ urlencode(route('scan.table', $table->qr_token)) }}" 
                             alt="QR Meja {{ $table->table_number }}"
                             class="w-full h-auto">
                    </div>
                    <p class="font-bold text-gray-900">Meja {{ $table->table_number }}</p>
                    <p class="text-xs text-gray-500 mt-1">{{ $table->is_active ? 'Aktif' : 'Tidak Aktif' }}</p>
                    <a href="https://api.qrserver.com/v1/create-qr-code/?size=500x500&data={{ urlencode(route('scan.table', $table->qr_token)) }}" 
                       target="_blank"
                       download="QR_{{ $outlet->name }}_Meja_{{ $table->table_number }}.png"
                       class="mt-2 inline-block text-xs text-[#D26986] hover:text-[#BD5773] font-semibold">
                        <i class="fa-solid fa-download mr-1"></i>
                        Download
                    </a>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endforeach

    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-lg mt-8">
        <div class="flex items-start">
            <i class="fa-solid fa-info-circle text-blue-600 text-xl mr-3 mt-0.5"></i>
            <div>
                <h3 class="font-semibold text-blue-900 mb-1">Cara Menggunakan QR Code</h3>
                <ol class="text-sm text-blue-800 space-y-1 list-decimal list-inside">
                    <li>Download QR code untuk setiap meja</li>
                    <li>Print dan tempelkan QR code di setiap meja</li>
                    <li>Customer scan QR code dengan smartphone mereka</li>
                    <li>Sistem otomatis mendeteksi outlet dan nomor meja</li>
                    <li>Customer bisa langsung pesan dengan mode Dine In</li>
                </ol>
            </div>
        </div>
    </div>
</div>
@endsection
