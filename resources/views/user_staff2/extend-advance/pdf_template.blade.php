<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pernyataan</title>
    
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Times+New+Roman&display=swap');

        @page {
            
          padding: 0;
          margin: 25px 70px;

        }
body {
    font-family: 'Times New Roman', Times, serif;
    background-color: white;
    display: flex;
    justify-content: center;
    margin: 0;
    padding: 0;
    
    
}

.container {
transform-origin:top ;
 width: 100%; 
 font-size: 13px;  
}
header {
    margin-bottom: 10px;
}

h1, h2, h3 {
    text-align: center;
    margin: 0;
    padding: 0;
    font-weight: bold;
}

h1 {
    font-size: 14pt;
}

h2 {
    font-size: 13pt;
}

h3 {
    font-size: 13pt;
}

.nomor-surat {
    display: inline-block;
    text-align: left;
    margin-top: 5px;
    margin-left: 150px;
}

.nomor-surat span {
    font-weight: bold;
}

.line {
    width: 80%;
    height: 2px;
    background-color: black;
    margin-top: 2px;
    margin: auto;
}

main {
    margin-top: 10px;
}

.form-section table {
    width: 100%;
    border-collapse: collapse;
}

.form-section td {
    padding: 2px 0;
    vertical-align: top;
}

.section-number {
    width: 25px;
}

.section-title {
    padding-left: 5px;
}

.label {
    
    width: 280px;
}


.separator {
    width: 10px;
    text-align: center;
}


.statement-content {
        margin-left: 25px;
        font-size: 13px;
}

.w-0{
    width: 0;
}



.signature-section {
    
   
    text-align: center;
}

.applicant {
    width: 300px;
}

.pilot-title {
    margin-top: 5px;
}

.approval-section {
    width: 100%; 
    text-align: center; 
    margin-top: 10px;
    border-collapse: collapse;
}
.approval-section tr td {
    border: 1px solid #000;
}

.approval-box {
    width: 45%;
}

.approval-title {
    font-weight: bold;
    height: 40px; 
    margin-top: 5px;
}

.signature-space {
    height: 70px;
}

.signature-name {
    font-weight: bold;
    margin-bottom: 5px;
}


.pernyataan {
    font-style: italic;
    font-weight: bold;
    text-align: justify;
}

.underline {
    text-decoration: underline;
}

.underline-2 {
    border-bottom: 2px solid
}

