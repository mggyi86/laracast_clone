<template>
<div class="modal fade" id="createLesson" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Create new lesson</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Lesson title"
                    v-model="title">
                </div>
                <div class="form-group">
                    <input type="text" class="form-control" placeholder="Vimeo vidoe id"
                    v-model="video_id">
                </div>
                <div class="form-group">
                    <input type="number" class="form-control" placeholder="Episode number"
                    v-model="episode_number">
                </div>
                <div class="form-group">
                    <textarea v-model="description" cols="30" rows="10" class="form-control"></textarea>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" @click="createLesson">Save lesson</button>
            </div>
        </div>
    </div>
</div>
</template>


<script>
import axios from 'axios';

export default {
  data() {
    return {
      title: '',
      seriesID: '',
      video_id: '',
      description: '',
      episode_number: '',
    }
  },
  mounted() {
    this.$parent.$on('create_new_lesson', (seriesID) => {
      this.seriesID = seriesID;
      $('#createLesson').modal();
    });
  },
  methods: {
    createLesson: function() {
      axios.post(`/admin/${this.seriesID}/lessons`, {
        title: this.title,
        video_id: this.video_id,
        description: this.description,
        episode_number: this.episode_number,
      }).then(res => {
        console.log(res);
      }).catch(error => {
        console.log(error.response);
      });
    }
  }
}
</script>
