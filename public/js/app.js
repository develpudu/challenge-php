const API_URL = 'http://localhost:8080/api/productos';

const form = document.getElementById('product-form');
const formTitle = document.getElementById('form-title');
const submitBtn = document.getElementById('submit-btn');
const cancelBtn = document.getElementById('cancel-btn');
const productIdInput = document.getElementById('product-id');
const nombreInput = document.getElementById('nombre');
const descripcionInput = document.getElementById('descripcion');
const precioInput = document.getElementById('precio');
const productsBody = document.getElementById('products-body');
const productsTable = document.getElementById('products-table');
const emptyMessage = document.getElementById('empty-message');
const loading = document.getElementById('loading');
const messageDiv = document.getElementById('message');

let products = [];

function showMessage(text, type) {
    messageDiv.textContent = text;
    messageDiv.className = `message ${type}`;
    setTimeout(() => {
        messageDiv.classList.add('hidden');
    }, 3000);
}

function showLoading() {
    loading.classList.remove('hidden');
    productsTable.classList.add('hidden');
    emptyMessage.classList.add('hidden');
}

function hideLoading() {
    loading.classList.add('hidden');
}

async function fetchProducts() {
    showLoading();
    try {
        const response = await fetch(API_URL);
        if (!response.ok) {
            throw new Error(`HTTP error! status: ${response.status}`);
        }
        const data = await response.json();
        products = data.productos || [];
        renderProducts();
    } catch (error) {
        console.error('Error fetching products:', error);
        showMessage('Error al cargar los productos', 'error');
        hideLoading();
        emptyMessage.classList.remove('hidden');
    }
}

function renderProducts() {
    hideLoading();
    productsBody.innerHTML = '';

    if (products.length === 0) {
        productsTable.classList.add('hidden');
        emptyMessage.classList.remove('hidden');
        return;
    }

    productsTable.classList.remove('hidden');
    emptyMessage.classList.add('hidden');

    products.forEach(product => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${product.id}</td>
            <td>${escapeHtml(product.nombre)}</td>
            <td>${escapeHtml(product.descripcion)}</td>
            <td class="price-ars">$${Number(product.precio).toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
            <td class="price-usd">US$ ${Number(product.precio_usd).toLocaleString('es-AR', { minimumFractionDigits: 2, maximumFractionDigits: 2 })}</td>
            <td class="actions">
                <button class="btn-edit" onclick="editProduct(${product.id})">Editar</button>
                <button class="btn-delete" onclick="deleteProduct(${product.id})">Eliminar</button>
            </td>
        `;
        productsBody.appendChild(row);
    });
}

function escapeHtml(text) {
    const div = document.createElement('div');
    div.textContent = text;
    return div.innerHTML;
}

function resetForm() {
    form.reset();
    productIdInput.value = '';
    formTitle.textContent = 'Nuevo Producto';
    submitBtn.textContent = 'Guardar';
    cancelBtn.classList.add('hidden');
}

async function handleSubmit(e) {
    e.preventDefault();

    const id = productIdInput.value;
    const payload = {
        nombre: nombreInput.value.trim(),
        descripcion: descripcionInput.value.trim(),
        precio: parseFloat(precioInput.value)
    };

    if (!payload.nombre || !payload.descripcion || isNaN(payload.precio)) {
        showMessage('Por favor complete todos los campos', 'error');
        return;
    }

    try {
        const url = id ? `${API_URL}/${id}` : API_URL;
        const method = id ? 'PUT' : 'POST';

        const response = await fetch(url, {
            method: method,
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify(payload)
        });

        if (!response.ok) {
            const errorData = await response.json().catch(() => ({}));
            throw new Error(errorData.error || `HTTP error! status: ${response.status}`);
        }

        const result = await response.json().catch(() => null);

        if (id) {
            showMessage('Producto actualizado exitosamente', 'success');
        } else {
            showMessage('Producto creado exitosamente', 'success');
        }

        resetForm();
        fetchProducts();
    } catch (error) {
        console.error('Error saving product:', error);
        showMessage(error.message || 'Error al guardar el producto', 'error');
    }
}

async function editProduct(id) {
    const product = products.find(p => p.id === id);
    if (!product) return;

    productIdInput.value = product.id;
    nombreInput.value = product.nombre;
    descripcionInput.value = product.descripcion;
    precioInput.value = product.precio;

    formTitle.textContent = 'Editar Producto';
    submitBtn.textContent = 'Actualizar';
    cancelBtn.classList.remove('hidden');

    nombreInput.focus();
}

async function deleteProduct(id) {
    if (!confirm('¿Está seguro de eliminar este producto?')) {
        return;
    }

    try {
        const response = await fetch(`${API_URL}/${id}`, {
            method: 'DELETE'
        });

        if (!response.ok && response.status !== 204) {
            const errorData = await response.json().catch(() => ({}));
            throw new Error(errorData.error || `HTTP error! status: ${response.status}`);
        }

        showMessage('Producto eliminado exitosamente', 'success');

        if (productIdInput.value == id) {
            resetForm();
        }

        fetchProducts();
    } catch (error) {
        console.error('Error deleting product:', error);
        showMessage(error.message || 'Error al eliminar el producto', 'error');
    }
}

form.addEventListener('submit', handleSubmit);
cancelBtn.addEventListener('click', resetForm);

fetchProducts();
