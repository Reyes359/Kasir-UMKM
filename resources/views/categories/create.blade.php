<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kategori</title>
</head>
<body>
    <h1>Tambah Kategori</h1>

    @if ($errors->any())
        <div style="color: red; margin-bottom: 12px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('categories.store') }}">
        @csrf
        <div>
            <label>Nama</label>
            <input type="text" name="name" value="{{ old('name') }}" required>
        </div>
        <div>
            <label>Deskripsi</label>
            <textarea name="description">{{ old('description') }}</textarea>
        </div>
        <button type="submit">Simpan</button>
    </form>
</body>
</html>
