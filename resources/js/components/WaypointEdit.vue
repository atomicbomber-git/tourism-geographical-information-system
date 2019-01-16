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
                            :center="{lat: mapCenter.lat, lng: mapCenter.lng}"
                            :zoom="mapZoom"
                            style="width: 100%; height: 640px"
                            map-type-id="roadmap">

                            <gmap-marker
                                :position="{lat: waypoint.latitude, lng: waypoint.longitude}"
                                icon="/png/marker_lime.png"
                                :label="{ text: waypoint.name || ' ', fontSize: '14pt', fontWeight: 'bold', color: 'black'}"
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
                        <i class="fa fa-pencil"></i>
                        Sunting Waypoint {{ init_waypoint.name }}
                    </div>
                    <div class="card-body">
                        <form @submit="formSubmit">
                            <div class="form-row">
                                <div class='form-group col-md-6'>
                                    <label for='latitude'> Latitude: </label>
                                    <input
                                        v-model.number='points[waypoint.id].latitude'
                                        class='form-control'
                                        step="any"
                                        :class="{'is-invalid': get(this.error_data, 'errors.latitude[0]', false)}"
                                        type='number' id='latitude' placeholder='Latitude'>
                                    <div class='invalid-feedback'>{{ get(this.error_data, 'errors.latitude[0]', false) }}</div>
                                </div>

                                <div class='form-group col-md-6'>
                                    <label for='longitude'> Longitude: </label>
                                    <input
                                        v-model.number='points[waypoint.id].longitude'
                                        class='form-control'
                                        step="any"
                                        :class="{'is-invalid': get(this.error_data, 'errors.longitude[0]', false)}"
                                        type='number' id='longitude' placeholder='Longitude'>
                                    <div class='invalid-feedback'>{{ get(this.error_data, 'errors.longitude[0]', false) }}</div>
                                </div>
                            </div>

                            <div class='form-group'>
                                <label for='name'> Nama Waypoint: </label>
                                <input
                                    v-model='points[waypoint.id].name'
                                    class='form-control'
                                    :class="{'is-invalid': get(this.error_data, 'errors.name[0]', false)}"
                                    type='text' id='name' placeholder='Nama Waypoint'>
                                <div class='invalid-feedback'>{{ get(this.error_data, 'errors.name[0]', false) }}</div>
                            </div>

                            <div class="form-group text-right">
                                <button class="btn btn-primary" type="submit">
                                    Perbarui Waypoint
                                    <i class="fa fa-pencil"></i>
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
                map.setOptions({
                    styles: window.gmap_style
                });
            })
        },

        data() {
            return {
                
                init_waypoint: window.init_waypoint,
                waypoint: window.init_waypoint,

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
                    name: this.points[this.waypoint.id].name,
                    latitude: this.points[this.waypoint.id].latitude,
                    longitude: this.points[this.waypoint.id].longitude
                }
            }
        },

        methods: {
            get: get,
            mapClicked(event) {
                this.points[this.waypoint.id].latitude = event.latLng.lat(),
                this.points[this.waypoint.id].longitude = event.latLng.lng()
            },
            formSubmit(event) {
                event.preventDefault()
                axios.put(`/waypoint/update/${this.waypoint.id}`, this.form_data)
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
