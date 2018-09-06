<template>
<div>
    <div data-vimeo-id="19231868" data-vimeo-width="640" id="handstick"></div>
</div>
</template>


<script>
import Player from '@vimeo/player'

export default {
  mounted() {
    const player = new Player('handstick');

    player.on('play', () => {
      console.log('our video is play');
    });
  }
}
</script>

