import Vue from 'vue'
import moment from 'moment'

/**
 * Date library momentjs
 * https://momentjs.com/
 * 
 * @param {String} date
 * @param {String} format
 * @returns {String}
 * 
 * Usage:
 * this.$formatDate('2020-01-01', 'YYYY-MM-DD')
 * this.$formatDate('2020-01-01', 'YYYY-MM-DD HH:mm:ss')
 * this.$formatDate('2020-01-01', 'YYYY-MM-DD HH:mm:ss', 'YYYY-MM-DD')
 * this.$formatDate('2020-01-01', 'YYYY-MM-DD HH:mm:ss', 'YYYY-MM-DD HH:mm:ss')
 * this.$formatDate('2020-01-01', 'YYYY-MM-DD HH:mm:ss', 'YYYY-MM-DD HH:mm:ss', 'YYYY-MM-DD')
 * this.$formatDate('2020-01-01', 'YYYY-MM-DD HH:mm:ss', 'YYYY-MM-DD HH:mm:ss', 'YYYY-MM-DD HH:mm:ss')
 * this.$formatDate('2020-01-01', 'YYYY-MM-DD HH:mm:ss', 'YYYY-MM-DD HH:mm:ss', 'YYYY-MM-DD HH:mm:ss', 'YYYY-MM-DD')
 * this.$formatDate('2020-01-01', 'YYYY-MM-DD HH:mm:ss', 'YYYY-MM-DD HH:mm:ss', 'YYYY-MM-DD HH:mm:ss', 'YYYY-MM-DD HH:mm:ss')
 * this.$formatDate('2020-01-01', 'YYYY-MM-DD HH:mm:ss', 'YYYY-MM-DD HH:mm:ss', 'YYYY-MM-DD HH:mm:ss', 'YYYY-MM-DD HH:mm:ss', 'YYYY-MM-DD')
 * 
 * this.$formatDate('2020-01-01', 'YYYY-MM-DD').format('YYYY-MM-DD')
 **/

Vue.prototype.$formatDate = (date) => {
    if (format1) {
        return moment(date, format).format(format1)
    }
    if (format2) {
        return moment(date, format).format(format2)
    }
    if (format3) {
        return moment(date, format).format(format3)
    }
    if (format4) {
        return moment(date, format).format(format4)
    }
    if (format5) {
        return moment(date, format).format(format5)
    }
    return moment(date, format)
}


