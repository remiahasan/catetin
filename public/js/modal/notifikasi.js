
// 🔔 Fungsi popup universal
function showPopup(message, type = 'info') {
    const popup = document.createElement('div');
    const icons = {
        success: '✅',
        error: '❌',
        warning: '⚠️',
        info: 'ℹ️'
    };

    const colors = {
        success: '#16a34a', // hijau
        error: '#dc2626',   // merah
        warning: '#f59e0b', // kuning
        info: '#4f46e5'     // ungu (default)
    };

    popup.innerHTML = `
            <div style="display: flex; align-items: center; gap: 8px;">
                <span>${icons[type] || icons.info}</span>
                <span>${message}</span>
            </div>
        `;

    popup.style.position = 'fixed';
    popup.style.bottom = '80px';
    popup.style.left = '50%';
    popup.style.transform = 'translateX(-50%)';
    popup.style.background = colors[type] || colors.info;
    popup.style.color = '#fff';
    popup.style.padding = '12px 24px';
    popup.style.borderRadius = '8px';
    popup.style.boxShadow = '0 2px 8px rgba(0,0,0,0.15)';
    popup.style.zIndex = '9999';
    popup.style.opacity = '0';
    popup.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
    popup.style.transform = 'translate(-50%, 20px)';
    document.body.appendChild(popup);

    // animasi muncul
    setTimeout(() => {
        popup.style.opacity = '1';
        popup.style.transform = 'translate(-50%, 0)';
    }, 100);

    // animasi hilang
    setTimeout(() => {
        popup.style.opacity = '0';
        popup.style.transform = 'translate(-50%, 20px)';
        setTimeout(() => popup.remove(), 500);
    }, 2500);
}
