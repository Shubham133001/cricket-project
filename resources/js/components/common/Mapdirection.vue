<template>
    <div>
        <div id="mymapbox1" style="width: 100%; height: 100%;">
        </div>

    </div>

</template>
<style></style>
<script>

export default {

    data: () => ({
        location: []
    }),
    mounted() {
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
    methods: {
        async loadmap(location) {

            var mapboxgl = require('mapbox-gl/dist/mapbox-gl.js');

            mapboxgl.accessToken = 'pk.eyJ1IjoiYW1hcnRjaHNtYXJ0ZXJzIiwiYSI6ImNsdmh1YmdnZTFiMDQyanA1ZnFzN2E0ZnEifQ.H_1x8u_XcsS6PXzKPkzB3A';
            const map = new mapboxgl.Map({
                container: 'map', // container id
                // Choose from Mapbox's core styles, or make your own style with Mapbox Studio
                style: 'mapbox://styles/amartchsmarters/clwri9yls013g01pn700x455p',
                center: [78.08312224925453, 26.69108073495788], // starting position
                zoom: 4 // starting zoom
            });
            const geolocation = new mapboxgl.GeolocateControl({
                positionOptions: {
                    enableHighAccuracy: true
                },
                // When active the map will receive updates to the device's location as it changes.
                trackUserLocation: true,
                // Draw an arrow next to the location dot to indicate which direction the device is heading.
                showUserHeading: true
            })


            // load js from url and use it

            const searchBox = new MapboxSearchBox();
            searchBox.accessToken = 'pk.eyJ1IjoiYW1hcnRjaHNtYXJ0ZXJzIiwiYSI6ImNsdmh1YmdnZTFiMDQyanA1ZnFzN2E0ZnEifQ.H_1x8u_XcsS6PXzKPkzB3A';
            searchBox.options = {
                types: 'address,poi',
                // proximity: [-73.99209, 40.68933],
                language: 'en',
                country: 'IN',
            };

            searchBox.marker = false;
            searchBox.mapboxgl = mapboxgl;


            map.addControl(geolocation);
            map.addControl(searchBox);
            document.getElementById('searchbox').appendChild(searchBox.onAdd(map));
            searchBox.placeholder = 'Enter Full Location';
            // searchBox.value = this.category.location;
            let marker = new mapboxgl.Marker({ color: 'black' })
            map.on('click', (e) => {
                console.log(e)
                marker.setLngLat(e.lngLat)
                    .addTo(map)

                axios.get('https://api.mapbox.com/search/geocode/v6/reverse?longitude=' + e.lngLat.lng + '&latitude=' + e.lngLat.lat + '&access_token=' + mapboxgl.accessToken + '').then(response => {

                    searchBox.value = response.data.features[0].properties.full_address;
                    this.category.location = response.data.features[0].properties.full_address;

                })

                this.locationdata = JSON.stringify({ lng: e.lngLat.lng, lat: e.lngLat.lat });
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
            console.log(locationdata, 'locdata');
            this.loadmap(locationdata);
            // });

        }
    }
}
</script>