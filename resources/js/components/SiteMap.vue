<template>
    <div>
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-map"></i>
                        Peta Situs Pariwisata
                    </div>
                    
                    <div class="card-body">
                        <gmap-map
                            :center="{lat: map_center.lat, lng: map_center.lng}"
                            :zoom="map_zoom"
                            :options="{ styles: map_styles }"
                            @zoom_changed="saveZoom"
                            @center_changed="saveCenter"
                            ref="map"
                            style="width: 100%; height: 640px">

                            <div
                                v-for="point in points"
                                :key="point.id">
                                <GmapMarker
                                    :position="{lat: point.latitude, lng: point.longitude}"
                                    :label="{ text: point.name, fontSize: '14pt', fontWeight: 'bold', color: 'white'}"
                                    >
                                </GmapMarker>

                                <gmap-polyline
                                    v-for="path in point.paths"
                                    :key="path.id"
                                    :path="[{lat: point.latitude, lng: point.longitude}, {lat: points[path.id].latitude, lng: points[path.id].longitude}]"
                                    :options="{strokeColor: line_color(point, points[path.id])}"
                                    ref="polyline">
                                </gmap-polyline>
                            </div>

                        </gmap-map>
                    </div>
                </div>
            </div>
            <div class="col-4">

                <div class="card mb-4">
                    <div class="card-body">
                        <div class="form-group">
                            <label> Lokasi Asal: </label>
                            <select @change="update_track" v-model="start_point" class="form-control form-control-sm">
                                <option v-for="point in points" :key="point.id" :value="point.id">
                                    {{ point.name }}
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label> Lokasi Tujuan: </label>
                            <select @change="update_track" v-model="finish_point" class="form-control form-control-sm">
                                <option v-for="point in points" :key="point.id" :value="point.id">
                                    {{ point.name }}
                                </option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-road"></i>
                        Jalur Terbaik
                    </div>
                    <div class="card-body">
                        <div class="list-group list-group-flush">
                            <div class="list-group-item" v-for="point in track" :key="point.id">
                                <h5> {{ point.name }} </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        mounted() {
            // Store map object ref
            this.$refs.map.$mapPromise.then(map => {
                this.map = map
            })

            this.start_point = 1
            this.finish_point = 6

            this.dijkstra(this.points[this.start_point], this.points[this.end_point])
        },

        methods: {
            saveZoom() {
                window.localStorage.setItem('gmap_zoom', this.map.getZoom())
            },

            saveCenter() {
                window.localStorage.setItem('gmap_center_lat', this.map.getCenter().lat())
                window.localStorage.setItem('gmap_center_lng', this.map.getCenter().lng())
            },

            update_track()
            {
                this.points = _.mapValues(this.points, point => {
                    return {
                        ...point,
                        // For Dijkstra purpose
                        visited: false,
                        tentative_dist: Infinity,
                        is_in_track: false,
                        prev_point: null
                    }
                })

                this.dijkstra(this.points[this.start_point], this.points[this.end_point])
            },

            dijkstra(start, finish) {
                // Reset
                start.tentative_dist = 0
                start.visited = true
                let current_point = start

                while (true) {

                    let visitable_paths = current_point.paths.filter(path => {
                        return this.points[path.id].visited == false
                    })

                    // console.table(_.mapValues(this.points))
                    // console.log(this.points[1].visited)

                    if (visitable_paths.length == 0) {
                        current_point.visited = true
                        break;
                    }
                
                    visitable_paths.forEach(path => {
                        if (current_point.tentative_dist + this.haversineDist(this.points[path.id].latitude, this.points[path.id].longitude, current_point.latitude, current_point.longitude) < this.points[path.id].tentative_dist) {
                            this.points[path.id].tentative_dist =
                                current_point.tentative_dist +
                                this.haversineDist(this.points[path.id].latitude, this.points[path.id].longitude, current_point.latitude, current_point.longitude)
                            this.points[path.id].prev_point = current_point.id
                            // console.log(`Setting ${this.points[path.id].name}'s distance to ${this.points[path.id].tentative_dist}`)
                        }
                    })

                    current_point.visited = true
                    // console.log(`Setting ${current_point.name} to VISITED`)

                    // Determine next point to be visited
                    let next_point_id = _.minBy(visitable_paths, path => {
                        return this.points[path.id].tentative_dist
                    }).id

                    current_point = this.points[next_point_id]
                }

                // Generate best track from Dijksta algorithm's result
                
                let current = this.points[this.finish_point]
                this.track = []
                
                while (true) {
                    current.is_in_track = true
                    this.track.push(current)
                    if (current.prev_point == null) { break }
                    current = this.points[current.prev_point]
                }

                this.track = _.reverse(this.track)
            },

            line_color(point_a, point_b) {
                if (point_a.is_in_track && point_b.is_in_track) { return 'red' }
                return 'black'
            },

            icon(point_type) {
                if (point_type == "SITE") { return "/png/marker_green.png" }
                return "/png/marker_red.png"
            },

            haversineDist(lat1,lon1,lat2,lon2) {
                let R = 6371 // Radius of the earth in km
                let dLat = this.deg2rad(lat2-lat1)  // deg2rad below
                let dLon = this.deg2rad(lon2-lon1) 
                let a = 
                    Math.sin(dLat/2) * Math.sin(dLat/2) +
                    Math.cos(this.deg2rad(lat1)) * Math.cos(this.deg2rad(lat2)) * 
                    Math.sin(dLon/2) * Math.sin(dLon/2); 
                let c = 2 * Math.atan2(Math.sqrt(a), Math.sqrt(1-a)); 
                let d = R * c;
                return d;
            },

            deg2rad(deg) {
                return deg * (Math.PI/180)
            }
        },

        data() {
            return {

                map_zoom: parseInt(localStorage.gmap_zoom) || 12,
                map_center: {
                    lat: parseFloat(localStorage.gmap_center_lat) || 0.8192948,
                    lng: parseFloat(localStorage.gmap_center_lng) || 109.4806557
                },

                map_styles: window.gmap_styles,

                points: _.mapValues(window.init_points, point => {
                    return {
                        ...point,
                        // For Dijkstra purpose
                        visited: false,
                        tentative_dist: Infinity,
                        is_in_track: false,
                        prev_point: null
                    }
                }),

                start_point: null,
                finish_point: null,
                track: []
            }
        }
    }
</script>