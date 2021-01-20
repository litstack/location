<template>
    <lit-base-field :field="field">
        <b-input ref="search" class="mb-2 rounded-full" v-model="searchQuery" />
        <div
            ref="mapDiv"
            :style="`height: ${field.height}; width: 100%`"
            class="rounded-full"
        ></div>
    </lit-base-field>
</template>

<script>
import LocationPicker from 'location-picker';

export default {
    name: 'LitMap',
    props: {
        field: {
            required: true,
            type: Object,
        },
        value: {
            required: true,
        },
        model: {
            type: Object,
        },
    },
    data() {
        return {
            test: '',
            changed: false,
            searchQuery: null,
            map: null,
            markers: [],
            zoom: 4,
            options: [],
            geocoder: null,
            autocomplete: null,
            pin: {
                url:
                    'data:image/svg+xml;utf-8, <svg width="100%" height="100%" viewBox="0 0 29 35" version="1.1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" xmlns:serif="http://www.serif.com/" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:2;"><path id="Oval-Copy-3" d="M14.5,34.972C24.167,26.663 29,19.839 29,14.5C29,6.492 22.508,0 14.5,0C6.492,0 0,6.492 0,14.5C0,19.839 4.833,26.663 14.5,34.972Z" style="fill:rgb(9,14,35);"/><circle id="Oval-Copy-2" cx="14.5" cy="16" r="6" style="fill:rgb(64,255,164);fill-opacity:0.32;"/><circle id="Oval-Copy" cx="14.5" cy="13" r="6" style="fill:rgb(64,255,164);"/></svg>',
                size: new google.maps.Size(29, 35),
                scaledSize: new google.maps.Size(29, 35),
            },
            mapOptions: {
                styles: [
                    {
                        featureType: 'poi',
                        stylers: [{ visibility: 'off' }],
                    },
                ],
            },
        };
    },
    beforeMount() {
        Lit.bus.$on('saved', () => {
            if (!this.changed) {
                return;
            }

            Lit.bus.$emit('reload:model');
        });
    },
    mounted() {
        this.geocoder = new google.maps.Geocoder();
        this.initMap(this.getLocationFromModel());
        this.setMarker(this.getLocationFromModel());

        if (this.markers.length > 0) {
            this.goTo(this.getLocationFromModel());
        }
    },
    methods: {
        input(val) {
            this.changed = true;
            this.setMarker(val.geometry.location);

            this.$emit('input', val);
        },
        getLocationFromModel() {
            return {
                lat: parseFloat(this.model[this.field.lat_key]),
                lng: parseFloat(this.model[this.field.lng_key]),
            };
        },
        goTo(location) {
            this.map.setCenter(location);
            this.setZoom(15);
        },
        setMarker(location) {
            this.clearMarkers();

            const marker = new google.maps.Marker({
                map: this.map,
                position: location,
                icon: this.pin,
            });

            this.markers = [marker];
        },
        initMap(location) {
            const element = this.$refs.mapDiv;
            const map = new google.maps.Map(element, {
                zoom: this.zoom,
                center: location,
            });

            map.setOptions(this.mapOptions);

            this.map = map;

            map.addListener('click', event => {
                this.setMarker(event.latLng);
                this.findPlace(event.latLng);
                this.input({
                    geometry: { location: event.latLng },
                });
            });

            this.autocomplete = new google.maps.places.Autocomplete(
                this.$refs.search.$el
            );
            google.maps.event.addListener(
                this.autocomplete,
                'place_changed',
                () => {
                    this.input(this.autocomplete.getPlace());
                    this.goTo(this.autocomplete.getPlace().geometry.location);
                }
            );
        },
        findPlace(location) {
            const request = {
                location,
                radius: '50',
                fields: ['formatted_address'],
            };

            const service = new google.maps.places.PlacesService(this.map);
            service.nearbySearch(request, (results, status) => {
                if (status === google.maps.places.PlacesServiceStatus.OK) {
                    let place = `${results[0].name}, ${results[0].vicinity}`;
                    if (results[0].name == results[0].vicinity) {
                        place = results[0].name;
                    }
                    this.searchQuery = place;
                    this.$nextTick(() => {
                        this.$refs.search.focus();
                        var e = new KeyboardEvent('keydown', {
                            keyCode: 32,
                            which: 32,
                        });

                        this.$refs.search.$el.dispatchEvent(e);
                    });
                }
            });
        },
        setZoom(val) {
            this.zoom = val;
            this.map.setZoom(this.zoom);
        },
        // Sets the map on all markers in the array.
        setMapOnAll(map) {
            if (this.markers) {
                for (let i = 0; i < this.markers.length; i++) {
                    this.markers[i].setMap(map);
                }
            }
        },
        // Removes the markers from the map, but keeps them in the array.
        clearMarkers() {
            this.setMapOnAll(null);
        },
    },
};
</script>
<style lang="scss">
.rounded-full {
    border-radius: 0.625rem !important;
}
</style>
