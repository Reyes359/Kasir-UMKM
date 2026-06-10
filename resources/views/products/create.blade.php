<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk</title>
</head>
<body>
    <h1>Tambah Produk</h1>

    <form method="POST" action="{{ route('products.store') }}">
        @csrf
        <div>
            <label for="category_id">Kategori</label>
            <select name="category_id" id="category_id" required class="@error('category_id') is-invalid @enderror">
                <option value="">Pilih kategori</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
            @error('category_id')
                <div id="category_id-error" style="color: red; font-size: 0.875rem;">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label for="name">Nama</label>
            <input type="text" name="name" id="name" value="{{ old('name') }}" required class="@error('name') is-invalid @enderror">
            @error('name')
                <div id="name-error" style="color: red; font-size: 0.875rem;">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label for="description">Deskripsi</label>
            <textarea name="description" id="description">{{ old('description') }}</textarea>
            @error('description')
                <div id="description-error" style="color: red; font-size: 0.875rem;">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label for="price">Harga</label>
            <input type="number" name="price" id="price" min="0" step="0.01" value="{{ old('price') }}" required class="@error('price') is-invalid @enderror">
            @error('price')
                <div id="price-error" style="color: red; font-size: 0.875rem;">{{ $message }}</div>
            @enderror
        </div>
        <div>
            <label for="stock">Stok</label>
            <input type="number" name="stock" id="stock" min="0" value="{{ old('stock') }}" required class="@error('stock') is-invalid @enderror">
            @error('stock')
                <div id="stock-error" style="color: red; font-size: 0.875rem;">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit">Simpan</button>
    </form>
</body>
</html>
