<template>
  <!--  -->
</template>

<script>
import { mapMutations, mapState } from 'vuex'

/*
|---------------------------------------------------------------------
| Toolbar Currency Component
|---------------------------------------------------------------------
|
| Quickmenu to change currency in the toolbar
|
*/
export default {
  data() {
    return {
      currencies: [{
        label: 'USD',
        decimalDigits: 2,
        decimalSeparator: '.',
        thousandsSeparator: ',',
        currencySymbol: '$',
        currencySymbolNumberOfSpaces: 0,
        currencySymbolPosition: 'left'
      }, {
        label: 'EUR',
        decimalDigits: 2,
        decimalSeparator: '.',
        thousandsSeparator: ',',
        currencySymbol: '€',
        currencySymbolNumberOfSpaces: 1,
        currencySymbolPosition: 'right'
      }]
    }
  },
  computed: {
    ...mapState('app', ['currency'])
  },
  methods: {
    ...mapMutations('app', ['setCurrency'])
  }
}
</script>
