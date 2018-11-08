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
                            :zoom="map_zoom"
                            :center="map_center"
                            :options="{ styles: map_styles }"
                            :style="map_style"
                            @zoom_changed="zoomChanged"
                            @center_changed="centerChanged"
                            @click="mapClicked"
                            ref="map">

                            <gmap-marker
                                :position="marker_position"
                                :icon="marker_icon"
                                :label="pointerMarkerLabel()"/>

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
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-plus"></i>
                        Tambah Tempat Wisata
                    </div>

                    <div class="card-body">
                        <form @submit="formSubmitted">
                            <div class="form-row">
                                <div class='form-group col-md-6'>
                                    <label for='latitude'> Latitude: </label>
                                    <input
                                        readonly
                                        v-model='marker_position.lat'
                                        class='form-control'
                                        :class="{'is-invalid': get(this.error_data, 'errors.latitude[0]', false)}"
                                        type='text' id='latitude' placeholder='Latitude'>
                                    <div class='invalid-feedback'>{{ get(this.error_data, 'errors.latitude[0]', false) }}</div>
                                </div>
                                <div class='form-group col-md-6'>
                                    <label for='longitude'> Longitude: </label>
                                    <input
                                        readonly
                                        v-model='marker_position.lng'
                                        class='form-control'
                                        :class="{'is-invalid': get(this.error_data, 'errors.longitude[0]', false)}"
                                        type='text' id='longitude' placeholder='Longitude'>
                                    <div class='invalid-feedback'>{{ get(this.error_data, 'errors.longitude[0]', false) }}</div>
                                </div>
                            </div>

                            <div class='form-group'>
                                <label for='name'> Nama: </label>
                                <input
                                    v-model='name'
                                    class='form-control'
                                    :class="{'is-invalid': get(this.error_data, 'errors.name[0]', false)}"
                                    type='text' id='name' placeholder='Nama'>
                                <div class='invalid-feedback'>{{ get(this.error_data, 'errors.name[0]', false) }}</div>
                            </div>

                            <div class='form-group'>
                                <label for='visitor_count'> Jumlah Pengunjung: </label>
                                <input
                                    v-model='visitor_count'
                                    class='form-control'
                                    :class="{'is-invalid': get(this.error_data, 'errors.visitor_count[0]', false)}"
                                    type='number' id='visitor_count' placeholder='Jumlah Pengunjung'>
                                <div class='invalid-feedback'>{{ get(this.error_data, 'errors.visitor_count[0]', false) }}</div>
                            </div>

                            <div class='form-group'>
                                <label for='fee'> Harga Tiket Masuk: </label>
                                <input
                                    v-model='fee'
                                    class='form-control'
                                    :class="{'is-invalid': get(this.error_data, 'errors.fee[0]', false)}"
                                    type='number' id='fee' placeholder='Harga Tiket Masuk'>
                                <div class='invalid-feedback'>{{ get(this.error_data, 'errors.fee[0]', false) }}</div>
                            </div>

                            <div class='form-group'>
                                <label for='facility_count'> Jumlah Fasilitas: </label>
                                <input
                                    v-model='facility_count'
                                    class='form-control'
                                    :class="{'is-invalid': get(this.error_data, 'errors.facility_count[0]', false)}"
                                    type='number' id='facility_count' placeholder='Jumlah Fasilitas'>
                                <div class='invalid-feedback'>{{ get(this.error_data, 'errors.facility_count[0]', false) }}</div>
                            </div>

                            <div class="form-group">
                                <label for="site_category_id"> Kategori: </label>
                                <select
                                    :class="{'is-invalid': get(this.error_data, 'errors.site_category_id[0]', false)}"
                                    class="form-control"
                                    v-model="site_category_id">
                                    <option v-for="category in categories" :key="category.id" :value="category.id">
                                        {{ category.name }}
                                    </option>
                                </select>
                                <div class='invalid-feedback'>{{ get(this.error_data, 'errors.site_category_id[0]', false) }}</div>
                            </div>

                            <div class='form-group'>
                                <label for='description'> Deskripsi: </label>
                                <textarea
                                    v-model='description'
                                    class='form-control'
                                    :class="{'is-invalid': get(this.error_data, 'errors.description[0]', false)}"
                                    type='text' id='description' placeholder='Deskripsi'></textarea>
                                <div class='invalid-feedback'>{{ get(this.error_data, 'errors.description[0]', false) }}</div>
                            </div>

                            <div class="form-group">
                                <label for="image"> Gambar: </label>
                                <input
                                    :class="{'is-invalid': get(this.error_data, 'errors.image[0]', false)}"
                                    ref="image" type="file" class="form-control" name="image" accept="img/*">
                                <div class='invalid-feedback'>{{ get(this.error_data, 'errors.image[0]', false) }}</div>
                            </div>

                            <div class="form-group text-right">
                                <button type="submit" class="btn btn-primary">
                                    Tambahkan Situs Wisata
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

    export default {
        methods: {
            get: get,

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
            },

            pointerMarkerLabel(name) {
                return { text: this.name || ' ', ...window.gmap_config.marker.label }
            },

            formSubmitted(event) {

                let data = new FormData()
                Object.keys(this.form_data).forEach(key => {
                    data.append(key, this.form_data[key])
                })
                data.append('image', this.$refs.image.files[0])

                event.preventDefault()
                axios.post(`/site/store`, data, {headers: { 'Content-Type': 'multipart/form-data' }})
                    .then(response => { window.location.reload(true) })
                    .catch(error => { this.error_data = error.response.data })
            }
        },

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

                marker_position: {
                    lat: parseFloat(localStorage.gmap_center_lat) || window.gmap_config.map.center.lat,
                    lng: parseFloat(localStorage.gmap_center_lng) || window.gmap_config.map.center.lng
                },

                marker_icon: window.gmap_config.marker.icons.active,
                points: window.init_points,
                error_data: null,

                name: '',
                visitor_count: null,
                fee: null,
                facility_count: null,
                site_category_id: null,
                description: ''
            }
        },

        computed: {
            categories() {
                return window.categories;
            },

            form_data() {
                return {
                    name: this.name,
                    latitude: this.marker_position.lat,
                    longitude: this.marker_position.lng,
                    visitor_count: this.visitor_count,
                    fee: this.fee,
                    facility_count: this.facility_count,
                    site_category_id: this.site_category_id,
                    description: this.description
                }
            }
        }
    }
</script>
