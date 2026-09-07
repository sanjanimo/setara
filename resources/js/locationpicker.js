import L from 'leaflet';

window.locationPicker = function (initial = {}) {
    return {
        province_id: '',
        city_id: '',
        district_id: '',
        provinces: [],
        cities: [],
        districts: [],
        provinceName: '',
        cityName: '',
        districtName: '',
        latitude: '',
        longitude: '',
        searchQuery: '',
        map: null,
        marker: null,
        ...initial,

        async init() {
            const initialProvinceName = this.provinceName;
            const initialCityName = this.cityName;
            const initialDistrictName = this.districtName;

            const provinceResponse = await fetch('/api/provinces');
            this.provinces = await provinceResponse.json();

            const province = this.provinces.find(({ name }) => name === initialProvinceName);
            if (province) {
                this.province_id = String(province.id);
                await this.onProvinceChange();

                const city = this.cities.find(({ name }) => name === initialCityName);
                if (city) {
                    this.city_id = String(city.id);
                    await this.onCityChange();

                    const district = this.districts.find(({ name }) => name === initialDistrictName);
                    if (district) {
                        this.district_id = String(district.id);
                    }
                }
            }

            this.$nextTick(() => {
                const el = document.getElementById('panti-picker-map');
                if (!el) return;

                this.map = L.map(el).setView([-2.5, 118], 5);
                L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
                    attribution: '© OpenStreetMap',
                }).addTo(this.map);

                const initialLatitude = Number(this.latitude);
                const initialLongitude = Number(this.longitude);
                const hasInitialLocation = Number.isFinite(initialLatitude) && Number.isFinite(initialLongitude);
                const initialLocation = hasInitialLocation ? [initialLatitude, initialLongitude] : [-2.5, 118];

                if (hasInitialLocation) {
                    this.map.setView(initialLocation, 15);
                }

                this.marker = L.marker(initialLocation, { draggable: true }).addTo(this.map);
                this.marker.on('dragend', () => {
                    const ll = this.marker.getLatLng();
                    this.latitude = ll.lat.toFixed(6);
                    this.longitude = ll.lng.toFixed(6);
                });

                this.map.on('click', (e) => {
                    this.marker.setLatLng(e.latlng);
                    this.latitude = e.latlng.lat.toFixed(6);
                    this.longitude = e.latlng.lng.toFixed(6);
                });
            });
        },

        async onProvinceChange() {
            this.city_id = '';
            this.district_id = '';
            this.cities = [];
            this.districts = [];
            if (!this.province_id) return;
            this.provinceName = this.provinces.find(p => p.id == this.province_id)?.name || '';
            const r = await fetch(`/api/cities?province_id=${this.province_id}`);
            this.cities = await r.json();
        },

        async onCityChange() {
            this.district_id = '';
            this.districts = [];
            if (!this.city_id) return;
            this.cityName = this.cities.find(c => c.id == this.city_id)?.name || '';
            const r = await fetch(`/api/districts?city_id=${this.city_id}`);
            this.districts = await r.json();
        },

        async searchAddress() {
            if (!this.searchQuery) return;
            const r = await fetch(`https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(this.searchQuery)}&limit=1`);
            const data = await r.json();
            if (data.length > 0) {
                const { lat, lon } = data[0];
                const ll = L.latLng(parseFloat(lat), parseFloat(lon));
                this.map.setView(ll, 15);
                this.marker.setLatLng(ll);
                this.latitude = ll.lat.toFixed(6);
                this.longitude = ll.lng.toFixed(6);
            }
        },
    };
};
