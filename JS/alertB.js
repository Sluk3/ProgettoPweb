
document.addEventListener('DOMContentLoaded', function () {
    if (sessionStorage.getItem('alertMessage') && sessionStorage.getItem('alertColor')) {
        alertB(sessionStorage.getItem('alertMessage'), sessionStorage.getItem('alertColor'));
        sessionStorage.removeItem('alertMessage');
        sessionStorage.removeItem('alertColor');
    }
});

function alertB(message, color = 'warning') {
    const alertPlaceholder = document.createElement('div');
    alertPlaceholder.className = `alert alert-${color} alert-dismissible fade show`;
    alertPlaceholder.role = 'alert';
    alertPlaceholder.style.position = 'fixed'; // Changed to fixed
    alertPlaceholder.style.top = '70px'; // Adjust this value as needed
    alertPlaceholder.style.left = '50%';
    alertPlaceholder.style.transform = 'translateX(-50%)';
    alertPlaceholder.innerHTML = `
                    ${message}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                `;
    document.body.appendChild(alertPlaceholder);

    setTimeout(() => {
        const alertInstance = bootstrap.Alert.getOrCreateInstance(alertPlaceholder);
        alertInstance.close();
    }, 5000);
}