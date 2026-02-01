import {apiGetMany} from "../services/api.js";

const dummyBoats = [
    { id: 1, name: 'Windspiel', type: 'Segelboot', icon: '⛵', length: '8.5m', capacity: 4 },
    { id: 2, name: 'Meerblick', type: 'Motorboot', icon: '🚤', length: '6.2m', capacity: 6 },
    { id: 3, name: 'Wellenreiter', type: 'Kajak', icon: '🛶', length: '3.5m', capacity: 2 },
    { id: 4, name: 'Sonnenschein', type: 'Segelboot', icon: '⛵', length: '10.0m', capacity: 6 },
];

// Berth positions - horizontal lines
const berthPositions = [
    // UPPER PIER - 16 finger docks in a horizontal line
    { id: 'A1',  name: 'A-1',  x: 20.3, y: 30, w: 1.5, h: 7, pricePerDay: 50, capacity: 2 },
    { id: 'A2',  name: 'A-2',  x: 23.5, y: 30, w: 1.5, h: 7, pricePerDay: 50, capacity: 2 },
    { id: 'A3',  name: 'A-3',  x: 26.8, y: 30, w: 1.5, h: 7, pricePerDay: 50, capacity: 2 },
    { id: 'A4',  name: 'A-4',  x: 34.7, y: 30, w: 1.5, h: 7, pricePerDay: 50, capacity: 2 },
    { id: 'A5',  name: 'A-5',  x: 38.7, y: 30, w: 1.5, h: 7, pricePerDay: 50, capacity: 2 },
    { id: 'A6',  name: 'A-6',  x: 42.6, y: 30, w: 1.5, h: 7, pricePerDay: 50, capacity: 2 },
    { id: 'A7',  name: 'A-7',  x: 46.3, y: 30, w: 1.5, h: 7, pricePerDay: 50, capacity: 2 },
    { id: 'A8',  name: 'A-8',  x: 49.9, y: 30, w: 1.5, h: 7, pricePerDay: 50, capacity: 2 },
    { id: 'A9',  name: 'A-9',  x: 53.7, y: 30, w: 1.5, h: 7, pricePerDay: 50, capacity: 2 },
    { id: 'A10', name: 'A-10', x: 57.4, y: 30, w: 1.5, h: 7, pricePerDay: 50, capacity: 2 },
    { id: 'A11', name: 'A-11', x: 59.7, y: 30, w: 1.5, h: 7, pricePerDay: 50, capacity: 2 },
    { id: 'A12', name: 'A-12', x: 64.1, y: 30, w: 1.5, h: 7, pricePerDay: 50, capacity: 2 },
    { id: 'A13', name: 'A-13', x: 67.2, y: 30, w: 1.5, h: 7, pricePerDay: 50, capacity: 2 },
    { id: 'A14', name: 'A-14', x: 70.5, y: 30, w: 1.5, h: 7, pricePerDay: 50, capacity: 2 },

    // LOWER PIER - 12 finger docks in a horizontal line
    { id: 'B1',  name: 'B-1',  x: 20.0, y: 59, w: 1.5, h: 7, pricePerDay: 50, capacity: 2 },
    { id: 'B2',  name: 'B-2',  x: 21.8, y: 59, w: 1.5, h: 7, pricePerDay: 50, capacity: 2 },
    { id: 'B3',  name: 'B-3',  x: 23.6, y: 59, w: 1.5, h: 7, pricePerDay: 50, capacity: 2 },
    { id: 'B4',  name: 'B-4',  x: 27.0, y: 59, w: 1.5, h: 7, pricePerDay: 50, capacity: 2 },
    { id: 'B5',  name: 'B-5',  x: 30.3, y: 59, w: 1.5, h: 7, pricePerDay: 50, capacity: 2 },
    { id: 'B6',  name: 'B-6',  x: 33.2, y: 59, w: 1.5, h: 7, pricePerDay: 50, capacity: 2 },
    { id: 'B7',  name: 'B-7',  x: 36.0, y: 59, w: 1.5, h: 7, pricePerDay: 50, capacity: 2 },
    { id: 'B8',  name: 'B-8',  x: 38.8, y: 59, w: 1.5, h: 7, pricePerDay: 50, capacity: 2 },
    { id: 'B9',  name: 'B-9',  x: 41.8, y: 59, w: 1.5, h: 7, pricePerDay: 50, capacity: 2 },
    { id: 'B10', name: 'B-10', x: 45.1, y: 59, w: 1.5, h: 7, pricePerDay: 50, capacity: 2 },
    { id: 'B11', name: 'B-11', x: 48.4, y: 59, w: 1.5, h: 7, pricePerDay: 50, capacity: 2 },
];

