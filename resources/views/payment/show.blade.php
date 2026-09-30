<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pembayaran - {{ $order->order_number }}</title>
    
    <!-- Midtrans Snap.js -->
    @if(config('midtrans.is_production'))
        <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @else
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('midtrans.client_key') }}"></script>
    @endif

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); min-height: 100vh; display: flex; align-items: center; justify-content: center; padding: 20px; }
        .payment-container { max-width: 500px; width: 100%; background: white; border-radius: 15px; box-shadow: 0 20px 60px rgba(0,0,0,0.3); overflow: hidden; }
        .payment-header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; }
        .payment-header h1 { font-size: 1.5em; margin-bottom: 5px; }
        .payment-header .order-number { font-size: 0.9em; opacity: 0.9; }
        .payment-body { padding: 30px; }
        .order-info { background: #f8f9fa; padding: 20px; border-radius: 10px; margin-bottom: 25px; }
        .info-row { display: flex; justify-content: space-between; padding: 10px 0; border-bottom: 1px solid #e9ecef; }
        .info-row:last-child { border-bottom: none; }
        .info-label { color: #666; font-size: 0.9em; }
        .info-value { font-weight: 600; color: #333; }
        .total-amount { font-size: 1.8em; color: #667eea; text-align: center; margin: 20px 0; font-weight: bold; }
        .status-badge { display: inline-block; padding: 5px 15px; border-radius: 20px; font-size: 0.85em; font-weight: 600; }
        .status-pending { background: #fff3cd; color: #856404; }
        .status-settlement { background: #d4edda; color: #155724; }
        .payment-button { width: 100%; padding: 15px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; border: none; border-radius: 10px; font-size: 16px; font-weight: bold; cursor: pointer; transition: transform 0.2s; }
        .payment-button:hover { transform: translateY(-2px); }
        .payment-button:active { transform: translateY(0); }
        .payment-info { background: #e7f3ff; padding: 15px; border-radius: 8px; margin-top: 20px; font-size: 0.9em; color: #004085; }
        .payment-methods { display: grid; grid-template-columns: repeat(2, 1fr); gap: 10px; margin-top: 15px; }
        .method-badge { background: white; padding: 8px; border-radius: 8px; text-align: center; font-size: 0.8em; border: 1px solid #dee2e6; }
        .loading { text-align: center; color: #666; display: none; }
        .loading.active { display: block; }
    </style>
</head>
<body>
    <div class="payment-container">
        <div class="payment-header">
            <h1>💳 Pembayaran</h1>
            <div class="order-number">{{ $order->order_number }}</div>
        </div>

        <div class="payment-body">
            <div class="order-info">
                <div class="info-row">
                    <span class="info-label">Status Order</span>
                    <span>
                        @if($payment->status === 'pending')
                            <span class="status-badge status-pending">Menunggu Pembayaran</span>
                        @elseif($payment->status === 'settlement')
                            <span class="status-badge status-settlement">Pembayaran Berhasil</span>
                        @else
                            <span class="status-badge">{{ ucfirst($payment->status) }}</span>
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">Nama</span>
                    <span class="info-value">{{ $order->customer_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">Email</span>
                    <span class="info-value">{{ $order->customer_email }}</span>
                </div>
            </div>

            <div class="total-amount">
                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
            </div>

            @if($payment->status === 'pending')
                <button id="pay-button" class="payment-button">
                    🚀 Bayar Sekarang
                </button>

                <div id="loading" class="loading">
                    <p>Membuka halaman pembayaran...</p>
                </div>

                <div class="payment-info">
                    <strong>📌 Metode Pembayaran Tersedia:</strong>
                    <div class="payment-methods">
                        <div class="method-badge">📱 QRIS</div>
                        <div class="method-badge">🏦 BCA VA</div>
                        <div class="method-badge">🏦 BNI VA</div>
                        <div class="method-badge">🏦 BRI VA</div>
                        <div class="method-badge">🏦 Permata VA</div>
                        <div class="method-badge">🏦 Mandiri</div>
                    </div>
                    <p style="margin-top: 10px; font-size: 0.85em;">
                        Pilih metode pembayaran favorit Anda di halaman Midtrans.
                    </p>
                </div>
            @elseif($payment->status === 'settlement')
                <div style="text-align: center; padding: 20px; background: #d4edda; border-radius: 10px;">
                    <div style="font-size: 3em; margin-bottom: 10px;">✅</div>
                    <h2 style="color: #155724; margin-bottom: 10px;">Pembayaran Berhasil!</h2>
                    <p style="color: #155724;">Terima kasih. Pesanan Anda sedang diproses.</p>
                </div>
            @else
                <div style="text-align: center; padding: 20px; background: #f8d7da; border-radius: 10px;">
                    <p style="color: #721c24;">Status: {{ ucfirst($payment->status) }}</p>
                </div>
            @endif

            <div style="text-align: center; margin-top: 20px;">
                <a href="{{ route('catalog.index') }}" style="color: #667eea; text-decoration: none; font-weight: 500;">
                    ← Kembali ke Beranda
                </a>
            </div>
        </div>
    </div>

    @if($payment->status === 'pending')
    <script>
        const payButton = document.getElementById('pay-button');
        const loading = document.getElementById('loading');

        payButton.addEventListener('click', function() {
            loading.classList.add('active');
            payButton.style.display = 'none';

            snap.pay('{{ $payment->snap_token }}', {
                onSuccess: function(result) {
                    alert('Pembayaran berhasil! ✅');
                    console.log('Success:', result);
                    window.location.href = '{{ route('payment.show', $payment) }}';
                },
                onPending: function(result) {
                    alert('Menunggu pembayaran Anda. Silakan selesaikan pembayaran.');
                    console.log('Pending:', result);
                    // Bisa redirect ke halaman status
                },
                onError: function(result) {
                    alert('Pembayaran gagal! Silakan coba lagi.');
                    console.error('Error:', result);
                    loading.classList.remove('active');
                    payButton.style.display = 'block';
                },
                onClose: function() {
                    console.log('Customer closed the popup without finishing payment');
                    loading.classList.remove('active');
                    payButton.style.display = 'block';
                }
            });
        });

        // Auto-refresh status setiap 5 detik
        @if($payment->status === 'pending')
        setInterval(function() {
            fetch('{{ route('payment.status', $payment) }}')
                .then(response => response.json())
                .then(data => {
                    if (data.status === 'settlement') {
                        window.location.reload();
                    }
                });
        }, 5000);
        @endif
    </script>
    @endif
</body>
</html>
