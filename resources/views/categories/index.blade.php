<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Kategori</title>
</head>
<body>
    <h1>Data Kategori</h1>
    <a href="{{ route('categories.create') }}">Tambah Kategori</a>
    <br><br>
    <table border="1" cellpadding="6" cellspacing="0">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Deskripsi</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($categories as $category)
                <tr>
                    <td>{{ $category->name }}</td>
                    <td>{{ $category->description }}</td>
                    <td>
                        <a href="{{ route('categories.edit', $category) }}">Edit</a>
                        <form action="{{ route('categories.destroy', $category) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
