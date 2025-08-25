// Variables globales
let quotes = JSON.parse(localStorage.getItem('greenValleyQuotes')) || [];
let houseModels = JSON.parse(localStorage.getItem('greenValleyModels')) || [
    {
        id: 'eco-basic',
        name: 'Eco Basic',
        price: 45000,
        description: 'Casa prefabricada básica y ecológica, perfecta para parejas o familias pequeñas.',
        stock: 15
    },
    {
        id: 'eco-comfort',
        name: 'Eco Comfort',
        price: 65000,
        description: 'Casa con mayor comodidad y espacios optimizados para familias medianas.',
        stock: 12
    },
    {
        id: 'eco-premium',
        name: 'Eco Premium',
        price: 85000,
        description: 'Casa premium con acabados de alta calidad y tecnología sustentable.',
        stock: 8
    },
    {
        id: 'family-standard',
        name: 'Family Standard',
        price: 75000,
        description: 'Casa familiar estándar con amplios espacios y diseño funcional.',
        stock: 10
    },
    {
        id: 'family-deluxe',
        name: 'Family Deluxe',
        price: 95000,
        description: 'Casa familiar de lujo con múltiples habitaciones y áreas de entretenimiento.',
        stock: 6
    },
    {
        id: 'luxury-villa',
        name: 'Luxury Villa',
        price: 120000,
        description: 'Villa de lujo con diseño exclusivo y las mejores terminaciones.',
        stock: 4
    }
];

// Inicialización
document.addEventListener('DOMContentLoaded', function() {
    initializeApp();
    setupEventListeners();
    updateDashboard();
    loadInventory();
    loadQuotesHistory();
});

// Configurar event listeners
function setupEventListeners() {
    // Navegación
    document.querySelectorAll('.nav-link').forEach(link => {
        link.addEventListener('click', function(e) {
            e.preventDefault();
            const section = this.getAttribute('data-section');
            showSection(section);
            
            // Actualizar navegación activa
            document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
            this.classList.add('active');
        });
    });

    // Formulario de cotización
    const quoteForm = document.getElementById('quote-form');
    quoteForm.addEventListener('submit', handleQuoteSubmit);

    // Cambios en el modelo de casa
    const houseModel = document.getElementById('house-model');
    houseModel.addEventListener('change', updateQuotePrice);

    // Cambios en extras
    document.querySelectorAll('.extra-item input[type="checkbox"]').forEach(checkbox => {
        checkbox.addEventListener('change', updateQuotePrice);
    });

    // Modal de modelo
    const addModelBtn = document.getElementById('add-model-btn');
    const modelModal = document.getElementById('model-modal');
    const closeModal = document.querySelector('.close');
    const cancelModel = document.getElementById('cancel-model');
    const modelForm = document.getElementById('model-form');

    addModelBtn.addEventListener('click', () => openModelModal());
    closeModal.addEventListener('click', () => closeModelModal());
    cancelModel.addEventListener('click', () => closeModelModal());
    modelForm.addEventListener('submit', handleModelSubmit);

    // Búsqueda en inventario
    const searchInventory = document.getElementById('search-inventory');
    searchInventory.addEventListener('input', filterInventory);

    // Búsqueda en historial
    const searchHistory = document.getElementById('search-history');
    const statusFilter = document.getElementById('status-filter');
    searchHistory.addEventListener('input', filterHistory);
    statusFilter.addEventListener('change', filterHistory);

    // Cerrar modal al hacer clic fuera
    window.addEventListener('click', function(e) {
        if (e.target === modelModal) {
            closeModelModal();
        }
    });
}

// Mostrar sección
function showSection(sectionName) {
    document.querySelectorAll('.section').forEach(section => {
        section.classList.remove('active');
    });
    document.getElementById(sectionName).classList.add('active');
}

// Actualizar precio de cotización
function updateQuotePrice() {
    const houseModel = document.getElementById('house-model');
    const selectedOption = houseModel.options[houseModel.selectedIndex];
    const basePrice = selectedOption ? parseInt(selectedOption.getAttribute('data-price')) || 0 : 0;
    
    let extrasPrice = 0;
    document.querySelectorAll('.extra-item input[type="checkbox"]:checked').forEach(checkbox => {
        extrasPrice += parseInt(checkbox.value);
    });

    const totalPrice = basePrice + extrasPrice;

    document.getElementById('base-price').textContent = `$${basePrice.toLocaleString()}`;
    document.getElementById('extras-price').textContent = `$${extrasPrice.toLocaleString()}`;
    document.getElementById('total-price').textContent = `$${totalPrice.toLocaleString()}`;
}

