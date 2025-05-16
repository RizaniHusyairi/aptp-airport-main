<form action="{{ route('pengaduan.store') }}" method="POST" class="space-y-4">
    @csrf
    <h3 class="text-xl font-bold">Hubungi Kami</h3>
    
    <div>
        <label class="block text-gray-700">Nama</label>
        <input type="text" name="name" class="w-full px-4 py-2 border rounded">
    </div>
    
    <div>
        <label class="block text-gray-700">Email</label>
        <input type="email" name="email" class="w-full px-4 py-2 border rounded">
    </div>
    
    <div>
        <label class="block text-gray-700">Pesan</label>
        <textarea name="message" class="w-full px-4 py-2 border rounded"></textarea>
    </div>
    
    <!-- reCAPTCHA (sesuaikan dengan key Anda) -->
    <div class="g-recaptcha" data-sitekey="{{ config('services.recaptcha.site_key') }}"></div>
    
    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded hover:bg-blue-600">
        Kirim
    </button>
</form>