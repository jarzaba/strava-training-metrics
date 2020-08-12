<template>
    <div class="container">
        <l-map
            :zoom="zoom"
            :center="center"
            :options="mapOptions"
            style="height: 500px"
            @update:center="centerUpdate"
            @update:zoom="zoomUpdate"
        >
            <l-tile-layer :url="url" :attribution="attribution" />
        </l-map>
    </div>
</template>

<script>
// If you need to reference 'L', such as in 'L.icon', then be sure to
// explicitly import 'leaflet' into your component
import L from "leaflet";
import { latlng } from "leaflet";
import { LMap, LTileLayer, LMarker } from "vue2-leaflet";

export default {
    name: "ActivityMap",
    components: {
        LMap,
        LTileLayer,
        LMarker
    },
    props: {
        activities: {
            type: Array
        }
    },
    data() {
        return {
            zoom: 13,
            center: latlng(61, 25),
            url:
                "https://avoin-karttakuva.maanmittauslaitos.fi/avoin/wmts/1.0.0/maastokartta/default/ETRS-TM35FIN/{z}/{y}/{x}.png",
            attribution:
                '&copy; Karttamateriaali <a href="http://www.maanmittauslaitos.fi/avoindata">Maanmittauslaitos</a>',
            currentZoom: 11.5,
            currentCenter: latlng(61, 25),
            showParagraph: false,
            mapOptions: {
                zoomSnap: 0.5
            },
            showMap: true
        };
    },
    methods: {
        zoomUpdate(zoom) {
            this.currentZoom = zoom;
        },
        centerUpdate(center) {
            this.currentCenter = center;
        }
    },
    computed: {},
    mounted() {
        //const activities = this.activities;
        console.log("Map component mounted");
    }
};
</script>
