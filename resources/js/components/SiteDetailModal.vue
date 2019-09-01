<template>
  <modal :name="name" height="auto">
        <div class="p-4" style="position: relative" v-if="selected_point">
            <div class="text-right" style="position: absolute; top: 10px; right: 10px;">
                <button class="btn btn-default" @click="onClose">
                    <i class="fa fa-times"></i>
                </button>
            </div>

            <h1 class="h2 mb-3">
                {{ selected_point.name }}
            </h1>

            <dl>
                <dt> Jumlah Pengunjung </dt>
                <dd> {{ number_format(selected_point.site.visitor_count) }} </dd>

                <dt> Harga Tiket Masuk </dt>
                <dd> {{ number_format(selected_point.site.fee) }} </dd>

                <dt> Jumlah Fasilitas </dt>
                <dd> {{ number_format(selected_point.site.facility_count) }} </dd>
            </dl>

            <p>
                {{ selected_point.site.description }}
            </p>
        </div>
    </modal>
</template>

<script>

import { number_format } from "../numeral_helpers"

export default {
    props: [
        "name", "selected_point", "onClose"
    ],

    methods: {
        number_format,
    }
}
</script>
