<template>
    <div>
        <div class="form-group">
            <label for="point_a_id"> Titik Awal: </label>
            <multiselect
                placeholder="Point A"
                :allow-empty="false"
                selectLabel=""
                deselectLabel=""
                track-by="id"
                label="name"
                :options="points"
                v-model="point_a"
                :preselect-first="true"
            ></multiselect>
        </div>

        <div class="form-group">
            <label for="point_b_id"> Titik Akhir: </label>
            <multiselect
                placeholder="Point B"
                :allow-empty="false"
                selectLabel=""
                deselectLabel=""
                track-by="id"
                label="name"
                :options="points"
                v-model="point_b"
            ></multiselect>
        </div>
    </div>
</template>

<script>

import Multiselect from 'vue-multiselect'
import { keyBy } from 'lodash'

export default {
    props: [
        "points",
    ],

    components: { Multiselect },

    data() {
        return {
            keyed_points: keyBy(this.points, "id"),
            point_a: null,
            point_b: null,
        }
    },

    watch: {
        point_a() {
            dijkstra(this.point_a, this.point_b)
        },

        point_b() {
            dijkstra(this.point_a, this.point_b)
        }
    },

    computed() {

    },

    methods: {
        dijkstra(point_a, point_b) {

        }
    },
}
</script>
