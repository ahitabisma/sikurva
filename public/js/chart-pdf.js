// Shared functionality - Save chart images
async function saveChartImages() {
    let foundCharts = false;

    for (let i = 1; i <= 12; i++) {
        const canvas = document.getElementById(`chart-table-${i}`);

        if (!canvas) {
            console.warn(`Canvas with ID "chart-table-${i}" not found. Skipping...`);
            continue; // Skip loop iteration kalau canvas nggak ada
        }

        foundCharts = true;
        const dataUrl = canvas.toDataURL('image/png');
        await fetch('/save-chart', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': csrfToken
            },
            body: JSON.stringify({
                image: dataUrl,
                filename: `chart-${patientId}-table${i}.png`
            })
        });
    }

    return foundCharts;
}

// Generate and download PDF
async function generatePDF() {
    try {
        loadingOverlay.classList.remove('hidden');

        // Step 1: Save all chart images
        const chartsExist = await saveChartImages();

        if (!chartsExist) {
            throw new Error('Belum ada grafik yang tersedia untuk di-export');
        }

        // Step 2: Request PDF generation
        const response = await fetch(`/generate-chart-pdf/${patientId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });

        if (!response.ok) throw new Error('Gagal generate PDF');

        // Step 3: Download the PDF
        const blob = await response.blob();
        const url = URL.createObjectURL(blob);

        // Get filename from response headers
        const contentDisposition = response.headers.get('Content-Disposition');
        const fileNameMatch = contentDisposition && contentDisposition.match(/filename="(.+)"/);
        const fileName = fileNameMatch ? fileNameMatch[1] : 'grafik-kurva.pdf';

        // Create download link
        const link = document.createElement('a');
        link.href = url;
        link.download = fileName;
        document.body.appendChild(link);
        link.click();

        // Clean up
        document.body.removeChild(link);
        URL.revokeObjectURL(url);

        showNotification('PDF berhasil dibuat dan diunduh!', false);
    } catch (error) {
        console.error('Terjadi kesalahan saat generate PDF:', error);
        showNotification(error.message || 'Gagal generate PDF. Silakan coba lagi.', true);
    } finally {
        loadingOverlay.classList.add('hidden');
    }
}

// Generate and send PDF via email
async function generateAndSendPDF() {
    try {
        loadingOverlay.classList.remove('hidden');

        // Step 1: Save all chart images
        const chartsExist = await saveChartImages();

        if (!chartsExist) {
            throw new Error('Belum ada grafik yang tersedia untuk dikirim via email');
        }

        // Step 2: Request PDF generation and email sending
        const response = await fetch(`/generate-and-send-pdf/${patientId}`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken
            }
        });

        if (!response.ok) throw new Error('Gagal generate dan kirim email PDF');

        // Ambil isi JSON dari response
        const data = await response.json();

        // Tampilkan pesan dari controller
        showNotification(data.message, false);
    } catch (error) {
        console.error('Terjadi kesalahan saat kirim email:', error);
        showNotification(error.message || 'Gagal mengirim email. Silakan coba lagi.', true);
    } finally {
        loadingOverlay.classList.add('hidden');
    }
}
