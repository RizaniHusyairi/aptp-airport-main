@extends('layouts-V2.master-layouts-v2')
@section('title', 'Detail Surat')

@section('styles_admin')
    <link rel="stylesheet" href="{{ asset('assetsv2/extensions/datatables.net-bs5/css/dataTables.bootstrap5.min.css') }}">
    <style>
        .chip{display:inline-block;padding:.25rem .5rem;border-radius:999px;background:#424253;font-size:.85rem;margin:.125rem .25rem}
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
    $badgeClass = match($letter->status) {
        'Disetujui' => 'bg-success',
        'Ditolak' => 'bg-danger',
        'Revisi Diperlukan' => 'bg-warning',
        'Verifikasi Tambahan' => 'bg-info',
        'Menunggu Persetujuan Atasan' => 'bg-primary',
        default => 'bg-secondary',
    };

    $isAssignee = auth()->id() === ($letter->assigned_to_user_id ?? -1);
    $isFinalApprover = auth()->id() === $letter->final_approver_id;
    $isCreator = auth()->id() === $letter->user_id;

    // cek apakah user login adalah verifikator pending berikutnya
    $pendingMine = $letter->verifications
        ->where('status','Menunggu')
        ->sortBy('order')
        ->firstWhere('user_id', auth()->id());
@endphp

<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-8 order-md-1 order-last">
                <h3 class="mb-1">{{ $letter->subject }}</h3>
                <div>
                    <span class="badge {{ $badgeClass }}">{{ $letter->status }}</span>
                    @if($letter->assigned_to_user_id)
                        <span class="ms-2 text-muted">• Ditugaskan ke: <strong>{{ $letter->assignee->name ?? '-' }}</strong></span>
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
                Terakhir diperbarui: {{ optional($letter->updated_at)->translatedFormat('d M Y H:i') }}
            </div>

            <div class="d-flex flex-wrap gap-2">
                {{-- Aksi verifikator (approve/reject/request revision) --}}
                @if($isAssignee && $letter->status === 'Verifikasi Tambahan' && $pendingMine)
                    <form action="{{ route('persuratan.verify.approve', $letter) }}" method="POST" class="d-inline">
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
                @if($isAssignee && $letter->status === 'Menunggu Persetujuan Atasan' && $isFinalApprover)
                    <form action="{{ route('persuratan.final.approve', $letter) }}" method="POST" class="d-inline">
                        @csrf
                        <button class="btn btn-success btn-sm"><i class="bi bi-check2-circle"></i> Setujui Final</button>
                    </form>
                    <button class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#modalRevisi">
                        <i class="bi bi-arrow-counterclockwise"></i> Minta Revisi
                    </button>
                @endif

                {{-- Aksi pembuat saat revisi --}}
                @if($isAssignee && $letter->status === 'Revisi Diperlukan' && $isCreator)
                    <form action="{{ route('persuratan.revision.submit', $letter) }}" method="POST" class="d-inline" enctype="multipart/form-data">
                        @csrf
                        {{-- jika mau minta upload revisi baru, tambah input file di sini --}}
                        
                        <button class="btn btn-primary btn-sm"><i class="bi bi-send"></i> Kirim Revisi</button>
                    </form>
                @endif
            </div>
        </div>
    </div>

    <div class="row">
        {{-- Info utama --}}
        <div class="col-lg-7">
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Informasi Surat</h6></div>
                <div class="card-body">
                    <div class="kv">
                        <div class="text-muted">Jenis Surat</div><div>{{ $letter->letter_type }}</div>
                        <div class="text-muted">Tanggal Surat</div><div>{{ $letter->letter_date?->translatedFormat('d M Y') }}</div>
                        <div class="text-muted">Pembuat</div><div>{{ $letter->user->name ?? '-' }}</div>
                        <div class="text-muted">Pejabat Final</div><div>{{ $letter->finalApprover->name ?? '-' }}</div>
                        <div class="text-muted">Tujuan Alamat</div><div>{{ $letter->recipient_address }}</div>
                        @if(!empty($letter->collaborators))
                            <div class="text-muted">Kolaborator</div>
                            <div>
                                @foreach($letter->collaborators as $uid)
                                    <span class="chip">
                                        {{ optional(\App\Models\User::find($uid))->name ?? ('User #'.$uid) }}
                                    </span>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Lampiran --}}
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Lampiran Dokumen</h6></div>
                <div class="card-body">
                    @if(!empty($letter->attachments))
                        <ul class="list-group">
                            @foreach($letter->attachments as $i => $path)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <span><i class="bi bi-file-earmark-pdf me-2"></i> Lampiran {{ $i+1 }}</span>
                                    <span>
                                        <a class="btn btn-sm btn-outline-primary" href="{{ Storage::disk('public')->url($path) }}" target="_blank">Lihat</a>
                                        <a class="btn btn-sm btn-outline-secondary" href="{{ Storage::disk('public')->url($path) }}" download>Unduh</a>
                                    </span>
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
                                @forelse($letter->verifications->sortBy('order') as $v)
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
                    @forelse($letter->revisions->sortByDesc('created_at') as $rev)
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
                    @if(method_exists($letter,'events') && $letter->events->count())
                        <div class="timeline">
                            @foreach($letter->events->sortByDesc('created_at') as $ev)
                                <div class="timeline-item">
                                    <div class="fw-semibold">{{ ucfirst(str_replace('_',' ', $ev->event_type)) }}</div>
                                    <div class="small text-muted">
                                        {{ optional($ev->created_at)->translatedFormat('d M Y H:i') }}
                                        @if($ev->actor_user_id)
                                            • oleh {{ optional(optional($ev->actor)->name ?? null, fn($n)=>$n) ?? 'User #'.$ev->actor_user_id }}
                                        @endif
                                    </div>
                                    @if(!empty($ev->meta))
                                        <div class="mt-1 small text-muted">
                                            {{-- tampilkan meta ringkas --}}
                                            @foreach($ev->meta as $k=>$v)
                                                <span class="chip">{{ $k }}: {{ is_array($v)? json_encode($v) : $v }}</span>
                                            @endforeach
                                        </div>
                                    @endif
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

{{-- Modal Tolak --}}
<div class="modal fade" id="modalTolak" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog">
    <form class="modal-content" method="POST" action="{{ route('persuratan.verify.reject', $letter) }}">
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
    <form class="modal-content" method="POST" action="{{ route('persuratan.revision.request', $letter) }}">
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