.label-t{
    margin-left: 28px;
}
.p-5{
    padding-left: 15px;
}
.table-title{
    font-size: 13px
}


    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>SURAT PERNYATAAN</h1>
            <h2>PERMOHONAN SLOT CLEARANCE - EXTEND ADVANCE HOUR AIRPORT</h2>
            <h3>DI BANDAR UDARA AJI PANGERAN TUMENGGUNG PRANOTO SAMARINDA</h3>
            <div class="nomor-surat">
                <span>NOMOR:</span>
            </div>
            <div class="line"></div>
        </header>

        <main>
            <p style="margin: 0 0 8px 0; font-size:13px;">Yang bertanda tangan di bawah ini:</p>

            <section class="form-section">
                <table class="table-title">
                    <tr>
                        <td class="section-number">I.</td>
                        <td class="section-title" colspan="2"><b class="underline-2">Pesawat Udara</b><br><i>Aircraft</i></td>
                    </tr>
                </table>
                <table class="statement-content">
                    <tr>
                        
                        <td class="w-0">
                            <div class="label">
                                a. <span class="underline p-5">Operator (Pemilik/Penyewa)</span>

                            </div>
                            
                            <i class="label-t">Operator (Owner/Charterer)</i>
                        </td>
                        <td class="separator">:</td>
                        <td class="value">{{ $submission->operator }}</td>
                    </tr>
                    <tr>
                        <td class="w-0">
                            <div class="label">
                                   b. <span class="underline p-5">Tipe</span>
                            </div>
                            <i class="label-t">Type</i>

                        </td>
                        
                        <td class="separator">:</td>
                        <td class="value">{{ $submission->aircraft_type }}</td>
                    </tr>
                    <tr>
                        <td class="w-0">
                            <div class="label">
                                   c. <span class="underline p-5">Tanda Pendaftaran / No Penerbangan</span>
                            </div>
                            <i class="label-t">Registration and Flight Number</i>

                        </td>
                        
                        <td class="separator">:</td>
                        <td class="value">{{ $submission->registration_and_flight_number }}</td>
                    </tr>
                </table>
                <table class="table-title">
                        <tr>
                            <td class="section-number">II.</td>
                            <td class="section-title" colspan="2"><b class="underline-2">Penerbangan</b><br><i>Flight</i></td>
                        </tr>
                        
                </table>
                <table class="statement-content">
                    <tr>
                        <td class="w-0">
                            <div class="label">
                                   a. <span class="underline p-5">Tanggal</span>
                            </div>
                            <i class="label-t">Date</i>

                        </td>
                        <td class="separator">:</td>
                        <td class="value">{{ $submission->flight_date->translatedFormat('d M Y') }}</td>
                    </tr>
                    <tr>
                        <td class="w-0">
                            <div class="label">
                                   b. <span class="underline p-5">Jam Keberangkatan</span>
                            </div>
                            <i class="label-t">EOBT</i>

                        </td>
                        <td class="separator">:</td>
                        <td class="value">{{ \Carbon\Carbon::parse($submission->eobt)->format('H:i') }} UTC</td>
                    </tr>
                    <tr>
                        <td class="w-0">
                            <div class="label">
                                   c. <span class="underline p-5">Jam Kedatangan</span>
                            </div>
                            <i class="label-t">AOBT</i>

                        </td>
                        <td class="separator">:</td>
                        <td class="value">{{ \Carbon\Carbon::parse($submission->aobt)->format('H:i') }} UTC</td>
                    </tr>
                    <tr>
                        <td class="w-0">
                            <div class="label">
                                   d. <span class="underline p-5">Rute</span>
                            </div>
                            <i class="label-t">Route</i>

                        </td>
                        <td class="separator">:</td>
                        <td class="value">{{ $submission->route }}</td>
                    </tr>
                    <tr>
                        <td class="w-0">
                            <div class="label">
                                   e. <span class="underline p-5">Alternate Take Off</span>
                            </div>
                            <i class="label-t">Take off Alternate</i>
                        </td>
                        <td class="separator">:</td>
                        <td class="value">{{ $submission->take_off_alternate ?? '-' }}</td>
                    </tr>
                    <tr>
                        <td class="w-0">
                            <div class="label">
                                   f. <span class="underline p-5">Keperluan Terbang</span>
                            </div>
                            <i class="label-t">Purpose of Flight</i>    
                        </td>
                        <td class="separator">:</td>
                        <td class="value">{{ $submission->purpose_of_flight }}</td>
                    </tr>
                    </table>
                    <table class="table-title">

                    <tr>
                        <td class="section-number" style="vertical-align: top;">III.</td>
                        <td class="section-title" colspan="2" style="vertical-align: top;"><b class="underline-2">Pernyataan</b><br><i>Statement</i></td>
                         
                        </tr>
                    </table>
                    <p class="statement-content pernyataan">
                        {{ $submission->statement_notes }}
                        </p>
            </section>
            <table style="text-align: center; margin: auto;">
                <tr>
                    <td>

                        <div class="signature-section">
                            <div class="signature-box applicant">
                                <div>Samarinda, {{ $submission->created_at->translatedFormat('d M Y') }}</div>
                                <div><b class="underline-2">PEMOHON</b></div>
                                <div class="pilot-title"><i>Pilot in Command</i></div>
                                <div class="signature-space"></div>
                                <div class="signature-name">{{ $submission->pic_name }}</div>
                            </div>
                        </div>
                    </td>
                </tr>
            </table>
                        <hr>
                        <div style="text-align: center;">Samarinda, {{ $submission->created_at->translatedFormat('d M Y') }}</div>
                        <table class="approval-section">
                           <tr>
                               <td colspan="2" style="font-weight: 600; background-color: #9bba58;">Mengetahui</td>
                           </tr>
                           <tr>
                               <td style="width: 50%;">
                                   <div class="">
                                       <div class="approval-title">PIC - PERUM LPPNPI<br>KCP SAMARINDA</div>
                                       <div class="signature-space"></div>
                                       <div class="signature-name">(...........................................)</div>
                                   </div>
            
                               </td>
                               <td style="width: 50%;">
                                   <div class="">
                                       <div class="approval-title">PIC - BANDAR UDARA<br>APT PRANOTO SAMARINDA</div>
                                       <div class="signature-space"></div>
                                       <div class="signature-name">(...........................................)</div>
                                   </div>
            
                               </td>
                           </tr>
                        </table>
                        
                        
                        
        </main>
       
    </div>
</body>
</html>