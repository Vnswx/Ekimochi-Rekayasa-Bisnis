<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Products - Admin Ekimochi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .navbar { background: #dc3545; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .navbar h1 { font-size: 24px; }
        .navbar-right { display: flex; gap: 15px; align-items: center; }
        .navbar a { color: white; text-decoration: none; }
        .container { max-width: 1200px; margin: 30px auto; padding: 0 20px; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); margin-bottom: 20px; }
        .success { color: green; padding: 10px; background: #d4edda; border-radius: 4px; margin-bottom: 15px; }
        .error { color: red; padding: 10px; background: #f8d7da; border-radius: 4px; margin-bottom: 15px; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; }
        .btn:hover { background: #0056b3; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #218838; }
        .btn-danger { background: #dc3545; }
        .btn-danger:hover { background: #c82333; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { padding: 12px; text-align: left; border-bottom: 1px solid #ddd; }
        th { background: #f8f9fa; font-weight: bold; }
        .product-img { width: 50px; height: 50px; object-fit: cover; border-radius: 4px; }
        .badge { padding: 4px 12px; border-radius: 12px; font-size: 12px; font-weight: bold; }
        .badge-active { background: #28a745; color: white; }
        .badge-inactive { background: #6c757d; color: white; }
        .actions { display: flex; gap: 10px; }
        .pagination { display: flex; justify-content: center; gap: 10px; margin-top: 20px; }
        .logout-btn { background: #343a40; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>Ekimochi Admin - Products</h1>
        <div class="navbar-right">
            <span>{{ Auth::user()->name }}</span>
            <a href="{{ route('admin.dashboard') }}">Dashboard</a>
            <a href="{{ route('admin.categories.index') }}">Categories</a>
            <a href="{{ route('profile.show') }}">Profile</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container">
        @if(session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        @if($errors->any())
            <div class="error">
                <ul style="margin: 0; padding-left: 20px;">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="card">
            <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                <h2>Daftar Produk</h2>
                <a href="{{ route('admin.products.create') }}" class="btn btn-success">+ Tambah Produk</a>
            </div>

            @if($products->count() > 0)
                <table>
                    <thead>
                        <tr>
                            <th>Gambar</th>
                            <th>SKU</th>
                            <th>Nama</th>
                            <th>Kategori</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                        <tr>
                            <td>
                                @if($product->image)
                                    <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="product-img">
                                @else
                                    <div style="width: 50px; height: 50px; background: #ddd; border-radius: 4px; display: flex; align-items: center; justify-content: center; font-size: 12px;">No Image</div>
                                @endif
                            </td>
                            <td>{{ $product->sku }}</td>
                            <td><strong>{{ $product->name }}</strong></td>
                            <td>{{ $product->category->name }}</td>
                            <td>Rp {{ number_format($product->price, 0, ',', '.') }}</td>
                            <td>{{ $product->stock }} {{ $product->unit }}</td>
                            <td>
                                <span class="badge badge-{{ $product->status }}">
                                    {{ strtoupper($product->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="actions">
                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn" style="padding: 5px 10px; font-size: 14px;">Edit</a>
                                    <form method="POST" action="{{ route('admin.products.destroy', $product) }}" style="display: inline;">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger" style="padding: 5px 10px; font-size: 14px;" onclick="return confirm('Hapus produk ini?')">Hapus</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="pagination">
                    {{ $products->links() }}
                </div>
            @else
                <p style="text-align: center; color: #666; padding: 40px;">Belum ada produk. <a href="{{ route('admin.products.create') }}">Tambah produk pertama</a></p>
            @endif
        </div>
    </div>
</body>
</html>
