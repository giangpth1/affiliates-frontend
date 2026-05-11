function togglePassword(btn) {
    const input = btn.previousElementSibling;
    const icon = btn.querySelector('i');
    if (input.type === 'password') {
        input.type = 'text';
        icon.classList.replace('bi-eye', 'bi-eye-slash');
    } else {
        input.type = 'password';
        icon.classList.replace('bi-eye-slash', 'bi-eye');
    }
}

async function pasteFromClipboard() {
    try {
        const text = await navigator.clipboard.readText();
        if (text.includes('shopee') || text.includes('shp.ee')) {
            document.querySelector('input[name="url"]').value = text;
        }
    } catch (err) {
        console.error('Failed to read clipboard');
    }
}

function copyUrl(url) {
    navigator.clipboard.writeText(url).then(() => {
        alert('Đã copy link!');
    });
}
