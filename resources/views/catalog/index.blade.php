<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Katalog Produk - Ekimochi</title>
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
        
        /* Hero Section */
        .hero { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 40px 20px; text-align: center; }
        .hero h1 { font-size: 36px; margin-bottom: 10px; }
        .hero p { font-size: 18px; opacity: 0.9; }
        
        /* Container */
        .container { max-width: 1200px; margin: 0 auto; padding: 40px 20px; }
        
        /* Filter Section */
        .filter-section { background: white; padding: 25px; border-radius: 12px; box-shadow: 0 2px 8px rgba(0,0,0,0.08); margin-bottom: 30px; }
        .filter-row { display: flex; gap: 15px; flex-wrap: wrap; align-items: end; }
        .filter-group { flex: 1; min-width: 200px; }
        .filter-group label { display: block; margin-bottom: 8px; font-weight: 600; color: #555; font-size: 14px; }
        .filter-group input, .filter-group select { width: 100%; padding: 10px 15px; border: 1px solid #ddd; border-radius: 8px; font-size: 14px; }
        .filter-group input:focus, .filter-group select:focus { outline: none; border-color: #667eea; }
        .btn-filter { background: #667eea; color: white; border: none; padding: 11px 25px; border-radius: 8px; cursor: pointer; font-weight: 600; transition: all 0.3s; }
        .btn-filter:hover { background: #5568d3; transform: translateY(-1px); }
        .btn-reset { background: #6c757d; color: white; border: none; padding: 11px 25px; border-radius: 8px; cursor: pointer; font-weight: 600; text-decoration: none; display: inline-block; }
        
        /* Product Grid */
        .products-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 25px; }
        .products-header h2 { font-size: 24px; color: #333; }
        .product-count { color: #666; font-size: 14px; }
        
        .product-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 25px; }
        
        /* Product Card */
        .product-card { background: white; border-radius: 12px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); transition: all 0.3s; cursor: pointer; }
        .product-card:hover { transform: translateY(-5px); box-shadow: 0 8px 20px rgba(0,0,0,0.12); }
        .product-image { width: 100%; height: 220px; object-fit: cover; background: #f0f0f0; }
        .product-content { padding: 20px; }
        .product-category { font-size: 12px; color: #667eea; font-weight: 600; text-transform: uppercase; margin-bottom: 8px; }
        .product-name { font-size: 18px; font-weight: 600; color: #333; margin-bottom: 10px; line-height: 1.4; }
        .product-price { font-size: 24px; font-weight: bold; color: #667eea; margin-bottom: 10px; }
        .product-stock { font-size: 13px; color: #666; margin-bottom: 15px; }
        .stock-available { color: #28a745; font-weight: 600; }
        .stock-low { color: #ffc107; font-weight: 600; }
        .stock-out { color: #dc3545; font-weight: 600; }
        .btn-detail { display: block; text-align: center; background: #667eea; color: white; padding: 10px; border-radius: 8px; text-decoration: none; font-weight: 600; transition: all 0.3s; }
        .btn-detail:hover { background: #5568d3; }
        
        /* Empty State */
        .empty-state { text-align: center; padding: 60px 20px; }
        .empty-state-icon { font-size: 64px; margin-bottom: 20px; opacity: 0.3; }
        .empty-state h3 { font-size: 24px; color: #666; margin-bottom: 10px; }
        .empty-state p { color: #999; }
        
        /* Pagination */
        .pagination { display: flex; justify-content: center; gap: 10px; margin-top: 40px; flex-wrap: wrap; }
        .pagination a, .pagination span { padding: 10px 16px; border-radius: 8px; text-decoration: none; color: #667eea; border: 1px solid #ddd; transition: all 0.3s; }
        .pagination a:hover { background: #667eea; color: white; border-color: #667eea; }
        .pagination .active { background: #667eea; color: white; border-color: #667eea; font-weight: 600; }
        
        /* Responsive */
        @media (max-width: 768px) {
            .hero h1 { font-size: 28px; }
            .hero p { font-size: 16px; }
            .filter-row { flex-direction: column; }
            .filter-group { width: 100%; }
            .product-grid { grid-template-columns: repeat(auto-fill, minmax(240px, 1fr)); gap: 20px; }
            .nav-links { gap: 15px; font-size: 14px; }
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

    <!-- Hero -->
    <div class="hero">
        <h1>Katalog Produk Ekimochi</h1>
        <p>Temukan berbagai varian mochi premium dengan cita rasa istimewa</p>
    </div>

    <!-- Main Content -->
    <div class="container">
        <!-- Filter Section -->
        <div class="filter-section">
            <form method="GET" action="{{ route('catalog.index') }}">
                <div class="filter-row">
                    <div class="filter-group">
                        <label for="search">Cari Produk</label>
                        <input type="text" id="search" name="search" placeholder="Nama produk atau SKU..." value="{{ request('search') }}">
                    </div>
                    
                    <div class="filter-group">
                        <label for="category">Kategori</label>
                        <select id="category" name="category">
                            <option value="">Semua Kategori</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ request('category') == $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }} ({{ $cat->products_count }})
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="stock">Ketersediaan</label>
                        <select id="stock" name="stock">
                            <option value="">Semua</option>
                            <option value="available" {{ request('stock') == 'available' ? 'selected' : '' }}>Tersedia</option>
                            <option value="out" {{ request('stock') == 'out' ? 'selected' : '' }}>Habis</option>
                        </select>
                    </div>
                    
                    <div class="filter-group">
                        <label for="sort">Urutkan</label>
                        <select id="sort" name="sort">
                            <option value="latest" {{ request('sort') == 'latest' ? 'selected' : '' }}>Terbaru</option>
                            <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>Terlama</option>
                            <option value="name_asc" {{ request('sort') == 'name_asc' ? 'selected' : '' }}>Nama A-Z</option>
                            <option value="name_desc" {{ request('sort') == 'name_desc' ? 'selected' : '' }}>Nama Z-A</option>
                            <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>Harga Terendah</option>
                            <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                        </select>
                    </div>
                    
                    <div class="filter-group" style="flex: 0;">
                        <button type="submit" class="btn-filter">Filter</button>
                    </div>
                    
                    @if(request()->hasAny(['search', 'category', 'stock', 'sort']))
                        <div class="filter-group" style="flex: 0;">
                            <a href="{{ route('catalog.index') }}" class="btn-reset">Reset</a>
                        </div>
                    @endif
                </div>
            </form>
        </div>

        <!-- Products Header -->
        <div class="products-header">
            <h2>Produk Kami</h2>
            <span class="product-count">{{ $products->total() }} produk ditemukan</span>
        </div>

        <!-- Product Grid -->
        @if($products->count() > 0)
            <div class="product-grid">
                @foreach($products as $product)
                    <div class="product-card" onclick="window.location='{{ route('catalog.show', $product) }}'">
                        @if($product->image)
                            <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-image">
                        @else
                            <div class="product-image" style="display: flex; align-items: center; justify-content: center; font-size: 48px;">🍡</div>
                        @endif
                        
                        <div class="product-content">
                            <div class="product-category">{{ $product->category->name }}</div>
                            <h3 class="product-name">{{ $product->name }}</h3>
                            <div class="product-price">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                            
                            <div class="product-stock">
                                @if($product->stock > 10)
                                    <span class="stock-available">✓ Stok Tersedia</span>
                                @elseif($product->stock > 0)
                                    <span class="stock-low">⚠ Stok Terbatas ({{ $product->stock }} {{ $product->unit }})</span>
                                @else
                                    <span class="stock-out">✗ Stok Habis</span>
                                @endif
                            </div>
                            
                            <a href="{{ route('catalog.show', $product) }}" class="btn-detail" onclick="event.stopPropagation()">Lihat Detail</a>
                        </div>
                    </div>
                @endforeach
            </div>

            <!-- Pagination -->
            <div class="pagination">
                {{ $products->links() }}
            </div>
        @else
            <div class="empty-state">
                <div class="empty-state-icon">🔍</div>
                <h3>Produk tidak ditemukan</h3>
                <p>Coba gunakan kata kunci atau filter yang berbeda</p>
                <a href="{{ route('catalog.index') }}" class="btn-filter" style="margin-top: 20px; display: inline-block;">Lihat Semua Produk</a>
            </div>
        @endif
    </div>
</body>
</html>
