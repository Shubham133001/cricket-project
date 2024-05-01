<template>

</template>

<script>
/*
|---------------------------------------------------------------------
| Toolbar Notifications Component
|---------------------------------------------------------------------
|
| Quickmenu to check out notifications
|
*/
export default {
  data() {
    return {
      items: [
        {
          title: 'Brunch this weekend?',
          color: 'primary',
          icon: 'mdi-account-circle',
          subtitle: 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Sint, repudiandae?',
          time: '3 min'
        },
        {
          title: 'Summer BBQ',
          color: 'success',
          icon: 'mdi-email-outline',
          subtitle: 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Sint, repudiandae?',
          time: '3 min'
        },
        {
          title: 'Oui oui',
          color: 'teal lighten-1',
          icon: 'mdi-airplane-landing',
          subtitle: 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Sint, repudiandae?',
          time: '4 min'
        },
        {
          title: 'Disk capacity is at maximum',
          color: 'teal accent-3',
          icon: 'mdi-server',
          subtitle: 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Sint, repudiandae?',
          time: '3 hr'
        },
        {
          title: 'Recipe to try',
          color: 'blue-grey lighten-2',
          icon: 'mdi-noodles',
          subtitle: 'Lorem ipsum dolor sit amet consectetur, adipisicing elit. Sint, repudiandae?',
          time: '8 hr'
        }
      ]
    }
  }
}
</script>
