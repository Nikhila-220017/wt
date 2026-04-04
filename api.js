// ============================================================
//  api.js  —  Add this to your HTML page:
//  <script src="api.js"></script>
//
//  Connects your Kumari's Store HTML frontend to the PHP backend.
//  Backend must be running at http://localhost:8080
// ============================================================

const API_BASE = 'http://localhost:8080/backendforassign/api';

// ── Token helpers ────────────────────────────────────────────
function saveToken(token) { localStorage.setItem('kumari_token', token); }
function getToken()       { return localStorage.getItem('kumari_token'); }
function clearToken()     { localStorage.removeItem('kumari_token'); }
function isLoggedIn()     { return !!getToken(); }
function getUser()        { 
    const u = localStorage.getItem('kumari_user');
    return u ? JSON.parse(u) : null;
}

function authHeaders() {
    return {
        'Content-Type': 'application/json',
        'Authorization': 'Bearer ' + getToken()
    };
}

// ── Auth ─────────────────────────────────────────────────────

async function register(name, email, password) {
    const res  = await fetch(API_BASE + '/auth/register', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ name, email, password })
    });
    const data = await res.json();
    if (res.ok) {
        saveToken(data.token);
        localStorage.setItem('kumari_user', JSON.stringify(data.user));
        alert(data.message);
    } else {
        alert('Error: ' + data.error);
    }
    return data;
}

async function login(email, password) {
    const res  = await fetch(API_BASE + '/auth/login', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ email, password })
    });
    const data = await res.json();
    if (res.ok) {
        saveToken(data.token);
        localStorage.setItem('kumari_user', JSON.stringify(data.user));
        alert(data.message);
        window.location.reload();
    } else {
        alert('Error: ' + data.error);
    }
    return data;
}

function logout() {
    clearToken();
    localStorage.removeItem('kumari_user');
    alert('Logged out successfully!');
    window.location.reload();
}

// ── Products ─────────────────────────────────────────────────

async function getAllProducts(filters = {}) {
    const params = new URLSearchParams(filters).toString();
    const res    = await fetch(API_BASE + '/products?' + params);
    return res.json();
}

async function getProduct(id) {
    const res = await fetch(API_BASE + '/products/' + id);
    return res.json();
}

async function searchProducts(query) {
    return getAllProducts({ q: query });
}

async function getProductsByCategory(category) {
    return getAllProducts({ category: category });
}

async function getCategories() {
    const res = await fetch(API_BASE + '/products/categories');
    return res.json();
}

// ── Cart ─────────────────────────────────────────────────────

async function getCart() {
    if (!isLoggedIn()) { alert('Please log in to view your cart.'); return; }
    const res = await fetch(API_BASE + '/cart', { headers: authHeaders() });
    return res.json();
}

async function addToCart(productId, quantity = 1) {
    if (!isLoggedIn()) { alert('Please log in to add items to your cart.'); return; }
    const res  = await fetch(API_BASE + '/cart/add', {
        method: 'POST',
        headers: authHeaders(),
        body: JSON.stringify({ productId, quantity })
    });
    const data = await res.json();
    if (res.ok) alert(data.message);
    else        alert('Error: ' + data.error);
    return data;
}

async function removeFromCart(productId) {
    if (!isLoggedIn()) return;
    const res  = await fetch(API_BASE + '/cart/' + productId, {
        method: 'DELETE',
        headers: authHeaders()
    });
    const data = await res.json();
    if (res.ok) alert(data.message);
    else        alert('Error: ' + data.error);
    return data;
}

async function clearCart() {
    if (!isLoggedIn()) return;
    const res = await fetch(API_BASE + '/cart', {
        method: 'DELETE',
        headers: authHeaders()
    });
    return res.json();
}

// ── Orders ───────────────────────────────────────────────────

async function placeOrder(address = '', paymentMethod = 'Cash on Delivery') {
    if (!isLoggedIn()) { alert('Please log in to place an order.'); return; }
    const res  = await fetch(API_BASE + '/orders/place', {
        method: 'POST',
        headers: authHeaders(),
        body: JSON.stringify({ address, paymentMethod })
    });
    const data = await res.json();
    if (res.ok) alert(data.message);
    else        alert('Error: ' + data.error);
    return data;
}

async function getMyOrders() {
    if (!isLoggedIn()) { alert('Please log in to view your orders.'); return; }
    const res = await fetch(API_BASE + '/orders/my-orders', { headers: authHeaders() });
    return res.json();
}

// ── Usage examples ───────────────────────────────────────────
//
//  Register:
//    register('Nikhila', 'nikhila@email.com', 'mypassword')
//
//  Login:
//    login('nikhila@email.com', 'mypassword')
//
//  Add to cart button in HTML:
//    <button onclick="addToCart('PRODUCT_ID_HERE')">Add to Cart</button>
//
//  Place order button:
//    <button onclick="placeOrder('My Address, Guntur')">Place Order</button>
//
//  Load products into your page:
//    getAllProducts().then(data => {
//        data.products.forEach(p => {
//            console.log(p.name, p.price);
//        });
//    });
//
//  Search products:
//    searchProducts('blue').then(data => console.log(data.products));
//
//  Show logged in user name:
//    const user = getUser();
//    if (user) document.getElementById('username').textContent = user.name;
