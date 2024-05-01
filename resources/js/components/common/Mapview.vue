<template>
    <div>
        <div id="mymapbox"
            style="max-width: 350px;width: 100%; height: 200px; margin-right: 60px; border: solid 2px #fff;">
        </div>

    </div>

</template>
<script>

export default {

    data: () => ({
        location: []
    }),
    mounted() {

        // this.loadmap(mapboxgl);
    },
    methods: {
        loadmap(mapboxgl) {

            var mapboxgl = require('mapbox-gl/dist/mapbox-gl.js');

            mapboxgl.accessToken = 'pk.eyJ1IjoiYW1hcnRjaHNtYXJ0ZXJzIiwiYSI6ImNsdmh1YmdnZTFiMDQyanA1ZnFzN2E0ZnEifQ.H_1x8u_XcsS6PXzKPkzB3A';
            // var map = new mapboxgl.Map({
            //     container: 'mymapbox',
            //     style: 'mapbox://styles/mapbox/streets-v11'
            // });


            // let locationdata = this.location.split(',');

            const map = new mapboxgl.Map({
                container: 'mymapbox', // Container ID
                style: 'mapbox://styles/mapbox/streets-v12', // Map style to use
                center: this.location, // Starting position [lng, lat]
                zoom: 12 // Starting zoom level
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
                map.addSource('single-point', {
                    type: 'geojson',
                    data: {
                        type: 'FeatureCollection',
                        features: []
                    }
                });

                map.addLayer({
                    id: 'point',
                    source: 'single-point',
                    type: 'circle',
                    paint: {
                        'circle-radius': 10,
                        'circle-color': '#448ee4'
                    }
                });

                // Listen for the `result` event from the Geocoder
                // `result` event is triggered when a user makes a selection
                //  Add a marker at the result's coordinates
                // geocoder.on('result', function (e) {
                //     map.getSource('single-point').setData(e.result.geometry);
                // });
            });
        },
        async getlocation(address) {

            await axios.get('https://api.mapbox.com/search/geocode/v6/forward?q=' + address + '&access_token=pk.eyJ1IjoiYW1hcnRjaHNtYXJ0ZXJzIiwiYSI6ImNsdmh1YmdnZTFiMDQyanA1ZnFzN2E0ZnEifQ.H_1x8u_XcsS6PXzKPkzB3A').then(response => {
                console.log(response, 'response');
                this.location = response.data.features[0].geometry.coordinates;
            });
            this.loadmap();
        }
    }
}
</script>