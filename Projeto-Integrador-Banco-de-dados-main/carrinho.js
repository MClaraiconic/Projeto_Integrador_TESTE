// Pega elementos
const cartItemsList = document.getElementById("cart-items");
const cartTotal = document.getElementById("cart-total");
const checkoutBtn = document.getElementById("checkout");

let cart = [];

// Função para adicionar item
function addToCart(name, price) {
  const item = cart.find(i => i.name === name);
  
  if (item) {
    item.quantity++;
  } else {
    cart.push({ name, price, quantity: 1 });
  }

  renderCart();
}

// Função para remover item
function removeFromCart(name) {
  cart = cart.filter(i => i.name !== name);
  renderCart();
}

// Renderizar carrinho
function renderCart() {
  cartItemsList.innerHTML = "";
  let total = 0;

  cart.forEach(item => {
    total += item.price * item.quantity;

    const li = document.createElement("li");
    li.textContent = `${item.name} x${item.quantity} - R$ ${(item.price * item.quantity).toFixed(2)}`;

    const removeBtn = document.createElement("button");
    removeBtn.textContent = "❌";
    removeBtn.onclick = () => removeFromCart(item.name);
    li.appendChild(removeBtn);

    cartItemsList.appendChild(li);
  });

  cartTotal.textContent = total.toFixed(2);
}

// Eventos dos botões
document.querySelectorAll(".add-to-cart").forEach(button => {
  button.addEventListener("click", () => {
    const name = button.getAttribute("data-name");
    const price = parseFloat(button.getAttribute("data-price"));
    addToCart(name, price);
  });
});

// Finalizar pedido (simples, redireciona pro WhatsApp)
checkoutBtn.addEventListener("click", () => {
  if (cart.length === 0) {
    alert("Seu carrinho está vazio!");
    return;
  }

  let mensagem = "Olá, gostaria de fazer um pedido:\n";
  cart.forEach(item => {
    mensagem += `${item.name} x${item.quantity} - R$ ${(item.price * item.quantity).toFixed(2)}\n`;
  });
  mensagem += `\nTotal: R$ ${cartTotal.textContent}`;

  const url = `https://wa.me/5532984431132?text=${encodeURIComponent(mensagem)}`;
  window.open(url, "_blank");
});
