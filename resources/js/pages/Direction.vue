<template>
    <div style="height: 550px;">
        <div id="mymapbox1" style="width: 100%; height: 550px;">
        </div>

    </div>

</template>
<style>
.mapboxgl-ctrl-attrib {
    display: none;

}
</style>
<script>

export default {

    data: () => ({
        location: []
    }),
    created() {
        // create scipt and link tags
        const mapboxglcss = document.createElement("link");
        mapboxglcss.setAttribute("rel", "stylesheet");
        mapboxglcss.setAttribute(
            "href",
            "https://api.mapbox.com/mapbox-gl-js/v3.4.0/mapbox-gl.css"
        );
        document.head.appendChild(mapboxglcss);
        // const gljs = document.createElement("script");
        // gljs.setAttribute(
        //     "src",
        //     "https://api.mapbox.com/mapbox-gl-js/v3.4.0/mapbox-gl.js"
        // );
        // gljs.async = true;
        // document.head.appendChild(gljs);
        // load js from url and use it
        const plugin = document.createElement("script");
        plugin.setAttribute(
            "src",
            "//api.mapbox.com/search-js/v1.0.0-beta.21/web.js"
        );
        plugin.async = true;
        document.head.appendChild(plugin);

        // add geocoder
        const geocoder = document.createElement("script");
        geocoder.setAttribute(
            "src",
            "https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.min.js"
        );
        geocoder.async = true;
        document.head.appendChild(geocoder);

        // add geocoder css
        const geocodercss = document.createElement("link");
        geocodercss.setAttribute("rel", "stylesheet");
        geocodercss.setAttribute(
            "href",
            "https://api.mapbox.com/mapbox-gl-js/plugins/mapbox-gl-geocoder/v5.0.0/mapbox-gl-geocoder.css"
        );
        document.head.appendChild(geocodercss);


    },
    mounted() {
        this.getlocation([30.70489843716473, 76.71770933013812]);
    },
    methods: {
        async loadmap(geometydata) {

            var mapboxgl = require('mapbox-gl/dist/mapbox-gl.js');

            mapboxgl.accessToken = 'pk.eyJ1IjoiYW1hcnRjaHNtYXJ0ZXJzIiwiYSI6ImNsdmh1YmdnZTFiMDQyanA1ZnFzN2E0ZnEifQ.H_1x8u_XcsS6PXzKPkzB3A';
            const map = new mapboxgl.Map({
                container: 'mymapbox1', // container id
                // Choose from Mapbox's core styles, or make your own style with Mapbox Studio
                style: 'mapbox://styles/amartchsmarters/clwri9yls013g01pn700x455p',
                center: [this.$route.query.lng, this.$route.query.lat], // starting position
                zoom: 16 // starting zoom
            });
            let marker = new mapboxgl.Marker({ color: 'black' })

            map.addControl(new mapboxgl.NavigationControl());
            const geolocation = new mapboxgl.GeolocateControl({
                positionOptions: {
                    enableHighAccuracy: true
                },
                // When active the map will receive updates to the device's location as it changes.
                trackUserLocation: true,
                // Draw an arrow next to the location dot to indicate which direction the device is heading.
                showUserHeading: true
            })
            map.addControl(geolocation);
            marker.setLngLat([this.$route.query.lng, this.$route.query.lat])
                .addTo(map);
            geolocation.on('geolocate', function (e) {
                const coords = e.coords;
                map.center = [coords.longitude, coords.latitude];
                this.getlocation([coords.longitude, coords.latitude]);
                map.addSource('route', {
                    type: 'geojson',
                    data: {
                        type: 'Feature',
                        properties: {},
                        geometry: geometydata
                    }
                });

                map.addLayer({
                    id: 'route',
                    source: 'route',
                    type: 'line',
                    paint: {
                        'line-width': 6,
                        'line-color': '#007cbf'
                    }
                });

            });
            map.on('load', function () {
                geolocation.trigger();
            });


        },
        async getlocation(locationdata1) {
            let locationdata = {
                lat: this.$route.query.lat,
                lng: this.$route.query.lng
            };
            await axios.get('https://api.mapbox.com/directions/v5/mapbox/driving/' + locationdata1[1] + ',' + locationdata1[0] + ';' + this.$route.query.lng + ',' + this.$route.query.lat + '?geometries=geojson&access_token=pk.eyJ1IjoiYW1hcnRjaHNtYXJ0ZXJzIiwiYSI6ImNsdmh1YmdnZTFiMDQyanA1ZnFzN2E0ZnEifQ.H_1x8u_XcsS6PXzKPkzB3A').then(response => {
                // create new instance

                // set the location
                // this.location = {

                //     lat: response.data.features[0].geometry.coordinates[1],
                //     lng: response.data.features[0].geometry.coordinates[0]

                // };
                console.log(response.data.routes);
                this.loadmap(response.data.routes[0].geometry);
            });

        }
    }
}
</script>