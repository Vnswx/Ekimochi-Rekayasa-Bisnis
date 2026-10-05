@extends('layouts.app')

@section('title', 'FAQ - Pertanyaan yang Sering Diajukan')

@section('content')
<!-- Hero Section -->
<section class="relative bg-gradient-to-br from-rose-50 via-pink-50 to-white py-16">
  <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
    <div class="text-center">
      <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-white shadow-sm border border-rose-100 mb-6">
        <!-- <i class="fa-solid fa-circle-question text-[#D26986]"></i> -->
        <!-- <span class="text-sm font-semibold text-gray-700">Pusat Bantuan</span> -->
      </div>
      <h1 class="text-4xl md:text-5xl font-bold text-gray-900 mb-4">
        Pertanyaan yang Sering Diajukan
      </h1>
      <p class="text-lg text-gray-600 max-w-2xl mx-auto">
        Temukan jawaban untuk pertanyaan umum seputar produk, pengiriman, pembayaran, dan layanan kami.
      </p>
    </div>
  </div>
</section>

<!-- FAQ Section -->
<section class="py-16 bg-white">
  <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
    
    <!-- Filter Tabs -->
    <div class="flex flex-wrap justify-center gap-3 mb-12" x-data="{ activeTab: 'all' }">
      <button 
        @click="activeTab = 'all'" 
        :class="activeTab === 'all' ? 'bg-[#D26986] text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-rose-50'"
        class="faq-tab px-6 py-3 rounded-full font-semibold text-sm transition-all border border-rose-100"
        data-category="all">
        <i class="fa-solid fa-grid-2 mr-2"></i>Semua
      </button>
      <button 
        @click="activeTab = 'product'" 
        :class="activeTab === 'product' ? 'bg-[#D26986] text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-rose-50'"
        class="faq-tab px-6 py-3 rounded-full font-semibold text-sm transition-all border border-rose-100"
        data-category="product">
        <i class="fa-solid fa-box mr-2"></i>Produk
      </button>
      <button 
        @click="activeTab = 'shipping'" 
        :class="activeTab === 'shipping' ? 'bg-[#D26986] text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-rose-50'"
        class="faq-tab px-6 py-3 rounded-full font-semibold text-sm transition-all border border-rose-100"
        data-category="shipping">
        <i class="fa-solid fa-truck-fast mr-2"></i>Pengiriman
      </button>
      <button 
        @click="activeTab = 'payment'" 
        :class="activeTab === 'payment' ? 'bg-[#D26986] text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-rose-50'"
        class="faq-tab px-6 py-3 rounded-full font-semibold text-sm transition-all border border-rose-100"
        data-category="payment">
        <i class="fa-solid fa-credit-card mr-2"></i>Pembayaran
      </button>
      <button 
        @click="activeTab = 'other'" 
        :class="activeTab === 'other' ? 'bg-[#D26986] text-white shadow-lg' : 'bg-white text-gray-600 hover:bg-rose-50'"
        class="faq-tab px-6 py-3 rounded-full font-semibold text-sm transition-all border border-rose-100"
        data-category="other">
        <i class="fa-solid fa-ellipsis mr-2"></i>Lainnya
      </button>
    </div>

    <!-- FAQ Accordion -->
    <div class="space-y-4">
      
      <!-- FAQ Item: Produk 1 -->
      <div class="faq-item bg-white rounded-2xl border border-rose-100 shadow-sm hover:shadow-md transition-shadow" data-cat="product">
        <button class="faq-trigger w-full px-6 py-5 flex items-center justify-between text-left">
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center shrink-0 mt-1">
              <i class="fa-solid fa-box text-[#D26986]"></i>
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900">Apa itu mochi dan apa yang membuat produk Ekimochi istimewa?</h3>
            </div>
          </div>
          <i class="fa-solid fa-chevron-down faq-icon text-gray-400 transition-transform duration-300 shrink-0 ml-4"></i>
        </button>
        <div class="faq-content hidden px-6 pb-5">
          <div class="ml-14 text-gray-600 leading-relaxed">
            <p>Mochi adalah kue tradisional Jepang yang terbuat dari beras ketan yang ditumbuk hingga lembut dan kenyal. Ekimochi menghadirkan mochi premium dengan isian buah segar pilihan dan lelehan cokelat berkualitas tinggi. Kami menggunakan bahan-bahan premium, proses pembuatan yang higienis, dan resep autentik yang telah disempurnakan untuk menciptakan tekstur yang lembut sempurna di setiap gigitan.</p>
          </div>
        </div>
      </div>

      <!-- FAQ Item: Produk 2 -->
      <div class="faq-item bg-white rounded-2xl border border-rose-100 shadow-sm hover:shadow-md transition-shadow" data-cat="product">
        <button class="faq-trigger w-full px-6 py-5 flex items-center justify-between text-left">
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center shrink-0 mt-1">
              <i class="fa-solid fa-snowflake text-[#D26986]"></i>
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900">Bagaimana cara menyimpan mochi agar tetap fresh?</h3>
            </div>
          </div>
          <i class="fa-solid fa-chevron-down faq-icon text-gray-400 transition-transform duration-300 shrink-0 ml-4"></i>
        </button>
        <div class="faq-content hidden px-6 pb-5">
          <div class="ml-14 text-gray-600 leading-relaxed">
            <p class="mb-3">Mochi Ekimochi harus disimpan di dalam kulkas pada suhu 2-8°C untuk menjaga kesegaran dan teksturnya. Berikut panduan penyimpanan:</p>
            <ul class="list-disc ml-5 space-y-2">
              <li><strong>Di kulkas:</strong> Tahan hingga 3 hari dalam kemasan tertutup</li>
              <li><strong>Di freezer:</strong> Bisa disimpan hingga 2 minggu, diamkan 10-15 menit di suhu ruang sebelum dikonsumsi</li>
              <li><strong>Tips:</strong> Simpan dalam wadah kedap udara untuk mencegah mochi mengering</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- FAQ Item: Produk 3 -->
      <div class="faq-item bg-white rounded-2xl border border-rose-100 shadow-sm hover:shadow-md transition-shadow" data-cat="product">
        <button class="faq-trigger w-full px-6 py-5 flex items-center justify-between text-left">
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-rose-100 flex items-center justify-center shrink-0 mt-1">
              <i class="fa-solid fa-leaf text-[#D26986]"></i>
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900">Apakah produk Ekimochi halal dan aman untuk dikonsumsi?</h3>
            </div>
          </div>
          <i class="fa-solid fa-chevron-down faq-icon text-gray-400 transition-transform duration-300 shrink-0 ml-4"></i>
        </button>
        <div class="faq-content hidden px-6 pb-5">
          <div class="ml-14 text-gray-600 leading-relaxed">
            <p>Ya, semua produk Ekimochi dijamin halal dan diproduksi dengan standar higienis tinggi. Kami menggunakan bahan-bahan berkualitas premium yang aman dikonsumsi, tanpa pengawet berbahaya, dan diproduksi di dapur yang bersertifikat. Proses produksi kami mengikuti standar keamanan pangan yang ketat.</p>
          </div>
        </div>
      </div>

      <!-- FAQ Item: Pengiriman 1 -->
      <div class="faq-item bg-white rounded-2xl border border-rose-100 shadow-sm hover:shadow-md transition-shadow" data-cat="shipping">
        <button class="faq-trigger w-full px-6 py-5 flex items-center justify-between text-left">
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center shrink-0 mt-1">
              <i class="fa-solid fa-truck-fast text-blue-600"></i>
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900">Berapa lama estimasi pengiriman?</h3>
            </div>
          </div>
          <i class="fa-solid fa-chevron-down faq-icon text-gray-400 transition-transform duration-300 shrink-0 ml-4"></i>
        </button>
        <div class="faq-content hidden px-6 pb-5">
          <div class="ml-14 text-gray-600 leading-relaxed">
            <p class="mb-3">Estimasi pengiriman bervariasi tergantung lokasi dan metode pengiriman yang dipilih:</p>
            <ul class="list-disc ml-5 space-y-2">
              <li><strong>Same Day Delivery (Jakarta & Bandung):</strong> Pesanan diterima maksimal pukul 14.00, dikirim hari yang sama</li>
              <li><strong>Next Day Delivery (Jabodetabek):</strong> 1 hari kerja</li>
              <li><strong>Regular Delivery (Jawa):</strong> 2-3 hari kerja</li>
              <li><strong>Regular Delivery (Luar Jawa):</strong> 3-5 hari kerja</li>
            </ul>
            <p class="mt-3 text-sm">*Pengiriman menggunakan cold chain untuk menjaga kesegaran produk</p>
          </div>
        </div>
      </div>

      <!-- FAQ Item: Pengiriman 2 -->
      <div class="faq-item bg-white rounded-2xl border border-rose-100 shadow-sm hover:shadow-md transition-shadow" data-cat="shipping">
        <button class="faq-trigger w-full px-6 py-5 flex items-center justify-between text-left">
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center shrink-0 mt-1">
              <i class="fa-solid fa-box-open text-blue-600"></i>
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900">Apakah ada minimum pembelian untuk gratis ongkir?</h3>
            </div>
          </div>
          <i class="fa-solid fa-chevron-down faq-icon text-gray-400 transition-transform duration-300 shrink-0 ml-4"></i>
        </button>
        <div class="faq-content hidden px-6 pb-5">
          <div class="ml-14 text-gray-600 leading-relaxed">
            <p>Ya, kami menawarkan gratis ongkir dengan ketentuan berikut:</p>
            <ul class="list-disc ml-5 space-y-2 mt-3">
              <li><strong>Gratis Ongkir Reguler:</strong> Pembelian minimal Rp 200.000 untuk wilayah Jabodetabek</li>
              <li><strong>Gratis Ongkir Same Day:</strong> Pembelian Box of 6 atau lebih (gunakan kode promo khusus)</li>
              <li><strong>Promo Berkala:</strong> Pantau promo gratis ongkir di Instagram dan website kami</li>
            </ul>
          </div>
        </div>
      </div>

      <!-- FAQ Item: Pembayaran 1 -->
      <div class="faq-item bg-white rounded-2xl border border-rose-100 shadow-sm hover:shadow-md transition-shadow" data-cat="payment">
        <button class="faq-trigger w-full px-6 py-5 flex items-center justify-between text-left">
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center shrink-0 mt-1">
              <i class="fa-solid fa-credit-card text-green-600"></i>
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900">Metode pembayaran apa saja yang tersedia?</h3>
            </div>
          </div>
          <i class="fa-solid fa-chevron-down faq-icon text-gray-400 transition-transform duration-300 shrink-0 ml-4"></i>
        </button>
        <div class="faq-content hidden px-6 pb-5">
          <div class="ml-14 text-gray-600 leading-relaxed">
            <p class="mb-3">Kami menyediakan berbagai metode pembayaran untuk kemudahan Anda:</p>
            <ul class="list-disc ml-5 space-y-2">
              <li><strong>Transfer Bank:</strong> BCA, Mandiri, BNI, BRI</li>
              <li><strong>E-Wallet:</strong> GoPay, OVO, Dana, ShopeePay</li>
              <li><strong>Virtual Account:</strong> Semua bank via Midtrans</li>
              <li><strong>QRIS:</strong> Scan & bayar instan</li>
              <li><strong>Credit Card:</strong> Visa, Mastercard, JCB</li>
            </ul>
            <p class="mt-3 text-sm font-semibold text-[#D26986]">Semua transaksi aman dan terenkripsi</p>
          </div>
        </div>
      </div>

      <!-- FAQ Item: Pembayaran 2 -->
      <div class="faq-item bg-white rounded-2xl border border-rose-100 shadow-sm hover:shadow-md transition-shadow" data-cat="payment">
        <button class="faq-trigger w-full px-6 py-5 flex items-center justify-between text-left">
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center shrink-0 mt-1">
              <i class="fa-solid fa-clock text-green-600"></i>
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900">Berapa lama batas waktu pembayaran setelah checkout?</h3>
            </div>
          </div>
          <i class="fa-solid fa-chevron-down faq-icon text-gray-400 transition-transform duration-300 shrink-0 ml-4"></i>
        </button>
        <div class="faq-content hidden px-6 pb-5">
          <div class="ml-14 text-gray-600 leading-relaxed">
            <p>Setelah melakukan checkout, Anda memiliki waktu <strong class="text-gray-900">24 jam</strong> untuk menyelesaikan pembayaran. Jika pembayaran tidak dilakukan dalam batas waktu tersebut, pesanan akan otomatis dibatalkan dan stok akan dikembalikan. Pastikan untuk segera melakukan pembayaran agar pesanan Anda dapat segera diproses.</p>
          </div>
        </div>
      </div>

      <!-- FAQ Item: Lainnya 1 -->
      <div class="faq-item bg-white rounded-2xl border border-rose-100 shadow-sm hover:shadow-md transition-shadow" data-cat="other">
        <button class="faq-trigger w-full px-6 py-5 flex items-center justify-between text-left">
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center shrink-0 mt-1">
              <i class="fa-solid fa-gift text-purple-600"></i>
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900">Apakah bisa custom paket untuk hadiah/gift box?</h3>
            </div>
          </div>
          <i class="fa-solid fa-chevron-down faq-icon text-gray-400 transition-transform duration-300 shrink-0 ml-4"></i>
        </button>
        <div class="faq-content hidden px-6 pb-5">
          <div class="ml-14 text-gray-600 leading-relaxed">
            <p class="mb-3">Tentu! Kami menyediakan layanan custom paket gift box yang sempurna untuk berbagai acara:</p>
            <ul class="list-disc ml-5 space-y-2">
              <li>Pilih varian rasa favorit Anda (minimal 6 pcs)</li>
              <li>Kemasan gift box premium dengan pita cantik</li>
              <li>Tambahkan kartu ucapan personal (gratis)</li>
              <li>Cocok untuk ulang tahun, anniversary, parcel lebaran, dll</li>
            </ul>
            <p class="mt-3">Hubungi customer service kami untuk pemesanan custom dalam jumlah besar (50+ pcs) dengan harga spesial.</p>
          </div>
        </div>
      </div>

      <!-- FAQ Item: Lainnya 2 -->
      <div class="faq-item bg-white rounded-2xl border border-rose-100 shadow-sm hover:shadow-md transition-shadow" data-cat="other">
        <button class="faq-trigger w-full px-6 py-5 flex items-center justify-between text-left">
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center shrink-0 mt-1">
              <i class="fa-solid fa-rotate-left text-purple-600"></i>
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900">Bagaimana kebijakan retur dan pengembalian dana?</h3>
            </div>
          </div>
          <i class="fa-solid fa-chevron-down faq-icon text-gray-400 transition-transform duration-300 shrink-0 ml-4"></i>
        </button>
        <div class="faq-content hidden px-6 pb-5">
          <div class="ml-14 text-gray-600 leading-relaxed">
            <p class="mb-3">Kepuasan Anda adalah prioritas kami. Kebijakan retur:</p>
            <ul class="list-disc ml-5 space-y-2">
              <li><strong>Produk rusak/cacat:</strong> Retur full refund dengan foto bukti dalam 24 jam setelah diterima</li>
              <li><strong>Kesalahan pengiriman:</strong> Kami ganti produk yang benar tanpa biaya tambahan</li>
              <li><strong>Keterlambatan ekstrem:</strong> Kompensasi voucher atau refund parsial</li>
              <li><strong>Tidak bisa retur:</strong> Produk yang sudah dibuka/dikonsumsi kecuali terbukti cacat produksi</li>
            </ul>
            <p class="mt-3 text-sm">Hubungi customer service kami segera jika ada masalah dengan pesanan Anda.</p>
          </div>
        </div>
      </div>

      <!-- FAQ Item: Lainnya 3 -->
      <div class="faq-item bg-white rounded-2xl border border-rose-100 shadow-sm hover:shadow-md transition-shadow" data-cat="other">
        <button class="faq-trigger w-full px-6 py-5 flex items-center justify-between text-left">
          <div class="flex items-start gap-4">
            <div class="w-10 h-10 rounded-full bg-purple-100 flex items-center justify-center shrink-0 mt-1">
              <i class="fa-solid fa-headset text-purple-600"></i>
            </div>
            <div>
              <h3 class="text-lg font-semibold text-gray-900">Bagaimana cara menghubungi customer service?</h3>
            </div>
          </div>
          <i class="fa-solid fa-chevron-down faq-icon text-gray-400 transition-transform duration-300 shrink-0 ml-4"></i>
        </button>
        <div class="faq-content hidden px-6 pb-5">
          <div class="ml-14 text-gray-600 leading-relaxed">
            <p class="mb-3">Tim customer service kami siap membantu Anda:</p>
            <div class="space-y-3">
              <div class="flex items-center gap-3 p-3 bg-rose-50 rounded-lg">
                <i class="fa-brands fa-whatsapp text-green-600 text-xl"></i>
                <div>
                  <p class="font-semibold text-gray-900">WhatsApp</p>
                  <p class="text-sm">+62 812-3456-7890 (08:00 - 21:00 WIB)</p>
                </div>
              </div>
              <div class="flex items-center gap-3 p-3 bg-rose-50 rounded-lg">
                <i class="fa-solid fa-envelope text-[#D26986] text-xl"></i>
                <div>
                  <p class="font-semibold text-gray-900">Email</p>
                  <p class="text-sm">hello@ekimochi.com</p>
                </div>
              </div>
              <div class="flex items-center gap-3 p-3 bg-rose-50 rounded-lg">
                <i class="fa-brands fa-instagram text-pink-600 text-xl"></i>
                <div>
                  <p class="font-semibold text-gray-900">Instagram</p>
                  <p class="text-sm">@ekimochi.id (DM 24/7)</p>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>

    </div>

  </div>
