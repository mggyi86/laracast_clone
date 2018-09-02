<template>
    <div class="container text-center" style="color: black; font-weight: bold;">

        <h1 class="text-center">
            <button class="btn btn-primary" @click="createNewLesson">
                Create New Lesson
            </button>
        </h1>

        <div class="">
        <ul class="list-group d-flex">
            <li class="list-group-item flex justify-content-between" v-for="(lesson,key) in lessons" :key=lessons.indexOf(lesson)>
                <p>{{ lesson.title }}</p>
                <p>
                    <button class="btn btn-primary btn-xs" @click="editLesson(lesson)">
                        Edit
                    </button>
                    <button class="btn btn-danger btn-xs" @click="deleteLesson(lesson.id, key)">
                        Delete
                    </button>
                </p>
            </li>
        </ul>
        </div>
        <create-lesson></create-lesson>
    </div>
</template>


<script>
import axios from 'axios';

export default {
  props: ['default_lessons', 'series_id'],
  mounted() {
    this.$on('lesson_created', (lesson) => {

      window.noty({
          message: 'Lesson created successfully',
          type: 'success'
      });

      this.lessons.push(lesson);

    });

    this.$on('lesson_updated', (lesson) => {

      let lessonIndex = this.lessons.findIndex(l => {
        return lesson.id == l.id;
      });

      window.noty({
        message: 'Lesson updated successfully',
        type: 'success'
      });

      this.lessons.splice(lessonIndex, 1, lesson);

    });

  },
  components: {
    'create-lesson': require('./children/CreateLesson.vue')
  },
  data() {
    return {
      lessons: JSON.parse(this.default_lessons)
    }
  },
  computed: {
    // formattedLessons() {
    //   return JSON.parse(this.lessons);
    // }
  },
  methods: {
    createNewLesson: function() {
      this.$emit('create_new_lesson', this.series_id)
    },
    deleteLesson: function(id, key) {
      if(confirm('Are you sure you want to delete?')) {
        axios.delete(`/admin/${this.series_id}/lessons/${id}`)
          .then(res => {
            this.lessons.splice(key, 1)
          }).catch(error => {
              window.handleErrors(error);
            //console.log(error.response);
          });
      }
    },
    editLesson(lesson) {
      let seriesId = this.series_id;
      this.$emit('edit_lesson', { lesson, seriesId });
    }
  }
}
</script>
