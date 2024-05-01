import Vue from 'vue'

Vue.filter('isjson', (value) => {

    try {
        JSON.parse(value);
    } catch (e) {
        return false;
    }
    return true;
})
