<template>

</template>

<script>
import FlagIcon from '../common/FlagIcon'

/*
|---------------------------------------------------------------------
| Language Switcher Component
|---------------------------------------------------------------------
|
| Locale menu to choose the language based on the locales present in
| vue-i18n locales available array
|
*/
export default {
  components: {
    FlagIcon
  },
  props: {
    // Show dropdown arrow
    showArrow: {
      type: Boolean,
      default: false
    },
    // Show the country label
    showLabel: {
      type: Boolean,
      default: true
    }
  },
  computed: {
    currentLocale() {
      return this.$i18n.locales.find((i) => i.code === this.$i18n.locale)
    },
    availableLocales () {
      return this.$i18n.locales.filter((i) => i.code !== this.$i18n.locale)
    }
  },
  methods: {
    setLocale(locale) {
      this.$i18n.locale = locale
      this.$vuetify.lang.current = locale

      // example on how certain languages can be RTL
      if (locale === 'ar') {
        this.$vuetify.rtl = true
      } else {
        this.$vuetify.rtl = false
      }
    }
  }
}
</script>
