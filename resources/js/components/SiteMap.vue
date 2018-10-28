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
                                    :label="{ text: point.name, fontSize: '14pt', fontWeight: 'bold', color: 'black'}"
                                    icon="/png/marker_blue.png"
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

                let visit = point => {
                    
                    let visitable_paths = point.paths.filter(path => {
                        return !this.points[path.id].visited
                    })

                    visitable_paths.forEach(path => {
                        
                        let another_point = this.points[path.id]
                        
                        // Calculate distance
                        const dist = this.haversineDist(
                            point.latitude, point.longitude,
                            another_point.latitude, another_point.longitude
                        )

                        if (point.tentative_dist + dist < another_point.tentative_dist) {
                            another_point.tentative_dist = point.tentative_dist + dist 
                            another_point.prev_point = point.id
                        }
                    })
                    
                    console.log(point.name)
                    point.visited = true
                }

                while (true) {
                    visit(current_point)

                    let visitables = Object.keys(this.points)
                        .map(key => { return {id: key, ...this.points[key]} })
                        .filter(point => !point.visited)
                        .sort((point_a, point_b) => point_a.tentative_dist - point_b.tentative_dist)

                    if (visitables.length == 0) {
                        break
                    }

                    current_point = this.points[visitables[0].id]
                }

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
            },

            // markerClick(point) {
            //     if (this.start_point == null) {
            //         this.start_point = point.id
            //         return
            //     }

            //     if (this.start_point == point.id) {
            //         this.start_point = null
            //     }

            //     if (this.end_point == null) {
            //         if (this.end_points.find(ep => ep.id == point.id) !== undefined) {
            //             this.end_point = point.id
            //         }
            //         return
            //     }

            //     if (this.end_point == point.id) {
            //         this.end_point = null
            //     }

            //     this.start_point = point.id
            //     this.end_point = null
            // },
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