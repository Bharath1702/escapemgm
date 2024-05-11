let cart = [];

function addToCart(gameId) {
    const game = document.getElementById(gameId);
    const gameName = game.querySelector('h2').innerText;
    const gameAmount = game.querySelector('p:nth-child(3)').innerText;
    cart.push({ name: gameName, amount: gameAmount });
    updateCart();
}

function updateCart() {
    const cartItemsDiv = document.getElementById('cart-items');
    cartItemsDiv.innerHTML = '';
    cart.forEach(item => {
        const cartItem = document.createElement('div');
        cartItem.classList.add('cart-item');
        cartItem.innerText = `${item.name} - ${item.amount}`;
        cartItemsDiv.appendChild(cartItem);
    });
}

function checkout() {
    if (cart.length > 0) {
        // Redirect to checkout.php
        window.location.href = "checkout.php";
    } else {
        alert("Your cart is empty!");
    }
}
