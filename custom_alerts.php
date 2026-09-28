<style>
/* Toast Container */
#toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 999999;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

/* Individual Toast */
.custom-toast {
    background-color: #ffffff;
    color: #333333;
    padding: 15px 20px;
    border-radius: 4px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    font-family: 'Roboto', sans-serif;
    display: flex;
    align-items: center;
    gap: 15px;
    min-width: 300px;
    max-width: 400px;
    position: relative;
    overflow: hidden;
    transform: translateX(120%);
    transition: transform 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}

/* Icons */
.toast-icon {
    width: 24px;
    height: 24px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    flex-shrink: 0;
}
.toast-icon.success { background-color: #22c55e; }
.toast-icon.error { background-color: #ef4444; }
.toast-icon.warning { background-color: #eab308; }
.toast-icon.info { background-color: #3b82f6; }

/* Message */
.toast-msg {
    flex-grow: 1;
    font-size: 15px;
    font-weight: 500;
}

/* Close Button */
.toast-close {
    color: #999;
    cursor: pointer;
    font-size: 18px;
    font-weight: bold;
    border: none;
    background: none;
    padding: 0;
}
.toast-close:hover {
    color: #333;
}

/* Progress Bar at Bottom */
.toast-progress {
    position: absolute;
    bottom: 0;
    left: 0;
    height: 4px;
    width: 100%;
}
.toast-progress.success { background-color: #22c55e; }
.toast-progress.error { background-color: #ef4444; }
.toast-progress.warning { background-color: #eab308; }
.toast-progress.info { background-color: #3b82f6; }

/* Progress Animation */
@keyframes shrinkProgress {
    from { width: 100%; }
    to { width: 0%; }
}
.toast-progress-bar {
    animation: shrinkProgress 3.5s linear forwards;
}

/* Modals */
#custom-confirm-overlay {
    position: fixed;
    top: 0; left: 0; width: 100%; height: 100%;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(5px);
    z-index: 999999;
    display: flex;
    justify-content: center;
    align-items: center;
    opacity: 0;
    transition: opacity 0.3s ease;
}

#custom-confirm-modal {
    background: var(--card-bg, #fff);
    color: var(--text-color, #333);
    border: 1px solid var(--border-color, #e5e7eb);
    padding: 40px;
    border-radius: 20px;
    box-shadow: 0 20px 40px rgba(0,0,0,0.2);
    text-align: center;
    min-width: 380px;
    max-width: 90%;
    transform: translateY(-20px);
    transition: transform 0.3s cubic-bezier(0.175, 0.885, 0.32, 1.275);
}
</style>

<script>
// ==========================================
// DYNAMIC TOAST & MODAL SYSTEM 
// ==========================================

window._nativeAlert = window.alert;

// Determine Type by parsing the message text
function getToastType(msg) {
    let lower = msg.toLowerCase();
    if (lower.includes('success') || lower.includes('updated') || lower.includes('added') || lower.includes('registered')) {
        return 'success';
    } else if (lower.includes('error') || lower.includes('fail') || lower.includes('wrong') || lower.includes('invalid') || lower.includes('please')) {
        return 'error';
    } else if (lower.includes('delete') || lower.includes('remove') || lower.includes('cancel')) {
        return 'warning';
    }
    return 'info';
}

function getIconSvg(type) {
    if (type === 'success') return '<svg viewBox="0 0 20 20" fill="currentColor" style="width:16px;height:16px"><path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path></svg>';
    if (type === 'error') return '<svg viewBox="0 0 20 20" fill="currentColor" style="width:16px;height:16px"><path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd"></path></svg>';
    if (type === 'warning') return '<svg viewBox="0 0 20 20" fill="currentColor" style="width:16px;height:16px"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>';
    return '<svg viewBox="0 0 20 20" fill="currentColor" style="width:16px;height:16px"><path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"></path></svg>';
}

function showToast(message) {
    let container = document.getElementById('toast-container');
    if (!container) {
        container = document.createElement('div');
        container.id = 'toast-container';
        document.body.appendChild(container);
    }

    let type = getToastType(message);
    let toast = document.createElement('div');
    toast.className = 'custom-toast';
    
    toast.innerHTML = `
        <div class="toast-icon ${type}">
            ${getIconSvg(type)}
        </div>
        <div class="toast-msg">${message}</div>
        <button class="toast-close" onclick="this.parentElement.remove()">&times;</button>
        <div class="toast-progress ${type} toast-progress-bar"></div>
    `;

    container.appendChild(toast);

    setTimeout(() => {
        toast.style.transform = 'translateX(0)';
    }, 10);

    setTimeout(() => {
        toast.style.transform = 'translateX(120%)';
        setTimeout(() => toast.remove(), 400);
    }, 3500);
}

// Override native alert
window.alert = function(message) {
    sessionStorage.setItem('pendingToast', message);
    showToast(message);
    setTimeout(() => {
        sessionStorage.removeItem('pendingToast');
    }, 1000);
};

document.addEventListener('DOMContentLoaded', () => {
    let pendingMsg = sessionStorage.getItem('pendingToast');
    if (pendingMsg) {
        showToast(pendingMsg);
        sessionStorage.removeItem('pendingToast');
    }
});


// 2. Custom Modal System
function showCustomConfirm(message, callback) {
    let overlay = document.createElement('div');
    overlay.id = 'custom-confirm-overlay';

    let modal = document.createElement('div');
    modal.id = 'custom-confirm-modal';

    modal.innerHTML = `
        <div style="margin-bottom: 25px;">
            <div style="width: 64px; height: 64px; background: rgba(239, 68, 68, 0.1); color: #ef4444; border-radius: 50%; display: flex; align-items: center; justify-content: center; margin: 0 auto 20px auto;">
                <svg style="width:32px;height:32px;fill:currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                </svg>
            </div>
            <h3 style="font-size: 1.5rem; margin: 0; font-family: 'Roboto', sans-serif; font-weight: 700; color: var(--text-color, #111827);">Confirmation Required</h3>
            <p style="margin: 12px 0 0 0; color: #6b7280; font-size: 1.05rem; line-height: 1.5;">${message}</p>
        </div>
        <div style="display: flex; gap: 15px; justify-content: center;">
            <button id="modal-cancel" style="padding: 12px 24px; border-radius: 8px; border: 1px solid #d1d5db; background: transparent; color: #374151; cursor: pointer; font-weight: 600; flex: 1; font-size: 1rem; transition: background 0.2s; white-space: nowrap;" onmouseover="this.style.background='#f3f4f6'" onmouseout="this.style.background='transparent'">Cancel</button>
            <button id="modal-confirm" style="padding: 12px 24px; border-radius: 8px; border: none; background: #ef4444; color: white; cursor: pointer; font-weight: 600; flex: 1; font-size: 1rem; box-shadow: 0 4px 6px -1px rgba(239, 68, 68, 0.5); transition: background 0.2s; white-space: nowrap;" onmouseover="this.style.background='#dc2626'" onmouseout="this.style.background='#ef4444'">Yes, Confirm</button>
        </div>
    `;

    overlay.appendChild(modal);
    document.body.appendChild(overlay);

    setTimeout(() => {
        overlay.style.opacity = '1';
        modal.style.transform = 'translateY(0)';
    }, 10);

    function closeModal(result) {
        overlay.style.opacity = '0';
        modal.style.transform = 'translateY(-20px)';
        setTimeout(() => overlay.remove(), 300);
        callback(result);
    }

    document.getElementById('modal-cancel').onclick = () => closeModal(false);
    document.getElementById('modal-confirm').onclick = () => closeModal(true);
}

document.addEventListener('click', function(e) {
    let target = e.target.closest('[onclick*="confirm("]');
    if (target) {
        e.preventDefault();
        e.stopPropagation();

        let onclickAttr = target.getAttribute('onclick');
        let match = onclickAttr.match(/confirm\(['"](.*?)['"]\)/);
        let msg = match ? match[1] : "Are you sure you want to proceed?";

        showCustomConfirm(msg, function(confirmed) {
            if (confirmed) {
                if (target.tagName === 'A' && target.href) {
                    window.location.href = target.href;
                } else if (target.tagName === 'BUTTON' || target.tagName === 'INPUT') {
                    let form = target.closest('form');
                    if (form) {
                        if (target.name) {
                            let hidden = document.createElement('input');
                            hidden.type = 'hidden';
                            hidden.name = target.name;
                            hidden.value = target.value || '1';
                            form.appendChild(hidden);
                        }
                        form.submit();
                    } else {
                        let locMatch = onclickAttr.match(/window\.location(?:\.href)?\s*=\s*['"]([^'"]+)['"]/);
                        if (locMatch) {
                            window.location.href = locMatch[1];
                        }
                    }
                }
            }
        });
    }
}, true);
</script>
