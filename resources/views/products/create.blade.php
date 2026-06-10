<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
</head>
<body>
    <h1>Tambah Produk</h1>

    @if ($errors->any())
        <div style="color: red; margin-bottom: 12px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('products.store') }}">
        @csrf
        <div>
            <label>Kategori</label>
            <select name="category_id" required>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label>Nama</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
        </div>
        <div>
            <label>Deskripsi</label>
            <textarea name="description">{{ old('description') }}</textarea>
        </div>
        <div>
            <label>Harga</label>
            <input type="number" name="price" min="0" step="0.01" value="{{ old('price') }}" required>
        </div>
        <div>
            <label>Stok</label>
            <input type="number" name="stock" min="0" value="{{ old('stock') }}" required>
        </div>
        <button type="submit">Simpan</button>
    </form>
</body>
</html>
