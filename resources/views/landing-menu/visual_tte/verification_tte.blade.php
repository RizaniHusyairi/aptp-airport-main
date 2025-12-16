<!DOCTYPE html>
<html>
<head><title>Verifikasi Dokumen</title></head>
<body style="font-family: sans-serif; text-align:center; padding: 50px;">
    <h1 style="color:green;">✔ DOKUMEN VALID</h1>
    <p>Dokumen ini telah terdaftar di sistem Bandara APT Pranoto.</p>
    <hr>
    <p><strong>Perihal:</strong> {{ $surat->subject }}</p>
    <p><strong>Penandatangan:</strong> {{ $surat->finalApprover->name }}</p>
    <p><strong>Status:</strong> SAH (DISETUJUI)</p>
</body>
</html>