// Manejar envío de cotización
function handleQuoteSubmit(e) {
    e.preventDefault();
    
    const formData = new FormData(e.target);
    const houseModel = document.getElementById('house-model');
    const selectedOption = houseModel.options[houseModel.selectedIndex];
    
    if (!selectedOption || !selectedOption.value) {
        alert('Por favor selecciona un modelo de casa');
        return;
    }

    const extras = [];
    document.querySelectorAll('.extra-item input[type="checkbox"]:checked').forEach(checkbox => {
        extras.push({
            name: checkbox.getAttribute('data-name'),
            price: parseInt(checkbox.value)
        });
    });

    const basePrice = parseInt(selectedOption.getAttribute('data-price'));
    const extrasPrice = extras.reduce((sum, extra) => sum + extra.price, 0);
    const totalPrice = basePrice + extrasPrice;

    const quote = {
        id: generateQuoteId(),
        clientName: document.getElementById('client-name').value,
        clientEmail: document.getElementById('client-email').value,
        clientPhone: document.getElementById('client-phone').value,
        houseModel: selectedOption.text,
        houseSize: document.getElementById('house-size').value,
        location: document.getElementById('location').value,
        extras: extras,
        basePrice: basePrice,
        extrasPrice: extrasPrice,
        totalPrice: totalPrice,
        date: new Date().toISOString(),
        status: 'pendiente'
    };

    quotes.push(quote);
    saveQuotes();
    
    // Limpiar formulario
    e.target.reset();
    updateQuotePrice();
    
    // Actualizar dashboard y historial
    updateDashboard();
    loadQuotesHistory();
    addActivity(`Nueva cotización creada para ${quote.clientName}`);
    
    alert('Cotización creada exitosamente');
    showSection('historial');
    document.querySelector('[data-section="historial"]').classList.add('active');
    document.querySelectorAll('.nav-link').forEach(l => l.classList.remove('active'));
}

// Generar ID de cotización
function generateQuoteId() {
    return 'GV-' + Date.now().toString().slice(-6);
}

// Actualizar dashboard
function updateDashboard() {
    const totalQuotes = quotes.length;
    const totalRevenue = quotes.reduce((sum, quote) => sum + quote.totalPrice, 0);
    const pendingQuotes = quotes.filter(quote => quote.status === 'pendiente').length;
    const totalHouses = houseModels.length;

    document.getElementById('total-quotes').textContent = totalQuotes;
    document.getElementById('total-revenue').textContent = `$${totalRevenue.toLocaleString()}`;
    document.getElementById('pending-quotes').textContent = pendingQuotes;
    document.getElementById('total-houses').textContent = totalHouses;
}

// Cargar inventario
function loadInventory() {
    const inventoryGrid = document.getElementById('inventory-grid');
    inventoryGrid.innerHTML = '';

    houseModels.forEach(model => {
        const stockClass = model.stock > 10 ? 'stock-high' : model.stock > 5 ? 'stock-medium' : 'stock-low';
        const stockText = model.stock > 10 ? 'Alto' : model.stock > 5 ? 'Medio' : 'Bajo';

        const modelCard = document.createElement('div');
        modelCard.className = 'inventory-item';
        modelCard.innerHTML = `
            <div class="inventory-header">
                <h3>${model.name}</h3>
                <p>$${model.price.toLocaleString()}</p>
            </div>
            <div class="inventory-body">
                <p>${model.description}</p>
            </div>
            <div class="inventory-footer">
                <span class="stock-badge ${stockClass}">Stock: ${stockText} (${model.stock})</span>
                <div>
                    <button class="btn btn-secondary" onclick="editModel('${model.id}')">
                        <i class="fas fa-edit"></i>
                    </button>
                    <button class="btn btn-danger" onclick="deleteModel('${model.id}')">
                        <i class="fas fa-trash"></i>
                    </button>
                </div>
            </div>
        `;
        inventoryGrid.appendChild(modelCard);
    });
}

// Filtrar inventario
function filterInventory() {
    const searchTerm = document.getElementById('search-inventory').value.toLowerCase();
    const inventoryItems = document.querySelectorAll('.inventory-item');
    
    inventoryItems.forEach(item => {
        const modelName = item.querySelector('h3').textContent.toLowerCase();
        const description = item.querySelector('.inventory-body p').textContent.toLowerCase();
        
        if (modelName.includes(searchTerm) || description.includes(searchTerm)) {
            item.style.display = 'block';
        } else {
            item.style.display = 'none';
        }
    });
}