// Reserved berths (for demo - would come from backend)
// Each berth can have multiple boats based on capacity
const reservedBerths = {
    'A3': { boats: [{ boatId: 1, boatName: 'Windspiel', until: '2026-03-15' }] },
    'A7': { boats: [
        { boatId: 2, boatName: 'Meerblick', until: '2026-02-28' },
        { boatId: 4, boatName: 'Sonnenschein', until: '2026-04-10' }
    ]},
    'B5': { boats: [{ boatId: 3, boatName: 'Wellenreiter', until: '2026-03-20' }] },
};

let panX = 0;
let panY = 0;
let isPanning = false;
let startX = 0;
let startY = 0;
let draggedBoat = null;
let currentBerth = null;
let viewportWidth = 0;
let viewportHeight = 0;
let imageWidth = 0;
let imageHeight = 0;
let scale = 1;

let liegeplaetzeState = {};
let featuresState = {};
let booteState = {};

async function initialLoad() {
    const {booteData, featuresData, liegeplaetzeData} = await apiGetMany({
        booteData: '/bootsverleih/load',
        featuresData: '/features/load',
        liegeplaetzeData: '/liegeplaetze/load',
    })
    liegeplaetzeState = liegeplaetzeData;
    featuresState = featuresData;
    booteState = booteData;
}

export async function init() {
    await initialLoad();
    applyDarkMode();
    renderBoatsList();

    const img = document.getElementById('marinaMap');
    const viewport = document.getElementById('mapViewport');

    if (img && viewport) {
        const onImageReady = () => {
            imageWidth = img.naturalWidth;
            imageHeight = img.naturalHeight;
            viewportWidth = viewport.clientWidth;
            viewportHeight = viewport.clientHeight;

            // Set overlay to match image dimensions
            const overlay = document.getElementById('berthsOverlay');
            if (overlay) {
                overlay.style.width = `${imageWidth}px`;
                overlay.style.height = `${imageHeight}px`;
            }

            // Calculate scale to cover viewport
            calculateCoverScale();
            createBerthMarkers();
            updateTransform();
        };

        if (img.complete && img.naturalWidth > 0) {
            onImageReady();
        } else {
            img.addEventListener('load', onImageReady);
        }

        // Recalculate on window resize
        window.addEventListener('resize', () => {
            viewportWidth = viewport.clientWidth;
            viewportHeight = viewport.clientHeight;
            calculateCoverScale();
            constrainPan();
            updateTransform();
        });
    }

    wirePan();
    wireDragDrop();
    wireModal();
}

function calculateCoverScale() {
    if (!imageWidth || !imageHeight || !viewportWidth || !viewportHeight) return;

    // Scale needed to cover viewport (like CSS background-size: cover)
    const scaleX = viewportWidth / imageWidth;
    const scaleY = viewportHeight / imageHeight;
    scale = Math.max(scaleX, scaleY);
}

function renderBoatsList() {
    const list = document.getElementById('boatsList');
    if (!list) return;

    list.innerHTML = '';

    dummyBoats.forEach(boat => {
        const item = document.createElement('div');
        item.className = 'boat-item';
        item.draggable = true;
        item.dataset.boatId = boat.id;

        item.innerHTML = `
            <div class="boat-item-icon">${boat.icon}</div>
            <div class="boat-item-name">${escape(boat.name)}</div>
            <div class="boat-item-type">${escape(boat.type)}</div>
            <div class="boat-item-info">
                <span>${escape(boat.length)}</span>
                <span>${boat.capacity} Pers.</span>
            </div>
        `;

        list.appendChild(item);
    });
}

function createBerthMarkers() {
    const overlay = document.getElementById('berthsOverlay');
    if (!overlay) return;

    overlay.innerHTML = '';

    berthPositions.forEach(berth => {
        const marker = document.createElement('div');
        marker.className = 'berth-marker';
        marker.dataset.berthId = berth.id;
        marker.dataset.name = berth.name;

        // Position and size based on percentage of image
        marker.style.left = `${berth.x}%`;
        marker.style.top = `${berth.y}%`;
        marker.style.width = `${berth.w}%`;
        marker.style.height = `${berth.h}%`;

        // Check reservation status
        const reservation = reservedBerths[berth.id];
        const bookedCount = reservation ? reservation.boats.length : 0;
        const availableSpots = berth.capacity - bookedCount;

        if (availableSpots === 0) {
            // Fully booked
            marker.classList.add('reserved');
            const boatNames = reservation.boats.map(b => b.boatName).join(', ');
            marker.title = `${berth.name} - Voll belegt (${boatNames})`;
        } else if (bookedCount > 0) {
            // Partially booked
            marker.classList.add('partial');
            marker.title = `${berth.name} - ${availableSpots}/${berth.capacity} frei - ${formatEuro(berth.pricePerDay)}/Tag`;
        } else {
            // Fully available
            marker.title = `${berth.name} - ${berth.capacity} Plätze - ${formatEuro(berth.pricePerDay)}/Tag`;
        }

        // Show capacity indicator
        marker.dataset.capacity = berth.capacity;
        marker.dataset.available = availableSpots;

        overlay.appendChild(marker);
    });
}

