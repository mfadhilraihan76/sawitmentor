<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Petani - SawitEdu</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-50 p-6">
    <div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow">
        
        <!-- Header & Tombol Tambah -->
        <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
            <h1 class="text-2xl font-bold text-gray-800">Manajemen Petani</h1>
            <a href="{{ route('petani.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 font-medium">
                + Tambah Petani
            </a>
        </div>

        <!-- Flash Message (Sukses / Error) -->
        @if(session('success'))
            <div class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded-lg">
                {{ session('success') }}
            </div>
        @endif
        @if(session('error'))
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                {{ session('error') }}
            </div>
        @endif

        <!-- Form Pencarian -->
        <form method="GET" action="{{ route('petani.index') }}" class="mb-6 flex gap-2">
            <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari nama petani..." class="w-full md:w-1/3 border border-gray-300 rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-green-500">
            <button type="submit" class="bg-gray-800 text-white px-4 py-2 rounded-lg hover:bg-gray-700">Cari</button>
            @if(request('q'))
                <a href="{{ route('petani.index') }}" class="bg-gray-200 text-gray-700 px-4 py-2 rounded-lg hover:bg-gray-300 flex items-center">Reset</a>
            @endif
        </form>

        <!-- Tabel Data Petani -->
        <div class="border border-gray-200 rounded-lg overflow-hidden">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="bg-gray-100 border-b text-gray-700 text-sm">
                        <th class="p-3">No</th>
                        <th class="p-3">Nama Petani</th>
                        <th class="p-3">No HP</th>
                        <th class="p-3">Alamat</th>
                        <th class="p-3 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 text-sm">
                    @forelse($petani as $index => $p)
                        <tr class="hover:bg-gray-50">
                            <td class="p-3 text-gray-500">
                                {{ $petani->firstItem() + $index }}
                            </td>
                            <td class="p-3 font-medium text-gray-900">{{ $p->nama }}</td>
                            <td class="p-3 text-gray-600">{{ $p->no_hp }}</td>
                            <td class="p-3 text-gray-600">{{ $p->alamat }}</td>
                            <td class="p-3 text-center space-x-2">
                                <a href="{{ route('petani.edit', $p->id) }}" class="text-blue-600 hover:underline font-medium">Edit</a>
                                <form action="{{ route('petani.destroy', $p->id) }}" method="POST" class="inline" onsubmit="return confirm('Yakin ingin menghapus data petani ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:underline font-medium">Hapus</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="p-6 text-center text-gray-500">Belum ada data petani yang tersedia.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <!-- Pagination Links -->
        <div class="mt-4">
            {{ $petani->links() }}
        </div>

        <div class="mt-6">
            <a href="{{ route('dashboard') }}" class="text-green-600 hover:underline font-medium">&larr; Kembali ke Dashboard</a>
        </div>
    </div>
</body>
</html>