// Abrir modal de modelo
function openModelModal(modelId = null) {
    const modal = document.getElementById('model-modal');
    const modalTitle = document.getElementById('modal-title');
    const form = document.getElementById('model-form');
    
    if (modelId) {
        const model = houseModels.find(m => m.id === modelId);
        modalTitle.textContent = 'Editar Modelo';
        document.getElementById('model-name').value = model.name;
        document.getElementById('model-price').value = model.price;
        document.getElementById('model-description').value = model.description;
        document.getElementById('model-stock').value = model.stock;
        form.setAttribute('data-edit-id', modelId);
    } else {
        modalTitle.textContent = 'Agregar Modelo';
        form.reset();
        form.removeAttribute('data-edit-id');
    }
    
    modal.style.display = 'block';
}

// Cerrar modal de modelo
function closeModelModal() {
    document.getElementById('model-modal').style.display = 'none';
}

// Manejar envío de modelo
function handleModelSubmit(e) {
    e.preventDefault();
    
    const editId = e.target.getAttribute('data-edit-id');
    const modelData = {
        name: document.getElementById('model-name').value,
        price: parseInt(document.getElementById('model-price').value),
        description: document.getElementById('model-description').value,
        stock: parseInt(document.getElementById('model-stock').value)
    };

    if (editId) {
        // Editar modelo existente
        const modelIndex = houseModels.findIndex(m => m.id === editId);
        houseModels[modelIndex] = { ...houseModels[modelIndex], ...modelData };
        addActivity(`Modelo ${modelData.name} actualizado`);
    } else {
        // Agregar nuevo modelo
        const newModel = {
            id: generateModelId(),
            ...modelData
        };
        houseModels.push(newModel);
        addActivity(`Nuevo modelo ${modelData.name} agregado`);
    }

    saveModels();
    updateHouseModelSelect();
    loadInventory();
    updateDashboard();
    closeModelModal();
}

// Generar ID de modelo
function generateModelId() {
    return 'model-' + Date.now().toString();
}

// Editar modelo
function editModel(modelId) {
    openModelModal(modelId);
}

// Eliminar modelo
function deleteModel(modelId) {
    if (confirm('¿Estás seguro de que quieres eliminar este modelo?')) {
        const modelIndex = houseModels.findIndex(m => m.id === modelId);
        const modelName = houseModels[modelIndex].name;
        houseModels.splice(modelIndex, 1);
        saveModels();
        updateHouseModelSelect();
        loadInventory();
        updateDashboard();
        addActivity(`Modelo ${modelName} eliminado`);
    }
}

// Actualizar select de modelos de casa
function updateHouseModelSelect() {
    const select = document.getElementById('house-model');
    const currentValue = select.value;
    
    // Limpiar opciones excepto la primera
    select.innerHTML = '<option value="">Seleccionar modelo</option>';
    
    houseModels.forEach(model => {
        const option = document.createElement('option');
        option.value = model.id;
        option.setAttribute('data-price', model.price);
        option.textContent = `${model.name} - $${model.price.toLocaleString()}`;
        select.appendChild(option);
    });
    
    // Restaurar valor si existe
    if (currentValue) {
        select.value = currentValue;
    }
}

// Cargar historial de cotizaciones
function loadQuotesHistory() {
    const tbody = document.getElementById('quotes-tbody');
    tbody.innerHTML = '';

    if (quotes.length === 0) {
        tbody.innerHTML = '<tr><td colspan="7" style="text-align: center; color: #666;">No hay cotizaciones registradas</td></tr>';
        return;
    }

    quotes.forEach(quote => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td>${quote.id}</td>
            <td>${quote.clientName}</td>
            <td>${quote.houseModel}</td>
            <td>$${quote.totalPrice.toLocaleString()}</td>
            <td>${new Date(quote.date).toLocaleDateString()}</td>
            <td><span class="status-badge status-${quote.status}">${quote.status.charAt(0).toUpperCase() + quote.status.slice(1)}</span></td>
            <td>
                <button class="btn btn-secondary" onclick="viewQuote('${quote.id}')">
                    <i class="fas fa-eye"></i>
                </button>
                <button class="btn btn-primary" onclick="updateQuoteStatus('${quote.id}')">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-danger" onclick="deleteQuote('${quote.id}')">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(row);
    });
}

