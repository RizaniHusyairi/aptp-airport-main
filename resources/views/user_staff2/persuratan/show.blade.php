@extends('layouts-V2.master-layouts-v2')
@section('title', 'Detail Surat')

@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <style>
        .chip{ color: white; display:inline-block;padding:.25rem .5rem;border-radius:999px;background:#424253;font-size:.85rem;margin:.125rem .25rem}
        .timeline{position:relative;margin-left:1rem;padding-left:1.25rem}
        .timeline::before{content:'';position:absolute;left:6px;top:0;bottom:0;width:2px;background:#e9ecef}
        .timeline-item{position:relative;margin-bottom:1rem}
        .timeline-item::before{content:'';position:absolute;left:-1.2rem;top:.25rem;width:.75rem;height:.75rem;border-radius:50%;background:#0d6efd}
        .kv{display:grid;grid-template-columns:180px 1fr;gap:.5rem .75rem}
        @media (max-width:768px){.kv{grid-template-columns:1fr}}
    </style>
@endsection

@section('content')
@php
    // helper badge
    $badgeClass = match($surat->status) {
        'Disetujui' => 'bg-success',
        'Ditolak' => 'bg-danger',
        'Revisi Diperlukan' => 'bg-warning',
        'Verifikasi Tambahan' => 'bg-info',
        'Menunggu Persetujuan Atasan' => 'bg-primary',
        default => 'bg-secondary',
    };

    $isAssignee = auth()->id() === ($surat->assigned_to_user_id ?? -1);
    $isFinalApprover = auth()->id() === $surat->final_approver_id;
    $isCreator = auth()->id() === $surat->user_id;

    // cek apakah user login adalah verifikator pending berikutnya
    $pendingMine = $surat->verifications
        ->where('status','Menunggu')
        ->sortBy('order')
        ->firstWhere('user_id', auth()->id());
@endphp

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-8 order-md-1 order-last">
                <h3 class="mb-1">{{ $surat->subject }}</h3>
                <div>
                    <span class="badge {{ $badgeClass }}">{{ $surat->status }}</span>
                    @if($surat->assigned_to_user_id)
                        <span class="ms-2 text-muted">• Ditugaskan ke: <strong>{{ $surat->assignee->name ?? '-' }}</strong></span>
                    @endif
                </div>
            </div>
            <div class="col-12 col-md-4 order-md-2 order-first">
                <x-breadcrumb2 :items="[
                    ['label'=>'Dashboard','url'=>route('root')],
                    ['label'=>'Persuratan','url'=>route('persuratan.staffIndex')],
                    ['label'=>'Detail','active'=>true]
                ]" />
            </div>
        </div>
    </div>
</div>

<section class="section">
    {{-- Bar aksi kontekstual --}}
    <div class="card mb-3">
        <div class="card-body d-flex flex-wrap gap-2 justify-content-between align-items-center">
            <div class="text-muted small">
                Terakhir diperbarui: {{ optional($surat->updated_at)->translatedFormat('d M Y H:i') }}
            </div>

            <div class="d-flex flex-wrap gap-2">
                {{-- Aksi verifikator (approve/reject/request revision) --}}
                @if($isAssignee && $surat->status === 'Verifikasi Tambahan' && $pendingMine)
                    <form action="{{ route('persuratan.verify.approve', $surat) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-success btn-sm"><i class="bi bi-check"></i> Setujui</button>
                    </form>

                    <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#modalTolak">
                        <i class="bi bi-x"></i> Tolak
                    </button>

                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalRevisi">
                        <i class="bi bi-arrow-counterclockwise"></i> Minta Revisi
                    </button>
                @endif

                {{-- Aksi final approver --}}
                @if($isAssignee && $surat->status === 'Menunggu Persetujuan Atasan' && $isFinalApprover)
                    {{-- Tombol ini sekarang membuka modal, bukan submit form langsung --}}
                    <button class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#modalFinalApprove">
                        <i class="bi bi-check2-circle"></i> Setujui Final & Tanda Tangan
                    </button>
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalRevisi">
                        <i class="bi bi-arrow-counterclockwise"></i> Minta Revisi
                    </button>
                @endif
                {{-- Aksi pembuat saat revisi --}}
                

            </div>
        </div>
        @if($isAssignee && $surat->status === 'Revisi Diperlukan' && $isCreator)
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Kirim Revisi</h6></div>
                <div class="card-body">
                    <form action="{{ route('persuratan.revision.submit', $surat) }}" method="POST">
                    @csrf
                    <div id="rev-link-wrapper" class="d-flex flex-column gap-2 mb-2">
                        <div class="input-group rev-link-row">
                            <span class="input-group-text"><i class="bi bi-link-45deg"></i></span>
                            <input type="url" name="attachments[]" class="form-control" placeholder="https://drive.google.com/..." required>
                            <button type="button" class="btn btn-outline-danger btn-remove-rev-link"><i class="bi bi-trash"></i></button>
                        </div>
                    </div>
                    <button type="button" id="btn-add-rev-link" class="btn btn-outline-primary btn-sm mb-3">
                        <i class="bi bi-plus"></i> Tambah baris link
                    </button>

                    <div class="d-flex justify-content-end">
                        <button class="btn btn-primary"><i class="bi bi-send"></i> Kirim Revisi</button>
                    </div>
                    </form>
                </div>
            </div>
        @endif
    </div>

    <div class="row">
        {{-- Info utama --}}
        <div class="col-lg-7">
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Informasi Surat</h6></div>
                <div class="card-body">
                    <div class="kv">
                        <div class="text-muted">Jenis Surat</div><div>{{ $surat->letter_type }}</div>
                        <div class="text-muted">Tanggal Surat</div><div>{{ $surat->letter_date?->translatedFormat('d M Y') }}</div>
                        <div class="text-muted">Pembuat</div><div>{{ $surat->user->name ?? '-' }}</div>
                        <div class="text-muted">Pejabat Final</div><div>{{ $surat->finalApprover->name ?? '-' }}</div>
                        <div class="text-muted">Tujuan Alamat</div><div>{{ $surat->recipient_address }}</div>
                        @if(!empty($surat->collaborators))
                            <div class="text-muted">Kolaborator</div>
                            <div>
                                @foreach($surat->collaborators as $uid)
                                    <span class="chip">
                                        {{ optional(\App\Models\User::find($uid))->name ?? ('User #'.$uid) }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            @if($surat->status == 'Disetujui' && $surat->signed_document_link)
            <div class="card mb-3 border-success border-2">
                <div class="card-header bg-success text-white">
                    <h6 class="mb-0 text-white"><i class="bi bi-patch-check-fill me-2"></i>Dokumen Final Bertanda Tangan</h6>
                </div>
                <div class="card-body mt-5">
                     <a href="{{ $surat->signed_document_link }}" target="_blank" rel="noopener" class="btn btn-success w-100">
                        <i class="bi bi-box-arrow-up-right me-2"></i> Buka Dokumen Final
                    </a>
                </div>
            </div>
            @endif


            {{-- Lampiran --}}
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Lampiran Dokumen</h6></div>
                <div class="card-body">
                    @if(!empty($surat->attachments))
                        <ul class="list-group">
                            @foreach($surat->attachments as $url)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div class="text-truncate" style="max-width: 75%;">
                                        <i class="bi bi-link-45deg me-2"></i>
                                        <a href="{{ $url }}" target="_blank" rel="noopener">
                                            {{ parse_url($url, PHP_URL_HOST) }}{{ Str::limit(parse_url($url, PHP_URL_PATH) ?? '', 40) }}
                                        </a>
                                    </div>
                                    <div class="ms-2">
                                        <a class="btn btn-sm btn-outline-primary" href="{{ $url }}" target="_blank" rel="noopener">Buka</a>
                                        <a class="btn btn-sm btn-outline-secondary" href="{{ $url }}" target="_blank" rel="noopener">Salin</a>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @else
                        <div class="text-muted">Tidak ada lampiran.</div>
                    @endif
                </div>
            </div>
        </div>

        {{-- Panel kanan: verifikasi & histori --}}
        <div class="col-lg-5">
            {{-- Antrian Verifikasi --}}
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Antrian Verifikasi</h6></div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table mb-0">
                            <thead>
                                <tr>
                                    <th style="width:60px">Urut</th>
                                    <th>Verifikator</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($surat->verifications->sortBy('order') as $v)
                                    @php
                                        $badge = match($v->status){
                                            'Disetujui'=>'bg-success',
                                            'Ditolak'=>'bg-danger',
                                            default=>'bg-secondary'
                                        };
                                    @endphp
                                    <tr>
                                        <td>#{{ $v->order }}</td>
                                        <td>
                                            <div>{{ $v->user->name ?? '-' }}</div>
                                            @if($v->comments)
                                                <div class="small text-muted mt-1"><i class="bi bi-chat-left-quote me-1"></i>{{ $v->comments }}</div>
                                            @endif
                                        </td>
                                        <td><span class="badge {{ $badge }}">{{ $v->status }}</span></td>
                                    </tr>
                                @empty
                                    <tr><td colspan="3" class="text-center text-muted py-3">Tidak ada verifikator.</td></tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Riwayat Revisi --}}
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Riwayat Revisi</h6></div>
                <div class="card-body">
                    @forelse($surat->revisions->sortByDesc('created_at') as $rev)
                        <div class="mb-3">
                            <div class="fw-semibold">{{ $rev->user->name ?? '-' }}</div>
                            <div class="small text-muted">
                                {{ optional($rev->created_at)->translatedFormat('d M Y H:i') }} • Status sebelumnya: {{ $rev->previous_status }}
                            </div>
                            <div class="mt-1">{{ $rev->comments }}</div>
                        </div>
                        @if(!$loop->last)<hr class="my-2">@endif
                    @empty
                        <div class="text-muted">Belum ada revisi.</div>
                    @endforelse
                </div>
            </div>

            {{-- Timeline Events (opsional) --}}
            <div class="card">
                <div class="card-header"><h6 class="mb-0">Aktivitas</h6></div>
                <div class="card-body">
                    @if(method_exists($surat,'events') && $surat->events->count())
                        <div class="timeline">
                            @foreach($surat->events->sortByDesc('created_at') as $ev)
                                @php
                                    // Logika untuk menerjemahkan event dan mengambil nama
                                    $meta = $ev->meta ?? [];
                                    $actorName = $ev->actor->name ?? 'Sistem';
                                    $message = '';

                                    switch ($ev->event_type) {
                                        case 'created':
                                            $message = "Surat dibuat dengan perihal '<strong>" . e($meta['subject'] ?? '') . "</strong>'";
                                            break;
                                        case 'assigned':
                                            $toUserName = $metaUsers[$meta['to_user_id']] ?? 'N/A';
                                            $message = "Ditugaskan kepada <strong>" . e($toUserName) . "</strong>";
                                            break;
                                        case 'verification_requested':
                                            $message = "Proses verifikasi dimulai dengan " . e($meta['queue_size'] ?? 0) . " pejabat.";
                                            break;
                                        case 'verified':
                                            $byUserName = $metaUsers[$meta['by']] ?? $actorName;
                                            $message = "Diverifikasi oleh <strong>" . e($byUserName) . "</strong> (Urutan #" . e($meta['order'] ?? '?') . ")";
                                            break;
                                        case 'rejected':
                                            $message = "Ditolak dengan alasan: \"" . e($meta['comments'] ?? '') . "\"";
                                            break;
                                        case 'revision_requested':
                                            $message = "Revisi diminta dengan catatan: \"" . e($meta['comments'] ?? '') . "\"";
                                            break;
                                        case 'revision_submitted':
                                            $message = "Revisi telah dikirim kembali.";
                                            break;
                                        case 'final_approved':
                                            $message = "Surat disetujui secara final.";
                                            break;
                                        default:
                                            $message = "Melakukan aksi: " . e(ucfirst(str_replace('_',' ', $ev->event_type)));
                                    }
                                @endphp
                                <div class="timeline-item">
                                    <div class="fw-semibold">{!! $message !!}</div>
                                    <div class="small text-muted">
                                        {{ optional($ev->created_at)->translatedFormat('d M Y H:i') }}
                                        • oleh {{ e($actorName) }}
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-muted">Belum ada aktivitas.</div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</section>

{{-- ================================================ --}}
{{-- ===         MODAL BARU DITAMBAHKAN           === --}}
{{-- ================================================ --}}
<!-- Modal Final Approve -->
<div class="modal fade" id="modalFinalApprove" tabindex="-1" aria-labelledby="modalFinalApproveLabel" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" action="{{ route('persuratan.final.approve', $surat) }}">
        @csrf
        <div class="modal-header">
            <h5 class="modal-title" id="modalFinalApproveLabel">Persetujuan Final</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
            <p>Anda akan menyetujui surat ini secara final. Silakan unggah tautan ke dokumen yang telah ditandatangani.</p>
            <div class="mb-3">
                <label for="signed_document_link" class="form-label">Link Dokumen Bertanda Tangan <span class="text-danger">*</span></label>
                <input type="url" class="form-control" name="signed_document_link" id="signed_document_link" placeholder="https://drive.google.com/..." required>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn btn-light-secondary" data-bs-dismiss="modal">Batal</button>
            <button type="submit" class="btn btn-success">Setujui dan Simpan</button>
        </div>
    </form>
  </div>
</div>



{{-- Modal Tolak --}}
<div class="modal fade" id="modalTolak" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" action="{{ route('persuratan.verify.reject', $surat) }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Tolak Surat</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" ></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Alasan Penolakan</label>
          <textarea name="comments" class="form-control" rows="4" required></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Batal</button>
        <button class="btn btn-danger" type="submit">Tolak</button>
      </div>
    </form>
  </div>
</div>

{{-- Modal Minta Revisi --}}
<div class="modal fade" id="modalRevisi" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" action="{{ route('persuratan.revision.request', $surat) }}">
      @csrf
      <div class="modal-header">
        <h5 class="modal-title">Minta Revisi</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" ></button>
      </div>
      <div class="modal-body">
        <div class="mb-3">
          <label class="form-label">Catatan Revisi</label>
          <textarea name="comments" class="form-control" rows="4" required></textarea>
        </div>
      </div>
      <div class="modal-footer">
        <button class="btn btn-outline-secondary" type="button" data-bs-dismiss="modal">Batal</button>
        <button class="btn btn-warning" type="submit">Kirim Permintaan Revisi</button>
      </div>
    </form>
  </div>
</div>
@endsection

@section('scripts_admin')
    <script src="{{ asset('assetsv2/extensions/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net/js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ asset('assetsv2/extensions/datatables.net-bs5/js/dataTables.bootstrap5.min.js') }}"></script>
@endsection
