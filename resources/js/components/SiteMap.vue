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
                            @zoom_changed="onZoomChanged"
                            @center_changed="saveCenter"
                            ref="map"
                            style="width: 100%; height: 640px">

                            <div
                                v-for="point in points"
                                :key="point.id">
                                <GmapMarker
                                    v-if="showMarker(point)"
                                    @click="onPointMarkerClick(point)"
                                    :position="{lat: point.latitude, lng: point.longitude}"
                                    :label="markerLabel(point)"
                                    :icon="markerIcon(point)">

                                </GmapMarker>

                                <gmap-polyline
                                    v-show="showMarker(point)"
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
                                <option v-for="point in site_points" :key="point.id" :value="point.id">
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

        <SiteDetailModal
            name="site-detail"
            :selected_point="selected_point"
            :onClose="onCloseSiteDetailModalButtonClick"
        >
        </SiteDetailModal>
    </div>
</template>

<script>
    import SiteDetailModal from './SiteDetailModal'
    import { number_format } from '../numeral_helpers'

    export default {
        components: { SiteDetailModal },

        mounted() {
            // Store map object ref
            this.$refs.map.$mapPromise.then(map => {
                this.map = map

                this.map.addListener("bounds_changed", () => {
                    this.map_bounds = map.getBounds()
                })
            })

            this.start_point = null
            this.finish_point = null
        },


        data() {
            return {
                map_bounds: null,
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
                track: [],

                iteration: 1,
                selected_point: null,

            }
        },

        methods: {
            number_format,

            showMarker(point) {
                if (this.map_bounds === null) {
                    return false
                }

                return (
                    ((point.latitude >=  this.map_bounds.na.g) && (point.latitude <= this.map_bounds.na.h)) &&
                    ((point.longitude >= this.map_bounds.ja.g) && (point.longitude <= this.map_bounds.ja.h))
                )
            },

            showSiteDetailModal(point) {
                this.selected_point = point
                this.$modal.show("site-detail")
            },

            onCloseSiteDetailModalButtonClick() {
                this.selected_point = null
                this.$modal.hide("site-detail")
            },

            onPointMarkerClick(point) {
                if (point.type === "SITE") {
                    this.showSiteDetailModal(point)
                }

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

            onZoomChanged(e) {
                this.map_zoom = e
                this.saveZoom()
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

                /* Mereset ulang semua nilai titik yang ada */
                this.points = _.mapValues(this.points, point => {
                    return {
                        ...point,
                        visited: false,  /* Seluruh titik diset menjadi 'belum dikunjungi' */
                        tentative_dist: Infinity, /* Jarak sementara seluruh titik diset menjadi tidak terhingga */
                        is_in_track: false, /* Seluruh titik ditandai sebagai titik yang tidak termasuk 'titik jalur' */
                        prev_point: null /* Titik sebelumnya pada awal mula tidak bernilai apa-apa */
                    }
                })

                this.iteration = 1
                this.dijkstra(this.points[this.start_point], this.points[this.finish_point])
            },

            dijkstra(start, finish) {
                if (!start || !finish) {
                    return
                }

                /* Set nilai jarak sementara titik awal menjadi 0 */
                start.tentative_dist = 0
                /* Menandai titik awal sebagai titik yang telah dikunjungi */
                start.visited = true
                /* Menandai titik awal sebagai titik yang akan dipertimbangkan sekarang */
                let current_point = start

                /* Deskripsi prosedur pengunjungan sebuah titik */
                let visit = point => {
                    /*
                        Mengambil data jalur-jalur yang dapat dikunjungi dari titik sekarang /
                        Mendaftarkan seluruh titik tetangga yang belum dikunjungi
                    */
                    let visitable_paths = point.paths.filter(path => {
                        return !this.points[path.id].visited
                    })

                   /* Mengunjungi setiap titik yang terdapat pada ujung jalur */
                    visitable_paths.forEach(path => {

                        let another_point = this.points[path.id] /* Another point -> Titik tetangga */

                        /* Menghitung jarak titik yang sekarang dengan titik lainnya dengan rumus haversin */
                        const dist = this.haversineDist(
                            point.latitude, point.longitude,
                            another_point.latitude, another_point.longitude
                        )

                        /*
                            Jika jarak sementara titik sekarang + panjang jalur < jarak sementara titik lainnya,
                            maka jarak sementara titk lainnya diganti menjadi jarak sementara titik
                            sekarang + panjang jalur
                        */
                        if (point.tentative_dist + dist < another_point.tentative_dist) {
                            another_point.tentative_dist = point.tentative_dist + dist

                            /* Menandai titik sekarang sebagai 'titik sebelumnya' dari titik lainnya */
                            another_point.prev_point = point.id
                        }

                    })

                    /* Tandai titik sebagai telah dikunjungi */
                    point.visited = true
                }

                let points_array = Object.keys(this.points).map(key => this.points[key])
                this.log(`Perhitungan Dijkstra untuk Mencari Jalur Terdekat dari Titik ${start.name} dan Titik ${finish.name}`)
                this.log('Dengan Titik-Titik dan Jalur-Jalur sebagai berikut: \n')
                let report_table = {}

                points_array.forEach(pt => {
                    this.log(`${pt.name} (Latitude: ${pt.latitude}, Longitude: ${pt.longitude})`)
                    this.log("Jalur ke titik lain: ")
                    this.log(
                        pt.paths
                            .map(pth => this.points[pth.id].name)
                            .join(", ")
                    )

                    this.log("")
                })
                this.log()

                while (true) {
                    /* Memulai proses pengunjungan titik */
                    visit(current_point)

                    /*
                        Menentukan kandidat titik selanjutnya yang harus dipertimbangkan dari daftar seluruh titik yang belum dikunjungi
                    */
                    let visitables = Object.keys(this.points)
                        .map(key => { return {id: key, ...this.points[key]} }) /* Ambil daftar titik */
                        .filter(point => !point.visited) /* Keluarkan semua titik yang telah dikunjungi dari daftar */
                        .sort((point_a, point_b) => point_a.tentative_dist - point_b.tentative_dist) /* Urutkan dari titik yang jarak sementaranya terdekat */

                    /* Jika tidak ada lagi titik yang dapat dikunjungi, berhenti */
                    if (visitables.length == 0) {
                        break
                    }

                    this.log("Iterasi " + this.iteration);
                    this.log("Titik yang Belum Dikunjungi: ")
                    this.log(
                        points_array
                            .filter(point => !point.visited)
                            .map(point => point.name).join(", ")
                    )

                    this.log("Titik yang Telah Dikunjungi: ")
                    this.log(
                        points_array
                            .filter(point => point.visited)
                            .map(point => point.name).join(", ")
                        )

                    this.log("Daftar Jarak Sementara dan Titik Sebelumnya:")

                    let text_array = points_array
                        .map(point => `${point.name} -> (${point.tentative_dist == Infinity ? '∞' : point.tentative_dist}, ${point.prev_point ? this.points[point.prev_point].name : '-' })`)

                    this.log(
                        points_array
                            .map(point => `${point.name} -> (${point.tentative_dist == Infinity ? '∞' : point.tentative_dist}, ${point.prev_point ? this.points[point.prev_point].name : '-' })`)
                            .join("\n")
                        )

                    this.log("\n")

                    /* Gunakan titik pertama dari daftar titik-titik kandidat sebagai titik selanjutnya */
                    current_point = this.points[visitables[0].id]

                    ++this.iteration
                }

                let current = this.points[this.finish_point]
                this.track = []


                this.log(`Dengan hasil kalkulasi diatas dapat disimpulkan bahwa jalur terdekat dari titik ${start.name} ke titik ${finish.name} adalah:`)
                let temp = current
                let route = []
                while(true) {
                    route.push(temp.name)
                    if (temp.prev_point == null) {
                        break
                    }
                    temp = this.points[temp.prev_point]
                }
                this.log(route.reverse().join(" - ")  )

                /* Melacak kembali jalur berdasarkan data 'titik sebelumnya' yang terdapat pada titik terakhir yang dipertimbangkan */

                if (current.prev_point === null) { // Jika titik terakhir tidak memiliki titik sebelumnya (artinya jalur tidak ditemukan)
                    alert("Jalur tidak ditemukan.")
                }

                while (true) {
                    current.is_in_track = true /* Tandai titik terakhir sebagai titik yang ada di dalam jalur */
                    this.track.push(current) /* Tambahkan titik tsb. sebagai daftar titik dalam jalur */
                    if (current.prev_point == null) { break } /* Berhenti jika titik tidak memiliki titik sebelumnya */
                    current = this.points[current.prev_point] /* Set titik sebagai titik sebelumnya */
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

            markerIcon(point) {
                switch (point.type) {
                    case 'WAYPOINT':
                        return window.gmap_config.marker.icons.default;
                    case 'SITE':
                        return window.gmap_config.marker.icons.site;
                }
            },

            markerLabel(point) {
                const label = {
                    text: point.name,
                    ...window.gmap_config.marker.label
                }

                if ((point.type == 'WAYPOINT') && (this.map_zoom >= 12)) {
                    return label
                }
                else if (point.type == 'SITE') {
                    return label
                }
                else {
                    return null
                }
            },

            log(value) {

            }
        },

        computed: {
            site_points() {
                return Object.keys(this.points)
                    .filter(key => {
                        return this.points[key].type === "SITE"
                    })
                    .map(key => this.points[key])
            }
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
    }
</script>
