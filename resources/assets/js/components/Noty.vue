<template>
    <div class="alert alert-success"
        :class="type"
        v-show="notification">
        <p class="text-center">
            {{ notification.message }}
        </p>
    </div>
</template>

<style>
    .alerty-noty {
       position: fixed;
       right: 20px;
       bottom: 40px;
       z-index: 1;

    }
</style>


<script>
export default {
  data() {
    return {
      notification: '',
    }
  },
  computed: {
    type: function() {
      return `alert-${this.notification.type}`;
    }
  },
  created() {
    window.events.$on('notification', (payload) => {
      this.notification = payload;
      setTimeout(() => {
        this.notification = ''
      }, 2500)
    })
  }
}
</script>
