import {apiGetMany, apiPostFormData, toFormData} from "../services/api.js";
import {formatEuro, escape} from "../services/helpers.js";
import {BOAT_TYPE_ICONS} from "../services/BoatConstants.js";

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

let berths = {};
let features = {};
let boats = {};

async function initialLoad() {
    const {boote, featuresData, liegeplaetze} = await apiGetMany({
        boote: '/bootsverleih/loadmietedBoote',
        featuresData: '/features/load',
        liegeplaetze: '/liegeplaetze/load',
    })
    berths = liegeplaetze;
    features = featuresData;
    boats = boote;
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

    Object.values(boats).forEach(boat => {
        const item = document.createElement('div');
        item.className = 'boat-item';
        item.draggable = true;
        item.dataset.boatId = boat.id;

        item.innerHTML = `
            <div class="boat-item-icon">${BOAT_TYPE_ICONS[boat.type]}</div>
            <div class="boat-item-name">${escape(boat.name)}</div>
            <div class="boat-item-type">${window.APP.enums.bootTypen?.[boat.type] ?? '-'}</div>
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

    Object.values(berths).forEach(lp => {
        const marker = document.createElement('div');
        marker.className = 'berth-marker';
        marker.dataset.berthId = lp.id
        marker.dataset.name = lp.bezeichnung;

        // Position
        marker.style.left = `${lp.pos.x}%`;
        marker.style.top  = `${lp.pos.y}%`;
        marker.style.width  = `${lp.pos.w}%`;
        marker.style.height = `${lp.pos.h}%`;

        const reservierungen = lp.reservierungen ?? [];
        const bookedCount = reservierungen.length;
        const available = lp.capacity - bookedCount;

        marker.dataset.capacity = lp.capacity;
        marker.dataset.available = available;

        if (available <= 0) {
            marker.classList.add('reserved');
            marker.title = `${lp.bezeichnung} – voll belegt`;
        } else if (bookedCount > 0) {
            marker.classList.add('partial');
            marker.title = `${lp.bezeichnung} – ${available}/${lp.capacity} frei`;
        } else {
            marker.title = `${lp.bezeichnung} – ${lp.capacity} Plätze frei`;
        }

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

        draggedBoat = boats[item.dataset.boatId];
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
        const available = Number(marker.dataset.available);
        if (available <= 0) {
            alert('Liegeplatz ist voll.');
            return;
        }

        const berthId = marker.dataset.berthId;
        const berth = berths[berthId];

        if (draggedBoat && berth) {
            currentBerth = berth;
            openReservationModal(berth, draggedBoat);
        }
    });
}

function openReservationModal(berth, boat) {
    const modal = document.getElementById(
        'reservationModal');

    if (!modal) return;

    const reservations = berth?.reservierungen ?? [];

    // Calculate available spots
    const bookedCount = reservations.length;
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

async function handleReservation() {
    const berthId = document.getElementById('reservation-berth-id')?.value;
    const boatId = document.getElementById('reservation-boat-id')?.value;
    const startDate = document.getElementById('reservation-start-date')?.value;
    const endDate = document.getElementById('reservation-end-date')?.value;

    const boat = boats[boatId]
    const berth = berths[berthId];

    if (!boat || !berth) return;

    // Calculate total
    const start = new Date(startDate);
    const end = new Date(endDate);
    const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
    const total = berth.pricePerDay * days;

    // Add to reserved (in real app, would send to backend)
    const reservations = berth?.reservierungen ?? [];

    const res = {
        boot: Number(boatId),
        liegeplatz: berth.id,
        startdatum: endDate,
        enddatum: startDate,
        preisProTag: berth.pricePerDay,
    }

    const fd = toFormData(res, 'data');
    const saved = await apiPostFormData('/liegeplaetze/reservieren', fd);

    if (saved) {
        reservations.push(saved);

        // Update marker
        const marker = document.querySelector(`.berth-marker[data-berth-id="${berthId}"]`);
        if (marker) {
            const bookedCount = reservations.length;
            const availableSpots = berth.capacity - bookedCount;
            marker.dataset.available = availableSpots;

            if (availableSpots === 0) {
                marker.classList.remove('partial');
                marker.classList.add('reserved');
                const boatNames = reservations.map(r => boats[r.boot]?.name).join(', ');
                marker.title = `${berth.name} - Voll belegt (${boatNames})`;
            } else {
                marker.classList.add('partial');
                marker.title = `${berth.name} - ${availableSpots}/${berth.capacity} frei - ${formatEuro(berth.pricePerDay)}/Tag`;
            }
        }

        alert(
            `Reservierung erfolgreich!\n\n` +
            `Liegeplatz: ${berth.name}\n` +
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