// Filtrar historial
function filterHistory() {
    const searchTerm = document.getElementById('search-history').value.toLowerCase();
    const statusFilter = document.getElementById('status-filter').value;
    const rows = document.querySelectorAll('#quotes-tbody tr');

    rows.forEach(row => {
        if (row.cells.length === 1) return; // Skip "no data" row
        
        const clientName = row.cells[1].textContent.toLowerCase();
        const status = row.querySelector('.status-badge').textContent.toLowerCase();
        
        const matchesSearch = clientName.includes(searchTerm);
        const matchesStatus = !statusFilter || status.includes(statusFilter);
        
        if (matchesSearch && matchesStatus) {
            row.style.display = '';
        } else {
            row.style.display = 'none';
        }
    });
}

// Ver cotización
function viewQuote(quoteId) {
    const quote = quotes.find(q => q.id === quoteId);
    if (!quote) return;

    const extrasText = quote.extras.length > 0 
        ? quote.extras.map(extra => `${extra.name}: $${extra.price.toLocaleString()}`).join('\n')
        : 'Ninguno';

    alert(`
Cotización: ${quote.id}
Cliente: ${quote.clientName}
Email: ${quote.clientEmail}
Teléfono: ${quote.clientPhone}
Modelo: ${quote.houseModel}
Tamaño: ${quote.houseSize} m²
Ubicación: ${quote.location}
Precio Base: $${quote.basePrice.toLocaleString()}
Extras: 
${extrasText}
Total: $${quote.totalPrice.toLocaleString()}
Estado: ${quote.status.charAt(0).toUpperCase() + quote.status.slice(1)}
Fecha: ${new Date(quote.date).toLocaleDateString()}
    `);
}

// Actualizar estado de cotización
function updateQuoteStatus(quoteId) {
    const quote = quotes.find(q => q.id === quoteId);
    if (!quote) return;

    const newStatus = prompt(`Estado actual: ${quote.status}\nNuevo estado (pendiente/aprobada/rechazada):`, quote.status);
    
    if (newStatus && ['pendiente', 'aprobada', 'rechazada'].includes(newStatus.toLowerCase())) {
        quote.status = newStatus.toLowerCase();
        saveQuotes();
        loadQuotesHistory();
        updateDashboard();
        addActivity(`Estado de cotización ${quote.id} cambiado a ${newStatus}`);
    }
}

// Eliminar cotización
function deleteQuote(quoteId) {
    if (confirm('¿Estás seguro de que quieres eliminar esta cotización?')) {
        const quoteIndex = quotes.findIndex(q => q.id === quoteId);
        const quote = quotes[quoteIndex];
        quotes.splice(quoteIndex, 1);
        saveQuotes();
        loadQuotesHistory();
        updateDashboard();
        addActivity(`Cotización ${quoteId} eliminada`);
    }
}


function deleteUser(userId) {
    if (confirm("¿Seguro que deseas eliminar este usuario? Esta acción no se puede deshacer.")) {
        var formData = new FormData();
        formData.append('action', 'delete_user');
        formData.append('id_usuario', userId);

        fetch('admin_ajax.php', {
            method: 'POST',
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                alert('Usuario eliminado correctamente.');
                location.reload();
            } else {
                alert(data.error || 'Error al eliminar usuario.');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Error al procesar la solicitud.');
        });
    }
}



// Agregar actividad
function addActivity(message) {
    const activityList = document.getElementById('activity-list');
    const noActivity = activityList.querySelector('.no-activity');
    
    if (noActivity) {
        noActivity.remove();
    }

    const activityItem = document.createElement('div');
    activityItem.className = 'activity-item';
    activityItem.innerHTML = `
        <span>${message}</span>
        <small>${new Date().toLocaleString()}</small>
    `;
    
    activityList.insertBefore(activityItem, activityList.firstChild);
    
    // Mantener solo las últimas 10 actividades
    const activities = activityList.querySelectorAll('.activity-item');
    if (activities.length > 10) {
        activities[activities.length - 1].remove();
    }
}

// Funciones de almacenamiento
function saveQuotes() {
    localStorage.setItem('greenValleyQuotes', JSON.stringify(quotes));
}

function saveModels() {
    localStorage.setItem('greenValleyModels', JSON.stringify(houseModels));
}

// Inicializar aplicación
function initializeApp() {
    updateHouseModelSelect();
    updateQuotePrice();
    
    // Agregar actividad inicial si no hay datos
    if (quotes.length === 0) {
        addActivity('Sistema Green Valley iniciado');
    }
}

