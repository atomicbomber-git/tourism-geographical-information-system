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
                            :center="mapCenter"
                            :zoom="mapZoom"
                            @zoom_changed="saveZoom"
                            @center_changed="saveCenter"
                            style="width: 100%; height: 640px"
                            :options="{ styles: this.styles }" >

                            <div v-for="point in points" :key="point.id" >
                                <gmap-marker
                                    :position="{lat: point.latitude, lng: point.longitude}"
                                    :label="{ text: point.name, fontSize: '14pt', fontWeight: 'bold', color: 'black'}"
                                    />
                                <gmap-polyline
                                    v-for="path in point.paths_from"
                                    :key="path.point_b_id"
                                    :path="[{lat: point.latitude, lng: point.longitude}, {lat: points[path.point_b_id].latitude, lng: points[path.point_b_id].longitude }]"
                                    />
                            </div>

                            <gmap-polyline
                                v-if="start_point_id != null && end_point_id != null"
                                :options="{ strokeColor: 'red' }"
                                :path="[{lat: points[start_point_id].latitude, lng: points[start_point_id].longitude}, {lat: points[end_point_id].latitude, lng: points[end_point_id].longitude }]"
                                />

                        </gmap-map>
                    </div>
                </div>
            </div>
            <div class="col-4">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-plus"></i>
                        Tambah Jalur
                    </div>
                    <div class="card-body">
                        <form @submit="submitForm">
                            <div class="form-group">
                                <label> Titik Asal: </label>
                                <select class="form-control" v-model="start_point_id">
                                    <option v-for="point in points" :key="point.id" :value="point.id">
                                        {{ point.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label> Titik Tujuan: </label>
                                <select class="form-control" v-model="end_point_id">
                                    <option v-for="point in end_points" :key="point.id" :value="point.id">
                                        {{ point.name }}
                                    </option>
                                </select>
                            </div>
                            <div class="form-group text-right">
                                <button class="btn btn-primary">
                                    Tambahkan Jalur
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
    export default {
        mounted() {
            this.$refs.map.$mapPromise.then(map => {
                this.map = map
            })
        },

        data() {
            return {
                mapZoom: parseInt(localStorage.gmap_zoom) || 12,
                mapCenter: {
                    lat: parseFloat(localStorage.gmap_center_lat) || 0.8192948,
                    lng: parseFloat(localStorage.gmap_center_lng) || 109.4806557
                },
                styles: window.gmap_styles,
                points: init_points,
                start_point_id: null,
                end_point_id: null,
            }
        },

        computed: {
            end_points() {
                if (this.start_point_id == null) {
                    return []
                }

                return _.filter(this.points, point => {

                    if (point.id == this.start_point_id) {
                        return false
                    }

                    let should_return = false

                    this.points[this.start_point_id].paths_from.forEach(path => {
                        if (path.point_b_id == point.id) {
                            should_return = true
                            return
                        }
                    })

                    if (should_return) { return false }

                    this.points[this.start_point_id].paths_to.forEach(path => {
                        if (path.point_a_id == point.id) {
                            should_return = true
                            return
                        }
                    })

                    if (should_return) { return false }

                    return true
                })
            }
        },

        methods: {
            

            saveZoom() {
                window.localStorage.setItem('gmap_zoom', this.map.getZoom())
            },

            saveCenter() {
                window.localStorage.setItem('gmap_center_lat', this.map.getCenter().lat())
                window.localStorage.setItem('gmap_center_lng', this.map.getCenter().lng())
            },

            submitForm(event) {
                event.preventDefault()
                axios.post('/path/store', {start_point_id: this.start_point_id, end_point_id: this.end_point_id})
                    .then(response => {
                        window.location.reload(true)
                    })
                    .catch(error => {
                        alert("Something went wrong. Form submission failed.")
                    })
            }
        }
    }
</script>