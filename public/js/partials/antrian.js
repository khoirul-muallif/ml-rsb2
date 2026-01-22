/**
 * Panggil antrian
 */
function panggil(noantrian, loket = '1', audioName = 'a') {
    console.log('🔔 Panggil:', { noantrian, loket });
    
    playAntrianSequence(noantrian, loket, audioName);
    
    $.ajax({
        url: '/anjungan/api/setpanggil',
        type: 'POST',
        dataType: 'json',
        data: {
            noantrian: noantrian,
            type: getCurrentType(),
            loket: loket,
        },
        success: function(data) {
            console.log('✅ Panggil berhasil');
        },
        error: function(xhr) {
            console.error('❌ Panggil gagal');
            showToast('Gagal memanggil antrian', 'error');
        }
    });
}

/**
 * Get current type dari URL
 */
function getCurrentType() {
    var params = new URLSearchParams(window.location.search);
    var show = params.get('show') || '';
    return show.replace('panggil_', '');
}

/**
 * Form lompat antrian validation
 */
document.addEventListener('DOMContentLoaded', function() {
    var formLompat = document.getElementById('formLompat');
    if(formLompat) {
        formLompat.addEventListener('submit', function(e) {
            var input = this.querySelector('input[name="antrian"]');
            if(!confirm('Yakin panggil nomor ' + input.value + '?')) {
                e.preventDefault();
            }
        });
    }
});