</section>

<!-- CTA Section -->
<section class="bg-gradient-to-r from-[#D26986] to-[#BD5773] py-16">
  <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
      Masih Ada Pertanyaan?
    </h2>
    <p class="text-white/90 text-lg mb-8">
      Tim kami siap membantu Anda 24/7. Jangan ragu untuk menghubungi kami!
    </p>
    <div class="flex flex-col sm:flex-row gap-4 justify-center">
      <a href="https://wa.me/6281234567890" target="_blank" class="inline-flex items-center justify-center gap-2 bg-white text-[#D26986] px-8 py-4 rounded-full font-bold text-lg shadow-xl hover:shadow-2xl hover:scale-105 transition-all">
        <i class="fa-brands fa-whatsapp text-xl"></i>
        <span>Chat via WhatsApp</span>
      </a>
      <a href="{{ route('catalog.index') }}" class="inline-flex items-center justify-center gap-2 bg-white/10 backdrop-blur-sm text-white border-2 border-white px-8 py-4 rounded-full font-bold text-lg hover:bg-white hover:text-[#D26986] transition-all">
        <i class="fa-solid fa-shopping-bag"></i>
        <span>Mulai Belanja</span>
      </a>
    </div>
  </div>
</section>

<script>
document.addEventListener('DOMContentLoaded', () => {
  // Accordion Logic
  const triggers = document.querySelectorAll('.faq-trigger');
  triggers.forEach(trigger => {
    trigger.addEventListener('click', () => {
      const item = trigger.closest('.faq-item');
      const content = item.querySelector('.faq-content');
      const icon = trigger.querySelector('.faq-icon');
      const isOpen = !content.classList.contains('hidden');

      // Close all items
      document.querySelectorAll('.faq-content').forEach(c => c.classList.add('hidden'));
      document.querySelectorAll('.faq-icon').forEach(ic => ic.classList.remove('rotate-180'));

      // Open clicked item if it was closed
      if (!isOpen) {
        content.classList.remove('hidden');
        icon.classList.add('rotate-180');
      }
    });
  });

  // Filter Logic
  const filterTabs = document.querySelectorAll('.faq-tab');
  const faqItems = document.querySelectorAll('.faq-item');

  filterTabs.forEach(tab => {
    tab.addEventListener('click', () => {
      const category = tab.getAttribute('data-category');

      // Filter items
      faqItems.forEach(item => {
        if (category === 'all' || item.getAttribute('data-cat') === category) {
          item.style.display = 'block';
        } else {
          item.style.display = 'none';
        }
      });

      // Close all accordions when filter changes
      document.querySelectorAll('.faq-content').forEach(c => c.classList.add('hidden'));
      document.querySelectorAll('.faq-icon').forEach(ic => ic.classList.remove('rotate-180'));
    });
  });
});
</script>
@endsection
