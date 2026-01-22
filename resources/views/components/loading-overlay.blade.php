{{-- Loading Overlay Component --}}
@props([
    'id' => 'loadingOverlay',
    'message' => 'Sedang memproses...',
    'submessage' => 'Mohon tunggu sebentar'
])

<div class="loading-overlay" id="{{ $id }}">
    <div class="loading-content">
        <div class="spinner"></div>
        <h3>{{ $message }}</h3>
        <p>{{ $submessage }}</p>
    </div>
</div>

<style>
.loading-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.8);
    z-index: 9999;
    justify-content: center;
    align-items: center;
}

.loading-overlay.show {
    display: flex;
}

.loading-content {
    text-align: center;
    color: #fff;
}

.spinner {
    border: 8px solid #f3f3f3;
    border-top: 8px solid #667eea;
    border-radius: 50%;
    width: 80px;
    height: 80px;
    animation: spin 1s linear infinite;
    margin: 0 auto 20px auto;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
</style>

<script>
function showLoading(id = '{{ $id }}') {
    document.getElementById(id)?.classList.add('show');
}

function hideLoading(id = '{{ $id }}') {
    document.getElementById(id)?.classList.remove('show');
}
</script>