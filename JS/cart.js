function addTocart(id, action) {
    showSpinner();
    console.log("Sending data:", {

        id,
        action
    }); // Log per debug

    const url = window.location.pathname.includes('index.php') ? './' : '../';

    fetch((url + "BACKEND/cart.php"), {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify({
            id,
            action
        })
    })
        .then(response => response.json())
        .then(data => {
            console.log("Response:", data); // Log per debug
            if (data.success) {
                hideSpinner();
                if (action === 'add') {
                    sessionStorage.setItem('alertMessage', 'Product added to cart');
                    sessionStorage.setItem('alertColor', 'success');
                    location.reload();
                } else if (action === 'delete' || action === 'decrease') {
                    sessionStorage.setItem('alertMessage', 'Product removed from cart');
                    sessionStorage.setItem('alertColor', 'success');
                    location.reload();

                } else if (action === 'checkout') {
                    sessionStorage.setItem('alertMessage', 'Checked out successfully');
                    sessionStorage.setItem('alertColor', 'success');
                    window.location.href = url + 'FRONTEND/orders.php';
                } else {
                    alertB("Error: " + data.message, "danger");
                }



            } else {
                hideSpinner();
                alertB("Error: " + data.message, "danger");
            }
        })
        .catch(error => {
            hideSpinner();
            console.error('Fetch error:', error);
            alertB("Errore nella risposta del server: " + error.message);
        });
}