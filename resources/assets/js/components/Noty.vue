<template>
    <div>

    </div>
</template>

<script>
import swal from 'sweetalert';

export default {
  created() {
    window.events.$on('notification', (payload) => {
      swal(payload.message);
    })
  }
}
</script>
