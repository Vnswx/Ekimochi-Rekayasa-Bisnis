<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->name }} - Ekimochi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background: #f8f9fa; color: #333; }
        
        /* Navbar */
        .navbar { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 15px 0; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .nav-container { max-width: 1200px; margin: 0 auto; padding: 0 20px; display: flex; justify-content: space-between; align-items: center; }
        .nav-brand { font-size: 28px; font-weight: bold; text-decoration: none; color: white; }
        .nav-links { display: flex; gap: 25px; align-items: center; }
        .nav-links a { color: white; text-decoration: none; transition: opacity 0.3s; }
        .nav-links a:hover { opacity: 0.8; }
        .btn-auth { background: white; color: #667eea; padding: 8px 20px; border-radius: 20px; font-weight: 600; }
        
        /* Container */
        .container { max-width: 1200px; margin: 0 auto; padding: 40px 20px; }
        .back-link { display: inline-block; color: #667eea; text-decoration: none; margin-bottom: 20px; font-weight: 600; }
        .back-link:hover { text-decoration: underline; }
        
        /* Product Detail */
        .product-detail { background: white; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); overflow: hidden; margin-bottom: 40px; }
        .product-main { display: grid; grid-template-columns: 1fr 1fr; gap: 40px; padding: 40px; }
        
        .product-image-section { }
        .product-image { width: 100%; height: 500px; object-fit: cover; border-radius: 12px; background: #f0f0f0; }
        
        .product-info { display: flex; flex-direction: column; gap: 20px; }
        .product-category { font-size: 14px; color: #667eea; font-weight: 600; text-transform: uppercase; }
        .product-name { font-size: 32px; font-weight: bold; color: #333; line-height: 1.3; }
        .product-sku { font-size: 14px; color: #999; }
        .product-price { font-size: 36px; font-weight: bold; color: #667eea; margin: 10px 0; }
        
        .product-stock { padding: 15px; background: #f8f9fa; border-radius: 8px; }
        .stock-available { color: #28a745; font-weight: 600; }
        .stock-low { color: #ffc107; font-weight: 600; }
        .stock-out { color: #dc3545; font-weight: 600; }
        
        .product-description { }
        .product-description h3 { font-size: 18px; margin-bottom: 10px; color: #555; }
        .product-description p { line-height: 1.8; color: #666; }
        
        .product-meta { display: grid; grid-template-columns: 1fr 1fr; gap: 15px; padding-top: 20px; border-top: 1px solid #eee; }
        .meta-item { }
        .meta-label { font-size: 14px; color: #999; margin-bottom: 5px; }
        .meta-value { font-size: 16px; font-weight: 600; color: #333; }
        
        /* Related Products */
        .related-section { margin-top: 60px; }
        .related-section h2 { font-size: 28px; margin-bottom: 30px; color: #333; }
        .related-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(250px, 1fr)); gap: 20px; }
        
        .related-card { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.3s; cursor: pointer; }
        .related-card:hover { transform: translateY(-3px); box-shadow: 0 6px 16px rgba(0,0,0,0.12); }
        .related-image { width: 100%; height: 180px; object-fit: cover; background: #f0f0f0; }
        .related-content { padding: 15px; }
        .related-name { font-size: 16px; font-weight: 600; color: #333; margin-bottom: 8px; }
        .related-price { font-size: 18px; font-weight: bold; color: #667eea; }
        
        /* Responsive */
        @media (max-width: 768px) {
            .product-main { grid-template-columns: 1fr; padding: 20px; }
            .product-image { height: 350px; }
            .product-name { font-size: 24px; }
            .product-price { font-size: 28px; }
            .product-meta { grid-template-columns: 1fr; }
            .related-grid { grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); }
        }
    </style>
</head>
<body>
    <!-- Navbar -->
    <nav class="navbar">
        <div class="nav-container">
            <a href="/" class="nav-brand">🍡 Ekimochi</a>
            <div class="nav-links">
                <a href="{{ route('catalog.index') }}">Katalog</a>
                @auth
                    <a href="{{ route('dashboard') }}">Dashboard</a>
                    @if(Auth::user()->isAdmin())
                        <a href="{{ route('admin.products.index') }}">Admin</a>
                    @endif
                    <a href="{{ route('profile.show') }}">Profile</a>
                @else
                    <a href="{{ route('login') }}" class="btn-auth">Login</a>
                @endauth
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <div class="container">
        <a href="{{ route('catalog.index') }}" class="back-link">← Kembali ke Katalog</a>
        
        <!-- Product Detail -->
        <div class="product-detail">
            <div class="product-main">
                <!-- Product Image -->
                <div class="product-image-section">
                    @if($product->image)
                        <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
                    @else
                        <div class="product-image" style="display: flex; align-items: center; justify-content: center; font-size: 120px;">🍡</div>
                    @endif
                </div>
                
                <!-- Product Info -->
                <div class="product-info">
                    <div>
                        <div class="product-category">{{ $product->category->name }}</div>
                        <h1 class="product-name">{{ $product->name }}</h1>
                        <div class="product-sku">SKU: {{ $product->sku }}</div>
                    </div>
                    
                    <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                    
                    <div class="product-stock">
                        @if($product->stock > 10)
                            <span class="stock-available">✓ Stok Tersedia ({{ $product->stock }} {{ $product->unit }})</span>
                        @elseif($product->stock > 0)
                            <span class="stock-low">⚠ Stok Terbatas ({{ $product->stock }} {{ $product->unit }})</span>
                        @else
                            <span class="stock-out">✗ Stok Habis</span>
                        @endif
                    </div>
                    
                    @if($product->description)
                        <div class="product-description">
                            <h3>Deskripsi Produk</h3>
                            <p>{{ $product->description }}</p>
                        </div>
                    @endif
                    
                    <div class="product-meta">
                        <div class="meta-item">
                            <div class="meta-label">Kategori</div>
                            <div class="meta-value">{{ $product->category->name }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Satuan</div>
                            <div class="meta-value">{{ $product->unit }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Status</div>
                            <div class="meta-value">{{ $product->status === 'active' ? 'Aktif' : 'Tidak Aktif' }}</div>
                        </div>
                        <div class="meta-item">
                            <div class="meta-label">Ditambahkan</div>
                            <div class="meta-value">{{ $product->created_at->format('d M Y') }}</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Related Products -->
        @if($relatedProducts->count() > 0)
            <div class="related-section">
                <h2>Produk Terkait</h2>
                <div class="related-grid">
                    @foreach($relatedProducts as $related)
                        <div class="related-card" onclick="window.location='{{ route('catalog.show', $related) }}'">
                            @if($related->image)
                                <img src="{{ asset('storage/' . $related->image) }}" alt="{{ $related->name }}" class="related-image">
                            @else
                                <div class="related-image" style="display: flex; align-items: center; justify-content: center; font-size: 48px;">🍡</div>
                            @endif
                            
                            <div class="related-content">
                                <div class="related-name">{{ $related->name }}</div>
                                <div class="related-price">Rp {{ number_format($related->price, 0, ',', '.') }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endif
    </div>
</body>
</html>