function constrainPan() {
    const scaledWidth = imageWidth * scale;
    const scaledHeight = imageHeight * scale;

    const maxPanX = 0;
    const minPanX = viewportWidth - scaledWidth;
    const maxPanY = 0;
    const minPanY = viewportHeight - scaledHeight;

    panX = Math.min(maxPanX, Math.max(minPanX, panX));
    panY = Math.min(maxPanY, Math.max(minPanY, panY));
}

function updateTransform() {
    const content = document.getElementById('mapContent');
    if (!content) return;

    content.style.transform = `translate(${panX}px, ${panY}px) scale(${scale})`;
}

function wirePan() {
    const viewport = document.getElementById('mapViewport');
    if (!viewport) return;

    // Pan with mouse drag
    viewport.addEventListener('mousedown', e => {
        if (e.target.classList.contains('berth-marker')) return;

        isPanning = true;
        startX = e.clientX - panX;
        startY = e.clientY - panY;
        viewport.style.cursor = 'grabbing';
    });

    document.addEventListener('mousemove', e => {
        if (!isPanning) return;

        panX = e.clientX - startX;
        panY = e.clientY - startY;
        constrainPan();
        updateTransform();
    });

    document.addEventListener('mouseup', () => {
        isPanning = false;
        const viewport = document.getElementById('mapViewport');
        if (viewport) viewport.style.cursor = 'grab';
    });

    // Touch support for panning only
    viewport.addEventListener('touchstart', e => {
        if (e.touches.length === 1) {
            isPanning = true;
            startX = e.touches[0].clientX - panX;
            startY = e.touches[0].clientY - panY;
        }
    }, { passive: true });

    viewport.addEventListener('touchmove', e => {
        if (e.touches.length === 1 && isPanning) {
            panX = e.touches[0].clientX - startX;
            panY = e.touches[0].clientY - startY;
            constrainPan();
            updateTransform();
        }
    }, { passive: true });

    viewport.addEventListener('touchend', () => {
        isPanning = false;
    });
}

function wireDragDrop() {
    const boatsList = document.getElementById('boatsList');
    const overlay = document.getElementById('berthsOverlay');

    if (!boatsList || !overlay) return;

    // Drag start on boat items
    boatsList.addEventListener('dragstart', e => {
        const item = e.target.closest('.boat-item');
        if (!item) return;

        draggedBoat = dummyBoats.find(b => b.id === Number(item.dataset.boatId));
        item.classList.add('dragging');

        e.dataTransfer.effectAllowed = 'move';
        e.dataTransfer.setData('text/plain', item.dataset.boatId);
    });

    boatsList.addEventListener('dragend', e => {
        const item = e.target.closest('.boat-item');
        if (item) item.classList.remove('dragging');
        draggedBoat = null;

        document.querySelectorAll('.berth-marker.drag-over').forEach(m => {
            m.classList.remove('drag-over');
        });
    });

    // Drop on berth markers
    overlay.addEventListener('dragover', e => {
        e.preventDefault();
        e.dataTransfer.dropEffect = 'move';

        const marker = e.target.closest('.berth-marker');
        if (marker && Number(marker.dataset.available) > 0) {
            marker.classList.add('drag-over');
        }
    });

    overlay.addEventListener('dragleave', e => {
        const marker = e.target.closest('.berth-marker');
        if (marker) {
            marker.classList.remove('drag-over');
        }
    });

    overlay.addEventListener('drop', e => {
        e.preventDefault();

        const marker = e.target.closest('.berth-marker');
        if (!marker) return;

        marker.classList.remove('drag-over');

        // Check if fully booked
        const availableSpots = Number(marker.dataset.available);
        if (availableSpots === 0) {
            alert('Dieser Liegeplatz ist voll belegt.');
            return;
        }

        const berthId = marker.dataset.berthId;
        const berth = berthPositions.find(b => b.id === berthId);

        if (draggedBoat && berth) {
            currentBerth = berth;
            openReservationModal(berth, draggedBoat);
        }
    });
}

