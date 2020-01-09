<template>
  <div>
    
    <h2 class="title">
      Чек-лист:
    </h2>

    <v-text-field
      v-model="task"
      label="Добавить элемент"
      @keydown.enter="create"
      hide-details
    >
      <v-fade-transition v-slot:append>
        <v-icon
          v-if="task"
          @click="create"
        >
          add_circle
        </v-icon>
      </v-fade-transition>
    </v-text-field>

    <div v-if="tasks.length" class="pt-4">

      <v-divider class="mt-4"></v-divider>

      <v-row
        class="my-1 py-1"
        align="center"
      >
        <span class="mx-4">
          Всего: 
          <v-fade-transition leave-absolute>
            <span :key="`tasks-${tasks.length}`">
              {{ tasks.length }}
            </span>
          </v-fade-transition>
        </span>

        <v-divider vertical></v-divider>
        <span class="mx-4 info--text text--darken-3">
          В ожидании: 
          <v-fade-transition leave-absolute>
            <span :key="`tasks-${tasks.length}`">
              {{ remainingTasks }}
            </span>
          </v-fade-transition>
        </span>

        <v-divider vertical></v-divider>

        <span class="mx-4 success--text text--darken-3">
          Завершено:
          <v-fade-transition leave-absolute>
            <span :key="`tasks-${tasks.length}`">
              {{ completedTasks }}
            </span>
          </v-fade-transition>
        </span>

        <v-spacer></v-spacer>

        <v-progress-circular
          :value="progress"
          class="mr-2"
          color="success"
        ></v-progress-circular>
      </v-row>

      <v-divider class="mb-4"></v-divider>

      <v-card v-if="tasks.length > 0">
        <template v-for="(task, i) in tasks">
          <v-divider
            v-if="i !== 0"
            :key="`${i}-divider`"
          ></v-divider>

          <v-list-item :key="`${i}-${task.text}`">
            <v-hover v-slot:default="{ hover }">
              <div class="d-flex" style="width: 100%">
                <v-list-item-action>
                  <v-checkbox
                    v-model="task.done"
                    color="info darken-3"
                  >
                    <template v-slot:label>
                      <div
                        :class="task.done && 'grey--text' || 'text--primary'"
                        class="ml-4"
                        v-text="task.text"
                      ></div>
                    </template>
                  </v-checkbox>
                </v-list-item-action>

                <v-spacer></v-spacer>

                <v-scroll-x-transition>
                  <v-btn
                    v-if="hover"
                    class="align-self-center"
                    icon
                    @click="removeTaskItem(i)"
                    >
                    <v-icon
                      color="red"
                    >
                      mdi-playlist-remove
                    </v-icon>
                  </v-btn>
                  <v-icon
                    v-else-if="task.done"
                    color="success"
                  >
                    mdi-check
                  </v-icon>
                </v-scroll-x-transition>
              </div>
            </v-hover>
          </v-list-item>
        </template>
      </v-card>
    </div>

  </div>
</template>
<script>
  export default {
    data: () => ({
      tasks: [],
      task: null,
    }),

    computed: {
      completedTasks () {
        return this.tasks.filter(task => task.done).length
      },
      progress () {
        return this.completedTasks / this.tasks.length * 100
      },
      remainingTasks () {
        return this.tasks.length - this.completedTasks
      },
    },

    methods: {
      create () {
        if (this.task  && this.task.trim()) {
          this.tasks.unshift({
            done: false,
            text: this.task,
          })

          this.task = null
        }
      },

      removeTaskItem (itemIndex) {
        this.tasks.splice(itemIndex, 1)
      },
    },
  }
</script>