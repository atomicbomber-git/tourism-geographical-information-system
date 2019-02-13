<template>
    <gmap-map
        :zoom="map_zoom"
        :center="map_center"
        :options="{ styles: map_styles }"
        :style="{'padding-top': '100%', 'width': '100%', 'border': 'thin solid black'}"
        @zoom_changed="zoomChanged"
        @center_changed="centerChanged"
        @click="mapClicked"
        ref="map">

        <div v-for="point in points"
            :key="point.id">
            <gmap-marker
                :label="markerLabel(point)"
                :position="{ lat: point.latitude, lng: point.longitude }"
                :icon="markerIcon(point)"/>

            <gmap-polyline
                v-for="path in point.paths_from"
                :key="path.point_b_id"
                :path="[{lat: point.latitude, lng: point.longitude}, {lat: points[path.point_b_id].latitude, lng: points[path.point_b_id].longitude }]"/>
        </div>

    </gmap-map>
</template>

<script>
import {get} from 'lodash'

export default {

    mounted() {
        this.$refs.map.$mapPromise.then(map => {
            this.map = map
        })
    },

    data() {
        return {
            map_zoom: parseInt(localStorage.gmap_zoom) || window.gmap_config.map.zoom,

            map_center: {
                lat: parseFloat(localStorage.gmap_center_lat) || window.gmap_config.map.center.lat,
                lng: parseFloat(localStorage.gmap_center_lng) || window.gmap_config.map.center.lng
            },
            
            // HTML style
            map_style: window.gmap_config.map.style,

            map_styles: window.gmap_config.map.options.styles,

            points: window.points,

            marker_position: {
                lat: parseFloat(localStorage.gmap_center_lat) || window.gmap_config.map.center.lat,
                lng: parseFloat(localStorage.gmap_center_lng) || window.gmap_config.map.center.lng
            }
        }
    },

    methods: {
        get: get,
        getTime() { return (new Date).getTime() },

        zoomChanged() {
            window.localStorage.setItem(window.gmap_config.localstorage_keys.zoom, this.map.getZoom())
        },

        centerChanged() {
            window.localStorage.setItem(window.gmap_config.localstorage_keys.center_lat, this.map.getCenter().lat())
            window.localStorage.setItem(window.gmap_config.localstorage_keys.center_lng, this.map.getCenter().lng())
        },

        mapClicked(event) {
            this.marker_position = {
                lat: event.latLng.lat(),
                lng: event.latLng.lng()
            }
        },

        markerIcon(point) {
            switch (point.type) {
                case 'WAYPOINT':
                    return window.gmap_config.marker.icons.default;
                case 'SITE':
                    return window.gmap_config.marker.icons.site;
            }
        },

        markerLabel(point) {
            return { text: point.name, ...window.gmap_config.marker.label }
        }
    }
}
</script>

