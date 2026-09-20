<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Admin Ekimochi</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: Arial, sans-serif; background: #f5f5f5; }
        .navbar { background: #dc3545; color: white; padding: 15px 30px; display: flex; justify-content: space-between; align-items: center; }
        .navbar h1 { font-size: 24px; }
        .navbar-right { display: flex; gap: 15px; align-items: center; }
        .navbar a { color: white; text-decoration: none; }
        .container { max-width: 800px; margin: 30px auto; padding: 0 20px; }
        .card { background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .form-group { margin-bottom: 20px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; color: #555; }
        input[type="text"], input[type="number"], select, textarea, input[type="file"] { width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 4px; }
        textarea { min-height: 100px; resize: vertical; }
        .btn { display: inline-block; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 4px; border: none; cursor: pointer; }
        .btn:hover { background: #0056b3; }
        .btn-success { background: #28a745; }
        .btn-success:hover { background: #218838; }
        .back-link { display: inline-block; margin-bottom: 20px; color: #dc3545; }
        .error { color: red; font-size: 14px; margin-top: 5px; }
        .info-text { color: #666; font-size: 14px; margin-top: 5px; }
        .logout-btn { background: #343a40; color: white; border: none; padding: 8px 16px; border-radius: 4px; cursor: pointer; }
    </style>
</head>
<body>
    <nav class="navbar">
        <h1>Ekimochi Admin - Tambah Produk</h1>
        <div class="navbar-right">
            <span>{{ Auth::user()->name }}</span>
            <a href="{{ route('admin.products.index') }}">Products</a>
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        </div>
    </nav>

    <div class="container">
        <a href="{{ route('admin.products.index') }}" class="back-link">← Kembali ke Daftar Produk</a>

        <div class="card">
            <h2 style="margin-bottom: 20px;">Tambah Produk Baru</h2>

            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="form-group">
                    <label for="name">Nama Produk *</label>
                    <input type="text" id="name" name="name" value="{{ old('name') }}" required>
                    @error('name')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="sku">SKU *</label>
                    <input type="text" id="sku" name="sku" value="{{ old('sku') }}" placeholder="Contoh: EKI-001" required>
                    @error('sku')
                        <div class="error">{{ $message }}</div>
                    @enderror
                    <small class="info-text">Kode unik produk</small>
                </div>

                <div class="form-group">
                    <label for="category_id">Kategori *</label>
                    <select id="category_id" name="category_id" required>
                        <option value="">Pilih Kategori</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('category_id')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="description">Deskripsi</label>
                    <textarea id="description" name="description">{{ old('description') }}</textarea>
                    @error('description')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="price">Harga *</label>
                    <input type="number" id="price" name="price" value="{{ old('price') }}" min="0" step="0.01" required>
                    @error('price')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="stock">Stok *</label>
                    <input type="number" id="stock" name="stock" value="{{ old('stock', 0) }}" min="0" required>
                    @error('stock')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="unit">Satuan *</label>
                    <input type="text" id="unit" name="unit" value="{{ old('unit') }}" placeholder="Contoh: pcs, box, pack" required>
                    @error('unit')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="status">Status *</label>
                    <select id="status" name="status" required>
                        <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                        <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                    </select>
                    @error('status')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="image">Gambar Produk</label>
                    <input type="file" id="image" name="image" accept="image/*">
                    @error('image')
                        <div class="error">{{ $message }}</div>
                    @enderror
                    <small class="info-text">Format: JPG, PNG. Maksimal 2MB</small>
                </div>

                <button type="submit" class="btn btn-success">Simpan Produk</button>
                <a href="{{ route('admin.products.index') }}" class="btn" style="background: #6c757d; margin-left: 10px;">Batal</a>
            </form>
        </div>
    </div>
</body>
</html>
