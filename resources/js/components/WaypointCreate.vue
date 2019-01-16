<template>
    <div>
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-map"></i>
                        Peta
                    </div>
                    <div class="card-body">
                        <gmap-map
                            ref="map"
                            @click="mapClicked"
                            @zoom_changed="saveZoom"
                            @center_changed="saveCenter"
                            :center="{lat: mapCenter.lat, lng: mapCenter.lng}"
                            :zoom="mapZoom"
                            style="width: 100%; height: 640px"
                            map-type-id="roadmap">

                            <gmap-marker
                                v-if="this.latitude && this.longitude"
                                :position="{lat: this.latitude, lng: this.longitude}"
                                icon="/png/marker_lime.png"
                                :label="{ text: name || ' ', fontSize: '14pt', fontWeight: 'bold', color: 'black'}"
                                />

                            <div v-for="point in points" :key="point.id">
                                <gmap-marker
                                    :position="{lat: point.latitude, lng: point.longitude}"
                                    :label="{ text: point.name, fontSize: '14pt', fontWeight: 'bold', color: 'black'}"
                                    icon="/png/marker_blue.png"
                                    />

                                <gmap-polyline
                                    v-for="path in point.paths_from"
                                    :key="path.point_b_id"
                                    :path="[{lat: point.latitude, lng: point.longitude}, {lat: points[path.point_b_id].latitude, lng: points[path.point_b_id].longitude }]"
                                    />
                            </div>

                        </gmap-map>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-plus"></i>
                        Data Waypoint Baru
                    </div>
                    <div class="card-body">
                        <form @submit="formSubmit">
                            <div class="form-row">
                                <div class='form-group col-md-6'>
                                    <label for='latitude'> Latitude: </label>
                                    <input
                                        v-model.number='latitude'
                                        class='form-control'
                                        :class="{'is-invalid': get(this.error_data, 'errors.latitude[0]', false)}"
                                        step="any"
                                        type='number' id='latitude' placeholder='Latitude'>
                                    <div class='invalid-feedback'>{{ get(this.error_data, 'errors.latitude[0]', false) }}</div>
                                </div>

                                <div class='form-group col-md-6'>
                                    <label for='longitude'> Longitude: </label>
                                    <input
                                        v-model.number='longitude'
                                        class='form-control'
                                        :class="{'is-invalid': get(this.error_data, 'errors.longitude[0]', false)}"
                                        step="any"
                                        type='number' id='longitude' placeholder='Longitude'>
                                    <div class='invalid-feedback'>{{ get(this.error_data, 'errors.longitude[0]', false) }}</div>
                                </div>
                            </div>

                            <div class='form-group'>
                                <label for='name'> Nama Waypoint: </label>
                                <input
                                    v-model='name'
                                    class='form-control'
                                    :class="{'is-invalid': get(this.error_data, 'errors.name[0]', false)}"
                                    type='text' id='name' placeholder='Nama Waypoint'>
                                <div class='invalid-feedback'>{{ get(this.error_data, 'errors.name[0]', false) }}</div>
                            </div>

                            <div class="form-group text-right">
                                <button class="btn btn-primary" type="submit">
                                    Tambahkan Waypoint
                                    <i class="fa fa-plus"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

    import {get} from 'lodash'
    import axios from 'axios'

    export default {

        mounted() {
            this.$refs.map.$mapPromise.then(map => {
                this.map = map
                map.setOptions({
                    styles: window.gmap_style
                });
            })
        },

        data() {
            return {
                points: window.init_points,

                // Data
                name: "",
                error_data: null,
                latitude: null,
                longitude: null,

                mapZoom: parseInt(localStorage.gmap_zoom) || 12,
                mapCenter: {
                    lat: parseFloat(localStorage.gmap_center_lat) || 0.8192948,
                    lng: parseFloat(localStorage.gmap_center_lng) || 109.4806557
                },
            }
        },

        computed: {
            form_data() {
                return {
                    name: this.name,
                    latitude: this.latitude,
                    longitude: this.longitude
                }
            }
        },

        methods: {
            get: get,
            saveZoom() {
                window.localStorage.setItem('gmap_zoom', this.map.getZoom())
            },
            saveCenter() {
                window.localStorage.setItem('gmap_center_lat', this.map.getCenter().lat())
                window.localStorage.setItem('gmap_center_lng', this.map.getCenter().lng())
            },
            mapClicked(event) {
                this.latitude = event.latLng.lat(),
                this.longitude = event.latLng.lng()
            },
            formSubmit(event) {
                event.preventDefault()
                axios.post('/waypoint/store', this.form_data)
                    .then(response => {
                        window.location.reload(true)
                    })
                    .catch(error => {
                        this.error_data = error.response.data
                    })
            }
        }
    }
</script>
