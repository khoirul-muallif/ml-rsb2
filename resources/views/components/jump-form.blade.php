@props([
    'panggil_loket' => '',
    'max_antrian' => 0,
    'current_loket' => 1
])

<div class="jump-form-container">
    <div class="jump-form-card">
        <h6 class="jump-form-title">
            <i class="fa fa-arrow-right"></i> Lompat ke Nomor Tertentu
        </h6>
        
        <form action="{{ route('anjungan.pemanggil') }}" method="GET" id="formLompat" class="jump-form">
            <input type="hidden" name="show" value="{{ $panggil_loket }}">
            <input type="hidden" name="loket" value="{{ $current_loket }}">
            <input type="hidden" name="skip_audio" value="1">
            
            <div class="jump-form-group">
                <input type="number" 
                       class="jump-form-input" 
                       name="antrian" 
                       id="jumpFormInput"
                       placeholder="No. Antrian"
                       min="1"
                       @if($max_antrian > 0) max="{{ $max_antrian }}" @endif
                       required>
                <button type="submit" class="jump-form-btn">
                    <i class="fa fa-check"></i> Panggil
                </button>
            </div>
            
            @if($max_antrian > 0)
                <small class="jump-form-info">
                    Total hari ini: <strong>{{ $max_antrian }}</strong>
                </small>
            @else
                <small class="jump-form-warning">
                    <i class="fas fa-exclamation-triangle"></i> Belum ada antrian
                </small>
            @endif
        </form>
    </div>
</div>

<style>
.jump-form-container {
    max-width: 500px;
    margin: 30px auto;
    padding: 0 20px;
}

.jump-form-card {
    background: white;
    border-radius: 15px;
    padding: 25px;
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.jump-form-title {
    color: #666;
    font-weight: 600;
    margin: 0 0 15px 0;
    font-size: 16px;
}

.jump-form {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

.jump-form-group {
    display: flex;
    gap: 10px;
}

.jump-form-input {
    flex: 1;
    padding: 12px 15px;
    font-size: 1rem;
    border: 1px solid #ddd;
    border-radius: 8px;
    font-family: inherit;
}

.jump-form-input:focus {
    outline: none;
    border-color: #dc3545;
    box-shadow: 0 0 0 3px rgba(220, 53, 69, 0.1);
}

.jump-form-btn {
    background: #dc3545;
    color: #fff;
    border: none;
    padding: 12px 20px;
    border-radius: 8px;
    cursor: pointer;
    font-weight: 600;
    transition: all 0.3s ease;
    display: flex;
    align-items: center;
    justify-content: center;
    gap: 8px;
}

.jump-form-btn:hover {
    background: #c82333;
    box-shadow: 0 5px 15px rgba(0,0,0,0.2);
}

.jump-form-btn:active {
    transform: scale(0.98);
}

.jump-form-info,
.jump-form-warning {
    display: block;
    margin-top: 10px;
    text-align: center;
    font-size: 13px;
}

.jump-form-info {
    color: #666;
}

.jump-form-warning {
    color: #dc3545;
}

@media (max-width: 768px) {
    .jump-form-container {
        margin: 20px auto;
    }
    
    .jump-form-card {
        padding: 20px;
    }
    
    .jump-form-group {
        flex-direction: column;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const formLompat = document.getElementById('formLompat');
    const jumpFormInput = document.getElementById('jumpFormInput');
    
    if(formLompat) {
        formLompat.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const nilai = parseInt(jumpFormInput.value);
            const max = parseInt(jumpFormInput.getAttribute('max') || '999');
            const min = parseInt(jumpFormInput.getAttribute('min') || '1');
            
            // Validasi input
            if(nilai < min) {
                alert(`Nomor antrian minimal ${min}`);
                return false;
            }
            
            if(max > 0 && nilai > max) {
                alert(`Nomor antrian maksimal ${max}`);
                return false;
            }
            
            // Confirm sebelum submit
            if(confirm(`Yakin panggil nomor ${nilai}?`)) {
                this.submit();
            }
        });
    }
});
</script>