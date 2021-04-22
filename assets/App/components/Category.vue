<template>
  <div>
    <div class="absolute flex flex-col w-2/3 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-xl top-1/2 left-1/2 min-h-3/4 max-h-3/4">
      <Title item="finder" class="px-4 py-4" title="Gestionnaire de fichiers"></Title>
      <div :class="currentAssessment == assessment.id ? 'flex flex-1' : ''" v-for="assessment in assessments" :key="assessment">
        <assessment class="flex" v-if="currentAssessment == assessment.id" :data="assessment"></assessment>
      </div>
      <div v-if="currentAssessment == null" class="flex flex-1">
        <div class="flex flex-col justify-between w-1/3 px-4 pt-4 border-t border-r border-gray-300">
          <div class="space-y-4 overflow-y-auto">
            <input class="border px-4 w-full py-2 rounded-full" type="text" v-model="categorySearch">
            <div class="flex w-full font-bold transition cursor-pointer" :class="[category.title.includes(categorySearch) ? '' : 'hidden']" v-for="category in categories" :key="category">
              
              <span v-if="titleEdit != category.id" :class="[currentCategory == category.id ? 'text-purple-500': '']" class="hover:text-purple-500" v-on:click="showAssessments(category.id)">{{category.title}}</span>
              <input v-if="titleEdit == category.id"  v-model="category.title" v-on:keyup.enter="editCategory(category.id, category.title)">
              <div v-if="isAdmin == true" class="ml-auto">
                <font-awesome-icon :icon="['fas', 'pencil']" class="hover:text-purple-500 mr-2" v-on:click="titleEdit = category.id"/>
                <font-awesome-icon :icon="['fas', 'times']" class="hover:text-red-700" v-on:click="deleteCategory(category.id)"/>
              </div>
            </div>
          </div>
        </div>
        <div class="flex flex-col justify-between w-2/3 p-4 border-t border-gray-300">
          <div class="relative h-full mb-4">
            <div class="absolute w-full h-full space-y-2 overflow-y-auto">
              <input class="border px-4 w-full py-2 rounded-full" type="text" v-model="assessmentSearch">
              <div class="flex w-full font-bold transition cursor-pointer" :class="[assessment.title.includes(assessmentSearch) ? '' : 'hidden']" v-for="assessment in assessments" :key="assessment">
                  <div class="flex w-full p-4 bg-gray-100 border border-gray-300 rounded-sm">
                    <span v-on:click="currentAssessment = assessment.id">{{assessment.title}}</span>
                    <div v-if="isAdmin == true" class="ml-auto hover:text-red-700" v-on:click="deleteAssessment(assessment.id)">
                      <font-awesome-icon :icon="['fas', 'trash']" />
                    </div>
                  </div>
              </div>
            </div>
          </div>
          <div v-if="isAdmin == true && currentCategory != null">
            <div class="grid grid-cols-5 gap-2 pb-4">
              <input
                class="w-full h-8 col-span-4 px-4 py-5 border border-gray-300 rounded-md"
                placeholder="Ajouter une évaluation"
                data-ref="assessmentTitle"
                type="text"
              />
              <Button       
                v-on:click="addAssessment(currentCategory)"
                text="Ajouter"
                color="pink-600"
                class="col-span-1">
              </Button>
            </div>
            <input
              class="w-full h-8 px-4 py-5 border border-gray-300 rounded-md"
              placeholder="Ajouter une description (optionel)"
              data-ref="assessmentDescription"
              type="text"
            />
          </div>
        </div>
      </div>
      <div v-if="isAdmin == true && currentAssessment == null" class="grid grid-cols-6 p-4 border-t border-gray-300">
        <input
          class="w-full h-8 col-span-4 px-4 py-5 border border-gray-300 rounded-md"
          placeholder="Ajouter une catégorie"
          data-ref="category"
          type="text"
        />
        <Button       
          v-on:click="addCategory"
          icon="plus"
          text="Ajouter"
          color="pink-600"
          class="col-span-2 ml-4">
        </Button>
      </div>
    </div>
  </div>
</template>


<script>

import axios from 'axios';
import Title from "./Title.vue";
import Button from "./Button.vue";
import Assessment from './Assessment.vue';

export default {  
  components: { Title, Button, Assessment},
  data() {
    return {
      isAdmin: false,
      currentCategory: null,
      currentAssessment: null,
      categories: [],
      assessments: [],
      works: [],
      datePicker: false,
      categorySearch: '',
      assessmentSearch: '',
      titleEdit: null,
    }
  },

  methods: {
    isAuthorized: function() {
      if(this.isAdmin) {
        return true;
      } else {
        return false;
      }
    },
    editCategory: async function(id, title) {
      let response = await axios({
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
          },
        method: 'put',
        url: window.address + '/api/categories/' + id,   
        data: {
          'title': title,
        }
      })
      .catch(function (error) {
        console.log(error);
      });
      if(response) {
        this.titleEdit = null;
      }
    },
    getCategories: async function() {
      let response = await axios({
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
          },
        method: 'get',
        url: window.address + '/api/categories',   
      })
      .catch(function (error) {
        console.log(error);
      });
      if(response) {
        this.categories = response.data.categories;
      }
    },
    
    addCategory: async function() {
      let response = await axios({
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
          },
        method: 'post',
        url: window.address + '/api/categories',   
        data: {
          "title": document.querySelector('[data-ref="category"]').value,
          "description": ""
        }
      })
      .catch(function (error) {
        console.log(error);
      });
      if(response) {
        this.getCategories();
      }
    },

    deleteCategory: async function(id) {
      let response = await axios({
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
          },
        method: 'delete',
        url: window.address + '/api/categories/' + id,   
      })
      .catch(function (error) {
        console.log(error);
      });
      if(response) {
        console.log(response);
        this.currentCategory = null;
        this.getCategories();
      }
    },

    deleteAssessment: async function(id) {
      let response = await axios({
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
          },
        method: 'delete',
        url: window.address + '/api/assessments/' + id,   
      })
      .catch(function (error) {
        console.log(error);
      });
      if(response) {
        console.log(response);
        this.showAssessments(this.currentCategory);
      }
    },

    addAssessment: async function(id) {
      let response = await axios({
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
          },
        method: 'post',
        url: window.address + '/api/categories/' + id + '/assessment',   
        data: {
          'title': document.querySelector('[data-ref="assessmentTitle"]').value,
          'description': document.querySelector('[data-ref="assessmentDescription"]').value,
          'dueDate': null
        }
      })
      .catch(function (error) {
        console.log(error);
      });
      if(response) {
        this.showAssessments(this.currentCategory);
      }
    },

    showAssessments: async function(id) {
      let response = await axios({
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
          },
        method: 'get',
        url: window.address + '/api/categories/' + id + '/assessments',   
      })
      .catch(function (error) {
        console.log(error);
      });
      if(response) {
        console.log(response);
        this.assessments = response.data.assessments;
        this.currentCategory = id;
      }
    },

    showAssessment: async function(id) {
      let response = await axios({
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
          },
        method: 'get',
        url: window.address + '/api/categories/' + id + '/assessments',   
      })
      .catch(function (error) {
        console.log(error);
      });
      if(response) {
        console.log(response);
      }
    },
  }, 

  mounted() {
    let admin = localStorage.getItem('admin');
    if(admin) {
        this.isAdmin = true;
    };
    this.getCategories();

    window.emitter.on('close', (e) => {
      if(e == "assessment") {
        this.currentAssessment = null;
      }
    })
  }
}
</script>