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
                            :center="{lat: 0.8192948, lng: 109.4806557}"
                            :zoom="11"
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
                        <div class="form-group">
                            <label> Titik Awal: </label>
                            <select class="form-control" v-model="start_point_id">
                                <option v-for="point in points" :key="point.id" :value="point.id">
                                    {{ point.name }}
                                </option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label> Titik Tujuan: </label>
                            <select class="form-control">
                                <option v-for="point in end_points" :key="point.id">
                                    {{ point.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</template>

<script>
    export default {
        data() {
            return {
                styles: window.gmap_styles,
                points: init_points,
                start_point_id: null,
            }
        },

        computed: {
            end_points() {
                if (this.start_point_id == null) {
                    return []
                }

                console.log("Test")

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

        mounted() {
            console.log('Component mounted.')
        }
    }
</script>