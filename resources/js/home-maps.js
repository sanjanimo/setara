import L from "leaflet";

const DEFAULT_CENTER = [-6.9175, 107.6191];
const TILE_URL = "https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png";

function createPin(color, pulsing = false) {
    const pulse = pulsing
        ? `<div style="position:absolute;width:100%;height:100%;border-radius:50%;background-color:${color};opacity:.75;animation:ping 1.5s cubic-bezier(0,0,.2,1) infinite;"></div>`
        : "";

    return L.divIcon({
        className: "setara-home-pin",
        html: `<div style="position:relative;width:16px;height:16px;">${pulse}<div style="position:relative;width:16px;height:16px;background-color:${color};border:2px solid white;border-radius:50%;box-shadow:0 2px 4px rgba(0,0,0,.3);"></div></div>`,
        iconSize: [16, 16],
        iconAnchor: [8, 8],
    });
}

function createMap(element, options = {}) {
    const map = L.map(element, {
        zoomControl: false,
        scrollWheelZoom: false,
        attributionControl: false,
        ...options,
    });

    L.tileLayer(TILE_URL, { maxZoom: 19 }).addTo(map);
    return map;
}

function initHeroMap() {
    const element = document.getElementById("hero-map");
    if (!element) return;

    const lat = Number(element.dataset.lat) || DEFAULT_CENTER[0];
    const lng = Number(element.dataset.lng) || DEFAULT_CENTER[1];
    const urgency = element.dataset.urgency || "kritis";
    const color = urgency === "waspada" ? "#F59E0B" : urgency === "aman" ? "#16A34A" : "#DC2626";
    const map = createMap(element);

    map.setView([lat, lng], 14);
    L.marker([lat, lng], { icon: createPin(color, true) })
        .addTo(map)
        .bindPopup(`<strong>${element.dataset.name || "Panti Prioritas"}</strong>`);
}

function initPreviewMap() {
    const element = document.getElementById("preview-map");
    if (!element) return;

    const map = createMap(element, {
        dragging: false,
        touchZoom: false,
        doubleClickZoom: false,
    });

    map.setView(DEFAULT_CENTER, 12);

    const samplePoints = [
        [-6.8915, 107.6101, "#DC2626", true],
        [-6.9025, 107.6181, "#F59E0B", false],
        [-6.8825, 107.6251, "#16A34A", false],
        [-6.9255, 107.6051, "#DC2626", true],
    ];

    samplePoints.forEach(([lat, lng, color, pulsing]) => {
        L.marker([lat, lng], { icon: createPin(color, pulsing) }).addTo(map);
    });
}

document.addEventListener("DOMContentLoaded", () => {
    initHeroMap();
    initPreviewMap();
});
