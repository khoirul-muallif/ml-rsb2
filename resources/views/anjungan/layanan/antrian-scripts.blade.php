{{-- 
    resources/views/anjungan/partials/antrian-scripts.blade.php
    
    Partial ini dipakai bersama oleh loket.blade.php, cs.blade.php, apotek.blade.php
    
    Cara pakai di view:
        @include('anjungan.partials.antrian-scripts', [
            'defaultTitle' => 'Loket Pendaftaran'
        ])
--}}

<script src="{{ asset('js/partials/utils.js') }}"></script>
<script>
const CSRF_TOKEN = '{{ csrf_token() }}';

function ambilAntrian(type) {
    showLoading();

    fetch('/anjungan/api/ambil', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': CSRF_TOKEN,
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({ type })
    })
    .then(res => res.json())
    .then(data => {
        hideLoading();

        if (data.status) {
            // Format tanggal dd/mm/yyyy hh:mm
            const now    = new Date();
            const dd     = String(now.getDate()).padStart(2, '0');
            const mm     = String(now.getMonth() + 1).padStart(2, '0');
            const yyyy   = now.getFullYear();
            const hh     = String(now.getHours()).padStart(2, '0');
            const mi     = String(now.getMinutes()).padStart(2, '0');

            // ✅ Cetak via triggerPrint — SATU-SATUNYA tempat print dipanggil
            window.triggerPrint({
                number : data.display,
                title  : data.label || '{{ $defaultTitle ?? "Antrian" }}',
                date   : `${dd}/${mm}/${yyyy} ${hh}:${mi}`
            });

            // Update UI nomor berikutnya langsung (tanpa tunggu loadSummary)
            const nextEl = document.getElementById('next-' + type);
            if (nextEl) nextEl.textContent = parseInt(data.nomor) + 1;

            // Refresh summary setelah 1 detik
            setTimeout(loadSummary, 1000);

        } else {
            alert('Gagal: ' + data.message);
        }
    })
    .catch(err => {
        hideLoading();
        console.error('ambilAntrian error:', err);
        alert('Terjadi kesalahan sistem');
    });
}

function loadSummary() {
    fetch('/anjungan/api/summary')
        .then(res => res.json())
        .then(data => {
            if (!data.status) return;

            Object.keys(data.summary).forEach(type => {
                const stats     = data.summary[type];
                const totalEl   = document.getElementById('total-'    + type);
                const menungguEl= document.getElementById('menunggu-' + type);
                const nextEl    = document.getElementById('next-'     + type);

                if (totalEl)     totalEl.textContent    = stats.total;
                if (menungguEl)  menungguEl.textContent  = stats.menunggu;
                if (nextEl)      nextEl.textContent      = parseInt(stats.last_number || 0) + 1;
            });
        })
        .catch(err => console.error('loadSummary error:', err));
}

document.addEventListener('DOMContentLoaded', loadSummary);
setInterval(loadSummary, 10000);
</script>