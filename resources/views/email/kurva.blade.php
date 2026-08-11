<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Laporan Grafik Kurva</title>
    <style>
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            font-size: 14px;
            color: #333;
            background-color: #f6f6f6;
            padding: 20px;
        }

        .email-container {
            background-color: #ffffff;
            padding: 30px;
            border-radius: 8px;
            max-width: 600px;
            margin: 0 auto;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.05);
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

        .content p {
            margin: 10px 0;
            line-height: 1.5;
        }

        .button {
            display: inline-block;
            background-color: #1565c0;
            color: #fff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }

        .footer {
            margin-top: 40px;
            font-size: 12px;
            color: #888;
            text-align: center;
        }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="header">
            <h1>Hasil Grafik Kurva</h1>
        </div>
        <div class="content">
            <p>Halo,</p>

            <p>Berikut ini kami kirimkan laporan kurva pertumbuhan untuk <strong>{{ $namaPatient }}</strong>.</p>

            <p>Silahkan unduh file PDF yang terlampir.</p>

            <p>Terima kasih telah menggunakan layanan kami.</p><br>

            <p>Powered by Sikurva.com</p>
        </div>

        <iframe src="data:application/pdf;base64,{{ $fileName }}" width="100%" height="600px"></iframe>

        <div class="footer">
            © {{ date('Y') }} ekurva.com. Semua Hak Dilindungi.
        </div>
    </div>
</body>

</html>
