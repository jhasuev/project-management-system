<template>
  <div>
    <!-- <pre>{{checkList}}</pre> -->
    <h2 class="title">
      Чек-лист:
    </h2>

    <v-text-field
      v-model="task"
      label="Добавить элемент"
      @keydown.enter="create"
      hide-details
      :disabled="loading"
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

    <div v-if="checkList.length" class="pt-4">

      <v-divider class="mt-4"></v-divider>

      <v-row
        class="my-1 py-1"
        align="center"
      >
        <span class="mx-4">
          Всего: 
          <v-fade-transition leave-absolute>
            <span :key="`checkList-${checkList.length}`">
              {{ checkList.length }}
            </span>
          </v-fade-transition>
        </span>

        <v-divider vertical></v-divider>
        <span class="mx-4 info--text text--darken-3">
          В ожидании: 
          <v-fade-transition leave-absolute>
            <span :key="`checkList-${checkList.length}`">
              {{ remainingCheckList }}
            </span>
          </v-fade-transition>
        </span>

        <v-divider vertical></v-divider>

        <span class="mx-4 success--text text--darken-3">
          Завершено:
          <v-fade-transition leave-absolute>
            <span :key="`checkList-${checkList.length}`">
              {{ completedCheckList }}
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

      <v-card v-if="checkList.length > 0" :loading="loading">
        <template v-for="(checkList, i) in checkList">
          <v-divider
            v-if="i !== 0"
            :key="`${i}-divider`"
          ></v-divider>

          <v-list-item :key="`${i}-${checkList.text}`">
            <v-hover v-slot:default="{ hover }">
              <div class="d-flex" style="width: 100%">
                <v-list-item-action>
                  <v-checkbox
                    v-model="checkList.done"
                    color="info darken-3"
                    @change="checkThis(i)"
                  >
                    <template v-slot:label>
                      <div
                        :class="checkList.done && 'grey--text' || 'text--primary'"
                        class="ml-4"
                        v-text="checkList.text"
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
                    @click="removeCheckListItem(i)"
                    >
                    <v-icon
                      color="red"
                    >
                      mdi-playlist-remove
                    </v-icon>
                  </v-btn>
                  <v-icon
                    v-else-if="checkList.done"
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
    props: {
      boardID : [String, Number],
      taskID : [String, Number],
      checkList : Array,
    },
    data:()=> ({
      loading : false,
      task: null,
    }),
    computed: {
      completedCheckList(){
        return this.checkList.filter(task => task.done).length
      },
      progress(){
        return this.completedCheckList / this.checkList.length * 100
      },
      remainingCheckList(){
        return this.checkList.length - this.completedCheckList
      },
    },

    methods: {
      create(){
        if (this.task  && this.task.trim()) {
          this.checkList.unshift({
            done: false,
            text: this.task,
          })

          this.task = null
          this.save();
        }
      },

      removeCheckListItem(itemIndex) {
        this.checkList.splice(itemIndex, 1);
        this.save();
      },

      checkThis() {
        this.save();
      },

      save(){
        this.loading = true;

        this.axios_req('saveCheckList', {
          data : {
            'boardID' : this.boardID,
              'taskID' : this.taskID,
              'checkList' : this.checkList,
          }
        }, (response) => {
          if (response.data.status == 'success') {
            // успешно
          } else if (response.data.status == 'fail') {
            // ошибка
            // eslint-disable-next-line
            console.log(response);
          }
        }, ()=>{
          // finally
          this.loading = false
        });
      },
    },
  }
</script>