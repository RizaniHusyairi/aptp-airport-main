
@extends('layouts_landing.landing_app')

@section('title', 'Laporan Keuangan - Bandara APT Pranoto')

@section('content')
<section id="chefs" class="chefs section pt-6">

      <!-- Section Title -->
      <div class="container section-title" data-aos="fade-up">
        <h2>Informasi Publik<br></h2>
        <p><span>SOP</span> <span class="description-title">PPID</span></p>
      </div><!-- End Section Title -->

      <div class="container-fluid light-background" >
        <div class="container">

            <!-- Sejarah dan Letak Geografis -->
            <section class="bg-white p-4 rounded shadow mb-5" data-aos="fade-up">
                <p class="text-center">Pada halaman ini, Anda dapat melihat SOP terkait dengan pengelolaan dan layanan Informasi Publik di PPID
                (Pejabat Pengelola Informasi dan Dokumentasi). SOP ini berfungsi sebagai panduan dalam memberikan layanan
                informasi yang transparan, tepat waktu, dan sesuai dengan ketentuan yang berlaku.</p>
            </section>


            
        </div>
        <div class="container">

        <div class="row gy-4">

          <div class="col-lg-4 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="100">
            <a href="{{ asset('assets_landing/img/sop/prosedur-pengajuan-sengketa-informasi-publik.png') }}" class="glightbox">
            <div class="team-member">
              <div class="member-img">
                <img src="{{ asset('assets/img/sop/prosedur-pengajuan-sengketa-informasi-publik.png') }}" class="img-fluid" alt="">
                <!-- <div class="social">
                    Lihat Detail
                  </div> -->
                </div>
              <div class="member-info">
                <h4>Tata Cara Prosedur Pengajuan Sengketa Informasi Publik</h4>
                
                <p>1. Pengajuan sengketa Informasi Publik ke KIP (Komite Informasi Pusat) diajukan dalam waktu 14 hari kerja setelah diterimanya tanggapan tertulis dari PPID Bandar Udara A.P.T. Pranoto - Samarinda yang tidak memuaskan permohonan Informasi Publik
                  Jika pada tahap mediasi dihasilkan kesepakatan, maka kesepakatan hasil mediasi tersebut ditetapkan oleh Putusan Komisi Informasi. <br> <br>
                2. Dalam waktu 14 hari kerja setelah di terimanya Permohonan Penyelesaian Sengketa Informasi Publik, Komisi Informasi harus melakukan proses penyelesaian sengketa melalui media, paling lambat 100 hari kerja.
Apabila upaya mediasi dinyatakan tidak berhasil secara tertulis oleh salah satu pihak atau para pihak yang 
bersengketa menarik diri dari perundingan, maka Komisi Informasi melanjutkan proses penyelesaian sengketa melalui adjudikasi. <br> <br>
                3. Apabila salah satu atau para pihak yang bersengketa secara tertulis menyatakan tidak menerima putusan adjudikasi dari Komisi Informasi paling lambat 14 hari kerja setelah diterimanya putusan tersebut, maka dapat mengajukan gugatan melalui pengadilan. 
                  Jika pemohon informasi puas atas keputusan adjudikasi Komisi Informasi sengketa selesai.</p>
                </div>
              </div>
            </a>
          </div><!-- End Chef Team Member -->

          <div class="col-lg-4 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="200">
            <a href="/assets/img/sop/prosedur-permohonan-informasi-publik.png" class="glightbox">
            <div class="team-member">
              <div class="member-img">
                <img src="assets/img/sop/prosedur-permohonan-informasi-publik.png" class="img-fluid" alt="Prosedur Permohonan Informasi Publik">
                <!-- <div class="social">
                    Lihat Detail
                </div> -->
              </div>
              <div class="member-info">
                <h4>Tata Cara Permohonan Informasi Publik</h4>
                <p > 1. Pemohon mengajukan permintaan informasi ke Bandar Udara A.P.T. Pranoto - Samarinda melalui PPID <br> 
                  2. Mengisi formulir permohonan <br>
                  3. Petugas Informasi mencatat semua yang diminta oleh pemohon informasi publik <br>
                  4. Pemohon Informasi Publik harus meminta tanda bukti kepada Petugas Informasi serta nomor pendaftaran permintaan <br>
                  5. PPID memberikan jawaban untuk memenuhi permohonan informasi atau tidak memenuhi dengan disertai alasan, dalam waktu 10 hari kerja dan dapat diperpanjang selama 7 hari kerja</p>
              </div>
            </div>
            </a>
          </div><!-- End Chef Team Member -->

          <div class="col-lg-4 d-flex align-items-stretch" data-aos="fade-up" data-aos-delay="300">
            <a href="/assets/img/sop/prosedur-permohonan-keberatan-informasi.png" class="glightbox">
            <div class="team-member">
              <div class="member-img">
                <img src="assets/img/sop/prosedur-permohonan-keberatan-informasi.png" class="img-fluid" alt="">
                <!-- <div class="social">
                    Lihat Detail
                </div> -->
              </div>
              <div class="member-info">
                <h4>Tata cara prosedur permohonan keberatan informasi</h4>
                <p>1. Keberatan diajukan kepada PPID Bandar Udara A.P.T. Pranoto - Samarinda dalam jangka waktu paling lambat 30 hari kerja setelah diketemukan alasan. <br> <br>
                  2. PPID Bandar Udara A.P.T. Pranoto Samarinda harus memberikan tanggapan atas pengajuan keberatan tersebut paling lambat 30 hari kerja sejak diterimanya keberatan secara tertulis. <br> <br>
                  3. Jika pengaju puas atas putusan PPID, maka sengketa keberatan selesai. Jika pengaju keberatan informasi tidak puas atas tanggapan PPID Bandara Udara A.P.T. Pranoto - Samarinda, maka penyelesaian sengketa informasi publik dapat diajukan kepada Komisi Informasi Pusat. <br>
                </p>
                
                
              </div>
            </div>
            </a>
          </div><!-- End Chef Team Member -->

        </div>

      </div>
    
      </div>
      

    </section><!-- /About Section -->

@endsection


@push('page-styles')

  <link href="{{ asset('assets/css/sop.css') }}" rel="stylesheet">
@endpush

@push('page-scripts')
    <script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>

  <script src="{{ asset('assets/js/sop.js') }}"></script>
@endpush
