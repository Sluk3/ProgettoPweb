function ajaxRequest() {
    var request = false;
    try { request = new XMLHttpRequest() } catch (e1) {
        try { request = new ActiveXObject("Msxml2.XMLHTTP") } catch (e2) {
            try {
                request = new ActiveXObject("Microsoft.XMLHTTP")
            } catch (e3) { request = false }
        }
    }
    return request
}
function totalPrice() {
    let items = document.querySelectorAll('.itemprice');
    let cart = document.querySelector('#cartcontent');

    if (items.length < 1) {
        const url = window.location.pathname.includes('index.php') ? './' : '../';
        fetch(url + 'COMMON/emptycart.php')
            .then(response => response.text())
            .then(data => {
                cart.outerHTML = data;
                console.log("Cart empty");
            })
            .catch(error => console.error('Error fetching empty cart content:', error));
    } else {
        let quantity = document.querySelectorAll('.quantity');
        sum = 0;
        i = 0;
        items.forEach(element => {
            let price = element.textContent.substring(1);
            price *= quantity[i].value;
            sum += parseFloat(price);
            i++;
        });
        document.querySelector('#totprice').innerHTML = "Total price: €" + sum.toFixed(2);
    }
}

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
            let item = document.querySelector('#cartitem' + id);
            if (data.success) {
                hideSpinner();
                if (action === 'add') {
                    let cart = document.querySelector('#cartcontent');
                    cart.outerHTML = data.content;

                    alertB(data.message, "success");

                } else if (action === 'increase') {
                    let quantityInput = item.querySelector('#quantity');
                    quantityInput.value = parseInt(quantityInput.value) + 1;
                    if (quantityInput.value > 1 && item.querySelector('#decrease').disabled) {
                        item.querySelector('#decrease').disabled = false;
                    }
                    totalPrice();
                    alertB(data.message, "success");
                }
                else if (action === 'delete') {
                    item.remove();
                    alertB(data.message, "success");
                    totalPrice();
                } else if (action === 'decrease') {
                    let quantityInput = item.querySelector('#quantity');
                    quantityInput.value = parseInt(quantityInput.value) - 1;
                    if (quantityInput.value == 1) {
                        item.querySelector('#decrease').setAttribute('disabled', 'true');
                    }
                    totalPrice();
                    alertB(data.message, "success");
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