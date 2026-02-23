/**
 * Show toast notification
 */
function showToast(message, type = 'info') {
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerHTML = `
        <i class="fas fa-${getToastIcon(type)}"></i>
        <span style="margin-left: 10px;">${message}</span>
    `;
    document.body.appendChild(toast);
    
    setTimeout(() => {
        toast.style.animation = 'slideIn 0.3s ease reverse';
        setTimeout(() => toast.remove(), 300);
    }, 3000);
}

/**
 * Get toast icon
 */
function getToastIcon(type) {
    const icons = {
        success: 'check-circle',
        error: 'exclamation-circle',
        warning: 'exclamation-triangle',
        info: 'info-circle'
    };
    return icons[type] || 'info-circle';
}

/**
 * Format number
 */
function formatNumber(num) {
    return num.toString().padStart(3, '0');
}

/**
 * Format time
 */
function formatTime(time) {
    return time ? time.substring(0, 5) : '-';
}

/**
 * Load stats with AJAX
 */
function loadStats(type, callback) {
    $.ajax({
        url: '/anjungan/api/getstats?type=' + type,
        type: 'GET',
        dataType: 'json',
        success: function(data) {
            if(data.status && data.stats) {
                callback(data.stats);
            }
        },
        error: function(xhr) {
            console.error('Failed to load stats');
        }
    });
}

/**
 * Setup CSRF token
 */
$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
});
