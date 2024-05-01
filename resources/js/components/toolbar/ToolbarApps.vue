<template>

</template>

<script>
import config from '../../configs'
/*
|---------------------------------------------------------------------
| Toolbar Apps Component
|---------------------------------------------------------------------
|
| Quickmenu for applications in the toolbar
|
*/
export default {
  data() {
    return {
      apps: config.toolbar.apps
    }
  },
  methods: {
    navigateTo(path) {
      if (this.$route.path !== path) this.$router.push(path)
    }
  }
}
</script>

<style lang="scss" scoped>
.app-tile {
  display: flex;
  justify-content: center;
  align-items: center;
  flex-direction: column;
  cursor: pointer;
  border-radius: 6px;
  background-color: var(--v-background-base);
  transition: transform 0.2s;

  &:hover {
    transform: scale(1.1);
  }
}
</style>
