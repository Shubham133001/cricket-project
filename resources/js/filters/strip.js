import Vue from 'vue'

Vue.filter('strip', (value) => {
  if (!value) return ''
  if(value.length < 150) return value.substring(0, 150);
  return value.substring(0, 150)+'...';
})