function openReservationModal(berth, boat) {
    const modal = document.getElementById('reservationModal');
    if (!modal) return;

    currentBerth = berth;

    // Calculate available spots
    const reservation = reservedBerths[berth.id];
    const bookedCount = reservation ? reservation.boats.length : 0;
    const availableSpots = berth.capacity - bookedCount;

    // Set info
    document.getElementById('info-berth-name').textContent = berth.name;
    document.getElementById('info-capacity').textContent = `${availableSpots} von ${berth.capacity} frei`;
    document.getElementById('info-boat-name').textContent = boat.name;
    document.getElementById('info-price-per-day').textContent = formatEuro(berth.pricePerDay);
    document.getElementById('reservation-berth-id').value = berth.id;
    document.getElementById('reservation-boat-id').value = boat.id;

    // Set default dates
    const today = new Date().toISOString().split('T')[0];
    const endDate = new Date();
    endDate.setMonth(endDate.getMonth() + 1);

    const startInput = document.getElementById('reservation-start-date');
    const endInput = document.getElementById('reservation-end-date');

    startInput.value = today;
    startInput.min = today;
    endInput.value = endDate.toISOString().split('T')[0];
    endInput.min = today;

    updateCostTotal();
    modal.style.display = 'block';
}

function wireModal() {
    // Close buttons
    document.querySelectorAll('[data-close]').forEach(el => {
        el.addEventListener('click', () => {
            closeModal(el.dataset.close);
        });
    });

    // Click outside to close
    window.addEventListener('click', e => {
        if (e.target.classList.contains('modal')) {
            e.target.style.display = 'none';
        }
    });

    // Date changes update total
    document.getElementById('reservation-start-date')?.addEventListener('change', updateCostTotal);
    document.getElementById('reservation-end-date')?.addEventListener('change', updateCostTotal);

    // Form submit
    document.getElementById('reservationForm')?.addEventListener('submit', e => {
        e.preventDefault();
        handleReservation();
    });
}

function updateCostTotal() {
    if (!currentBerth) return;

    const startDate = document.getElementById('reservation-start-date')?.value;
    const endDate = document.getElementById('reservation-end-date')?.value;

    let total = 0;
    let days = 0;

    if (startDate && endDate) {
        const start = new Date(startDate);
        const end = new Date(endDate);
        days = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
        if (days > 0) {
            total = currentBerth.pricePerDay * days;
        }
    }

    document.getElementById('cost-days').textContent = days > 0 ? `${days} Tag${days > 1 ? 'e' : ''}` : '-';
    document.getElementById('cost-total').textContent = formatEuro(total);
}

function handleReservation() {
    const berthId = document.getElementById('reservation-berth-id')?.value;
    const boatId = document.getElementById('reservation-boat-id')?.value;
    const startDate = document.getElementById('reservation-start-date')?.value;
    const endDate = document.getElementById('reservation-end-date')?.value;

    const boat = dummyBoats.find(b => b.id === Number(boatId));
    const berth = berthPositions.find(b => b.id === berthId);

    if (!boat || !berth) return;

    // Calculate total
    const start = new Date(startDate);
    const end = new Date(endDate);
    const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
    const total = berth.pricePerDay * days;

    // Add to reserved (in real app, would send to backend)
    if (!reservedBerths[berthId]) {
        reservedBerths[berthId] = { boats: [] };
    }
    reservedBerths[berthId].boats.push({
        boatId: Number(boatId),
        boatName: boat.name,
        until: endDate
    });

    // Update marker
    const marker = document.querySelector(`.berth-marker[data-berth-id="${berthId}"]`);
    if (marker) {
        const bookedCount = reservedBerths[berthId].boats.length;
        const availableSpots = berth.capacity - bookedCount;
        marker.dataset.available = availableSpots;

        if (availableSpots === 0) {
            marker.classList.remove('partial');
            marker.classList.add('reserved');
            const boatNames = reservedBerths[berthId].boats.map(b => b.boatName).join(', ');
            marker.title = `${berth.name} - Voll belegt (${boatNames})`;
        } else {
            marker.classList.add('partial');
            marker.title = `${berth.name} - ${availableSpots}/${berth.capacity} frei - ${formatEuro(berth.pricePerDay)}/Tag`;
        }
    }

    alert(
        `Reservierung erfolgreich!\n\n` +
        `Liegeplatz: ${berth.name}\n` +
        `Boot: ${boat.name}\n` +
        `Von: ${startDate}\n` +
        `Bis: ${endDate}\n` +
        `Dauer: ${days} Tag${days > 1 ? 'e' : ''}\n` +
        `Preis pro Tag: ${formatEuro(berth.pricePerDay)}\n` +
        `Gesamtpreis: ${formatEuro(total)}`
    );

    closeModal('reservationModal');
    currentBerth = null;
}

function closeModal(id) {
    const modal = document.getElementById(id);
    if (modal) modal.style.display = 'none';
    currentBerth = null;
}

function applyDarkMode() {
    if (localStorage.getItem('darkMode') === 'true') {
        document.body.classList.add('dark-mode');
    }
}

function formatEuro(v) {
    return Number(v || 0).toFixed(2).replace('.', ',') + ' €';
}

const escape = v => String(v ?? '')
    .replaceAll('&', '&amp;')
    .replaceAll('<', '&lt;')
    .replaceAll('>', '&gt;');
