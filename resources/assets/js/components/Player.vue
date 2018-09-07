<template>
<div>
    <div :data-vimeo-id="lesson.video_id" data-vimeo-width="640" v-if="lesson" id="handstick"></div>
</div>
</template>


<script>
import axios from 'axios';
import Swal from 'sweetalert';
import Player from '@vimeo/player';

export default {
  props: ['default_lesson', 'next_lesson_url'],
  data() {
      return {
          lesson: JSON.parse(this.default_lesson),
      }
  },
  methods: {
    displayVideoEndedAlert: function() {
      if(this.next_lesson_url) {
        Swal('Yaaay ! You completed this lesson !')
          .then(() => {
            window.location = this.next_lesson_url;
          });
      } else {
        Swal('Yaaay ! You completed this series !')
          .then(() => {
            location.reload();
          });
      }
    },
    completeLesson: function() {
      axios.post(`/series/complete-lesson/${this.lesson.id}`, {

        }).then(res => {
          this.displayVideoEndedAlert();
        })
    }
  },
  mounted() {
    const player = new Player('handstick');

    player.on('play', () => {
      console.log('our video is play');
    });

    player.on('ended', () => {
      this.completeLesson();
    });

  }
}
</script>

