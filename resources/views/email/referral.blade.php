<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>eKurva.com Referral</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h1 {
            font-size: 20px;
            color: #1565c0;
            margin: 0;
        }

        .button {
            display: inline-block;
            background-color: #2b7fff;
            color: white !important;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            margin: 20px 0;
            font-weight: bold;
        }

        .button:hover {
            background-color: #1a5bb8;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>Undangan Bergabung eKurva.com</h1>
        </div>

        <p>Hai,</p>

        <p>Saya <b>{{ $senderName }}</b> telah menggunakan <b>eKurva.com</b>, sebuah platform inovatif yang
            mendigitalisasi kurva
            tumbuh kembang anak Indonesia. Saya juga yakin Bapak / Ibu sekalian juga akan mendapat manfaat dari
            mendaftar di platform ini, seperti yang telah kami dapatkan.</p>

        <p>eKurva.com merupakan website pertama yang menyediakan fitur <b>digitalisasi kurva pertumbuhan</b>,
            memungkinkan
            pencatatan dan pemantauan parameter tinggi badan, berat badan, lingkar kepala, serta indeks massa tubuh
            (BMI) secara lebih praktis dan akurat. Selain itu platform ini telah dilengkapin dengan <b>Intepretasi</b>
            Status
            Gizi, Perkiraan Tinggi Genetik dan beragam parameter lainnya yang bisa diakses khusus oleh Tenaga Kesehatan.
        </p>

        <p>Sebagai bentuk dukungan terhadap digitalisasi kesehatan anak, eKurva.com dapat diakses <b>secara gratis</b>
            dengan
            <b>1.000 poin awal</b> yang berlaku selama <b>1 tahun utk Awam / 4 bulan untuk Tenaga Kesehatan.</b> Selain
            itu,
            setiap
            pendaftaran melalui referal ini akan mendapatkan <b>tambahan 100 poin dan perpanjangan akses 1 bulan secara
                gratis.</b>
        </p>

        <p>Saya percaya bahwa eKurva.com dapat menjadi solusi yang bermanfaat bagi tenaga medis, orang tua, dan semua
            pihak yang peduli terhadap pertumbuhan anak.</p>

        <a href="{{ $registrationUrl }}" class="button">Daftar Sekarang</a>

        <p>Salam,<br>
            <b>{{ $senderName }}</b>
        </p>
    </div>
</body>

</html>
