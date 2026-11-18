<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Petani - SawitEdu</title>
    <script src="https://cdn.jsdelivr.net/npm/@tailwindcss/browser@4"></script>
</head>
<body class="bg-gray-50 p-6">
    <div class="max-w-xl mx-auto bg-white p-6 rounded-lg shadow">
        <h1 class="text-2xl font-bold text-gray-800 mb-6">Edit Data Petani</h1>

        @if ($errors->any())
            <div class="mb-4 p-4 bg-red-100 border border-red-400 text-red-700 rounded-lg">
                <ul class="list-disc pl-5">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('petani.update', $petani->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Nama Petani</label>
                <input type="text" name="nama" value="{{ old('nama', $petani->nama) }}" class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">No HP / WhatsApp</label>
                <input type="text" name="no_hp" value="{{ old('no_hp', $petani->no_hp) }}" class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-green-500" required>
            </div>

            <div class="mb-4">
                <label class="block text-gray-700 font-medium mb-2">Alamat</label>
                <textarea name="alamat" rows="3" class="w-full border border-gray-300 rounded-lg p-2.5 focus:outline-none focus:ring-2 focus:ring-green-500" required>{{ old('alamat', $petani->alamat) }}</textarea>
            </div>

            <div class="flex justify-between items-center mt-6">
                <a href="{{ route('petani.index') }}" class="text-gray-600 hover:underline">Batal</a>
                <button type="submit" class="bg-green-600 text-white px-5 py-2.5 rounded-lg hover:bg-green-700 font-medium">Perbarui</button>
            </div>
        </form>
    </div>
</body>
</html>