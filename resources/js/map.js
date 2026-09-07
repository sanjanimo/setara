import L from "leaflet";
import "leaflet/dist/leaflet.css";

const STATUS_COLORS = {
    aman: "#16A34A",
    waspada: "#F59E0B",
    kritis: "#DC2626",
};

const TYPE_LABELS = {
    anak: "Panti Anak",
    jompo: "Panti Jompo",
    campuran: "Panti Campuran",
};

function escapeHtml(value) {
    return String(value ?? "")
        .replaceAll("&", "&amp;")
        .replaceAll("<", "&lt;")
        .replaceAll(">", "&gt;")
        .replaceAll('"', "&quot;")
        .replaceAll("'", "&#039;");
}

document.addEventListener("DOMContentLoaded", async () => {
    const mapEl = document.getElementById("setara-map");

    if (!mapEl) {
        return;
    }

    const listEl = document.getElementById("panti-map-list");
    const countEl = document.getElementById("map-result-count");
    const statusFilter = document.getElementById("filter-status");
    const typeFilter = document.getElementById("filter-type");

    const map = L.map(mapEl, {
        scrollWheelZoom: false,
    }).setView([-6.8846, 107.612], 14);

    map.on("click", () => {
        map.scrollWheelZoom.enable();
    });

    const cartoKey = import.meta.env.VITE_CARTO_KEY || "";

    L.tileLayer(
        `https://{s}.basemaps.cartocdn.com/light_all/{z}/{x}/{y}{r}.png?api_key=${cartoKey}`,
        {
            attribution:
                '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors &copy; <a href="https://carto.com/">CARTO</a>',
            subdomains: "abcd",
            maxZoom: 20,
        },
    ).addTo(map);

    const markers = L.layerGroup().addTo(map);

    let pantis = [];

    try {
        const response = await fetch(mapEl.dataset.url, {
            headers: {
                Accept: "application/json",
            },
        });

        if (!response.ok) {
            throw new Error("Data peta gagal dimuat.");
        }

        pantis = await response.json();
    } catch (error) {
        if (listEl) {
            listEl.innerHTML = `
                <div class="rounded-2xl border border-urgency-red/30 bg-urgency-red/10 px-4 py-3 text-sm text-urgency-red">
                    ${escapeHtml(error.message)}
                </div>
            `;
        }

        return;
    }

    function createIcon(status) {
        const color = STATUS_COLORS[status] || "#57534E";
        const criticalClass = status === "kritis" ? "setara-pin-kritis" : "";

        return L.divIcon({
            className: "",
            html: `<span class="setara-pin ${criticalClass}" style="background:${color};"></span>`,
            iconSize: [18, 18],
            iconAnchor: [9, 9],
            popupAnchor: [0, -10],
        });
    }

    function popupContent(panti) {
        const statusColor = STATUS_COLORS[panti.urgency_status] || "#57534E";
        const statusText =
            panti.urgency_status.charAt(0).toUpperCase() +
            panti.urgency_status.slice(1);
        const typeLabel = TYPE_LABELS[panti.type] || panti.type;

        const topNeed = panti.top_need_title
            ? `${escapeHtml(panti.top_need_title)}`
            : "Tidak ada kebutuhan aktif.";

        return `
            <div style="min-width: 230px; font-family: 'Inter', sans-serif;">
                <div style="display: flex; align-items: flex-start; justify-content: space-between; gap: 10px;">
                    <div>
                        <p style="margin: 0; font-weight: 700; color: #0F172A;">
                            ${escapeHtml(panti.name)}
                        </p>
                        <p style="margin: 4px 0 0; font-size: 12px; color: #57534E;">
                            ${escapeHtml(typeLabel)} • ${escapeHtml(panti.city)}
                        </p>
                    </div>

                    <span style="background:${statusColor}; color:${panti.urgency_status === "waspada" ? "#0F172A" : "#ffffff"}; font-size: 10px; font-weight: 700; text-transform: uppercase; padding: 4px 8px; border-radius: 9999px;">
                        ${escapeHtml(statusText)}
                    </span>
                </div>

                <p style="margin: 10px 0 0; font-size: 13px; color: #57534E;">
                    Kebutuhan prioritas: <strong>${topNeed}</strong>
                </p>

                <p style="margin: 4px 0 0; font-size: 12px; color: #57534E;">
                    ${panti.active_needs_count} kebutuhan aktif
                </p>

                <a href="${panti.detail_url}" style="margin-top: 12px; display: inline-flex; align-items: center; justify-content: center; background: #0F766E; color: #ffffff; font-size: 12px; font-weight: 600; padding: 7px 12px; border-radius: 8px; text-decoration: none;">
                    Lihat Detail
                </a>
            </div>
        `;
    }

    function listItem(panti) {
        const statusColor = STATUS_COLORS[panti.urgency_status] || "#57534E";
        const statusText =
            panti.urgency_status.charAt(0).toUpperCase() +
            panti.urgency_status.slice(1);
        const typeLabel = TYPE_LABELS[panti.type] || panti.type;

        const topNeed = panti.top_need_title
            ? `<p class="mt-2 text-xs text-stone-gray">Kebutuhan prioritas: <span class="font-medium text-stone-ink">${escapeHtml(panti.top_need_title)}</span></p>`
            : "";

        return `
            <a href="${panti.detail_url}" class="block rounded-2xl border border-border-soft bg-warm-surface p-4 shadow-sm transition hover:shadow-md">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h3 class="text-sm font-semibold text-stone-ink">${escapeHtml(panti.name)}</h3>
                        <p class="mt-1 text-xs text-stone-gray">${escapeHtml(typeLabel)} • ${escapeHtml(panti.city)}</p>
                    </div>

                    <span class="rounded-full px-2.5 py-1 text-[10px] font-bold uppercase" style="background:${statusColor}; color:${panti.urgency_status === "waspada" ? "#0F172A" : "#ffffff"};">
                        ${escapeHtml(statusText)}
                    </span>
                </div>

                ${topNeed}

                <p class="mt-2 text-xs text-stone-gray">${panti.active_needs_count} kebutuhan aktif</p>
            </a>
        `;
    }

    function applyFilters() {
        const statusValue = statusFilter?.value || "all";
        const typeValue = typeFilter?.value || "all";

        const filtered = pantis.filter((panti) => {
            const matchStatus =
                statusValue === "all" || panti.urgency_status === statusValue;
            const matchType = typeValue === "all" || panti.type === typeValue;

            return matchStatus && matchType;
        });

        markers.clearLayers();

        if (listEl) {
            listEl.innerHTML = "";
        }

        if (countEl) {
            countEl.textContent = `${filtered.length} panti ditampilkan`;
        }

        if (filtered.length === 0) {
            if (listEl) {
                listEl.innerHTML = `
                    <div class="rounded-2xl border border-border-soft bg-warm-surface px-4 py-6 text-center text-sm text-stone-gray">
                        Tidak ada panti yang cocok dengan filter saat ini.
                    </div>
                `;
            }

            map.setView([-6.8846, 107.612], 13);
            return;
        }

        filtered.forEach((panti) => {
            const marker = L.marker([panti.latitude, panti.longitude], {
                icon: createIcon(panti.urgency_status),
            }).bindPopup(popupContent(panti));

            markers.addLayer(marker);

            if (listEl) {
                listEl.insertAdjacentHTML("beforeend", listItem(panti));
            }
        });

        const bounds = L.latLngBounds(
            filtered.map((panti) => [panti.latitude, panti.longitude]),
        );
        map.fitBounds(bounds, {
            padding: [40, 40],
        });
    }

    statusFilter?.addEventListener("change", applyFilters);
    typeFilter?.addEventListener("change", applyFilters);

    applyFilters();
});
