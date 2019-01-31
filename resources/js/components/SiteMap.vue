<template>
    <div>
        <div class="row">
            <div class="col-8">
                <div class="card">
                    <div class="card-header">
                        <i class="fa fa-map"></i>
                        Peta Situs Wisata
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
                                    @click="onPointMarkerClick(point)"
                                    :position="{lat: point.latitude, lng: point.longitude}"
                                    :label="markerLabel(point)"
                                    :icon="markerIcon(point)">

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
                            <select v-model="start_point" class="form-control form-control-sm">
                                <option v-for="point in points" :key="point.id" :value="point.id">
                                    {{ point.name }}
                                </option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label> Lokasi Tujuan: </label>
                            <select v-model="finish_point" class="form-control form-control-sm">
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
                    <div class="card-body" style="max-height: 20rem; overflow-y: scroll">
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
            onPointMarkerClick(point) {
                if (this.start_point == null) {
                    this.start_point = point.id
                }
                else if (this.finish_point == null) {
                    this.finish_point = point.id
                }
                else {
                    this.start_point = null
                    this.finish_point = null
                    this.start_point = point.id
                }
            },

            saveZoom() {
                window.localStorage.setItem('gmap_zoom', this.map.getZoom())
            },

            saveCenter() {
                window.localStorage.setItem('gmap_center_lat', this.map.getCenter().lat())
                window.localStorage.setItem('gmap_center_lng', this.map.getCenter().lng())
            },

            update_track()
            {
                if ((this.start_point == null) || (this.finish_point == null)) {
                    return
                }

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

                // console.log(`Mencari rute terbaik dari titik "${start.name}" ke "${finish.name}"\n`)

                let visit = point => {
                    
                    let visitable_paths = point.paths.filter(path => {
                        return !this.points[path.id].visited
                    })

                    console.log(`Mengunjungi titik "${point.name}"`)

                    let visitable_path_names = visitable_paths
                        .map(path => `"${this.points[path.id].name}"`)
                        .join(", ")

                    console.log("Titik-titik tetangga: " + visitable_path_names)
                    
                    visitable_paths.forEach(path => {
                        
                        let another_point = this.points[path.id]
                        
                        // Calculate distance
                        const dist = this.haversineDist(
                            point.latitude, point.longitude,
                            another_point.latitude, another_point.longitude
                        )

                        console.log(`Jarak sebenarnya antara titik ini dengan titik "${another_point.name}": ${dist} KM`)
                        console.log(`Jarak sementara titik ini: ${this.tentative_dist} KM`)
                        console.log(`Jarak sementara titik "${another_point.name}": "${another_point.tentative_dist}" KM`)

                        if (point.tentative_dist + dist < another_point.tentative_dist) {
                            another_point.tentative_dist = point.tentative_dist + dist 
                            another_point.prev_point = point.id
                            console.log(`Karena jarak sementara titik ini + jarak sebenarnya antar titik ini dengan titik "${another_point.name} (${point.tentative_dist + dist} KM)" < Jarak sementara titik "${another_point.name}", maka jarak sementara titik "${another_point.name}" diubah menjadi ${point.tentative_dist + dist} KM`)
                            console.log(`Titik sebelumnya dari titik "${another_point.name}" diubah menjadi "${point.name}"`)
                        }

                    })
                    
                    point.visited = true
                }

                while (true) {
                    visit(current_point)

                    let visitables = Object.keys(this.points)
                        .map(key => { return {id: key, ...this.points[key]} })
                        .filter(point => !point.visited)
                        .sort((point_a, point_b) => point_a.tentative_dist - point_b.tentative_dist)
                    
                    let visitable_names = visitables.map(point => `"${point.name}"`).join(", ")

                    console.log("Titik-titik yang belum dikunjungi, diurutkan berdasarkan jarak sementara dari yang terdekat hingga yang terjauh: ")
                    console.log(visitable_names)

                    if (visitables.length == 0) {
                        break
                    }

                    console.log("");

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
                console.log("Rute (Dihitung mulai dari titik tujuan): ")
                console.log(this.track.map(point => `"${point.name}"`).join(", "))

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
        },

        watch: {
            start_point: function() {
                if ((this.start_point != null) && (this.finish_point != null)) {
                    this.update_track()
                }
            },

            finish_point: function() {
                if ((this.start_point != null) && (this.finish_point != null)) {
                    this.update_track()
                }
            }
        },

        data() {
            return {

                map_zoom: parseInt(localStorage.gmap_zoom) || 12,
                map_center: {
                    lat: parseFloat(localStorage.gmap_center_lat) || 0.8192948,
                    lng: parseFloat(localStorage.gmap_center_lng) || 109.4806557
                },

                map_styles: window.gmap_config.map.options.styles,

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