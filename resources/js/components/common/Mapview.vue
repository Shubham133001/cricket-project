<template>
    <div>
        <div id="mymapbox" style="max-width: 350px;width: 100%; height: 200px; margin-right: 60px; ">
        </div>

    </div>

</template>
<style>
.mapboxgl-ctrl-attrib {
    display: none;

}

canvas {
    border: solid 2px #fff;
}
</style>
<script>

export default {

    data: () => ({
        location: []
    }),
    mounted() {

        // this.loadmap(mapboxgl);
    },
    methods: {
        async loadmap(location) {

            var mapboxgl = require('mapbox-gl/dist/mapbox-gl.js');

            mapboxgl.accessToken = 'pk.eyJ1IjoiYW1hcnRjaHNtYXJ0ZXJzIiwiYSI6ImNsdmh1YmdnZTFiMDQyanA1ZnFzN2E0ZnEifQ.H_1x8u_XcsS6PXzKPkzB3A';
            // var map = new mapboxgl.Map({
            //     container: 'mymapbox',
            //     style: 'mapbox://styles/mapbox/streets-v11'
            // });


            // let locationdata = this.location.split(',');

            const map = new mapboxgl.Map({
                container: 'mymapbox', // Container ID
                style: 'mapbox://styles/amartchsmarters/clwri9yls013g01pn700x455p', // Map style to use
                center: [location.lng, location.lat], // Starting position [lng, lat]
                zoom: 16 // Starting zoom level
            });
            // var map = new mapboxgl.Map({
            //     container: 'mymapbox',
            //     style: 'mapbox://styles/mapbox/streets-v11'
            // });
            // var geocoder = new MapboxGeocoder({
            //     accessToken: mapboxgl.accessToken,
            //     mapboxgl: mapboxgl
            // });
            // map.addControl(geocoder);
            map.on('load', function () {

                // new mapboxgl.Marker({ color: 'black' })
                //     .setLngLat({ lng: location.lng, lat: location.lat })
                //     .addTo(map);

                // Listen for the `result` event from the Geocoder
                // `result` event is triggered when a user makes a selection
                //  Add a marker at the result's coordinates
                // geocoder.on('result', function (e) {
                //     map.getSource('single-point').setData(e.result.geometry);
                // });
            });
        },
        async getlocation(locationdata) {

            // await axios.get('https://api.mapbox.com/search/geocode/v6/forward?q=' + address + '&access_token=pk.eyJ1IjoiYW1hcnRjaHNtYXJ0ZXJzIiwiYSI6ImNsdmh1YmdnZTFiMDQyanA1ZnFzN2E0ZnEifQ.H_1x8u_XcsS6PXzKPkzB3A').then(response => {
            //     // create new instance

            //     // set the location
            //     this.location = {

            //         lat: response.data.features[0].geometry.coordinates[1],
            //         lng: response.data.features[0].geometry.coordinates[0]

            //     };
            locationdata = JSON.parse(locationdata);
            console.log(locationdata);
            this.loadmap(locationdata);
            // });

        }
    }
}
</script>