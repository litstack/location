<template>
    <lit-base-field :field="field">
        <b-input ref="search" />

        <div ref="mapDiv" :style="`height: ${field.height}; width: 100%`"></div>
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
            map: null,
            marker: null,
            zoom: 4,
            options: [],
            geocoder: new google.maps.Geocoder(),
            autocomplete: null,
            queryHandler: _.debounce(this.handleQueryChange, 1000),
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
        this.initMap(this.getLocationFromModel());
        this.setMarker(this.getLocationFromModel());
    },
    methods: {
        input(val) {
            this.changed = true;
            this.setMarker(val.geometry.location);
            this.$emit('input', val);
        },
        getLocationFromModel() {
            return {
                lat: this.model[this.field.lat_key],
                lng: this.model[this.field.lng_key],
            };
        },
        setMarker(location) {
            this.map.setCenter(location);
            this.marker = new google.maps.Marker({
                map: this.map,
                position: location,
            });
            this.setZoom(15);
        },
        initMap(location) {
            const element = this.$refs.mapDiv;
            this.map = new google.maps.Map(element, {
                zoom: this.zoom,
                center: location,
            });
            this.autocomplete = new google.maps.places.Autocomplete(
                this.$refs.search.$el
            );
            google.maps.event.addListener(
                this.autocomplete,
                'place_changed',
                () => {
                    this.input(this.autocomplete.getPlace());
                }
            );
        },
        setZoom(val) {
            this.zoom = val;
            this.map.setZoom(this.zoom);
        },
    },
};
</script>
<style lang="scss">
.pac-container {
    border: 1px solid #70859c;
    border-radius: 0.625rem;
    > div {
        // border-radius: 0.625rem;
    }
}
</style>
