<template>
  <div class="p-4 border-t border-gray-300 flex flex-col justify-between w-full">
    <div :class="[currentWork === work.id ? 'flex flex-1' : 'hidden']" v-for="work in works" :key="work">
      <work class="flex flex-1" v-if="currentWork == work.id" :data="work"></work>
    </div>
    <div v-if="currentWork == null" class="flex flex-1 flex-col">
      <div class="space-y-4 flex-1">
        <div class="flex items-center space-x-4">
          <font-awesome-icon class=" text-black text-purple-500 cursor-pointer" :icon="['fas', 'arrow-left']" size="lg" v-on:click="close"/>
          <div class="text-purple-500">Retour</div>
        </div>
        <h1 v-if="!titleEdit" v-on:click="titleEdit = true" class="text-xl font-semibold">{{data.title}}</h1>
        <input v-if="titleEdit" v-on:keyup.enter="editAssessment(data.id)" v-model="data.title">
        <div class="grid grid-cols-5 gap-2">
          <p class="italic col-span-4" v-if="!titleEdit" v-on:click="titleEdit = true">{{data.description}}</p>
          <input class="col-span-4" v-if="titleEdit" v-on:keyup.enter="editAssessment(data.id)" v-model="data.description">
          <p class="text-purple-500 font-bold col-span-1 text-right" v-on:click="orderByLikes()">Trier par likes</p>
        </div>
        <div>
          <input class="border px-4 w-full py-2 rounded-full mb-4" type="text" v-model="workSearch">
          <div class="grid grid-cols-5 gap-4">
            <div class="col-span-1 relative p-2 space-y-2 border border-gray-300 rounded-xl" :class="[work.title.includes(workSearch) || work.author.includes(workSearch) ? '' : 'hidden']"  v-for="work in works" :key="work">
              <div v-if="isAdmin == true" class="ml-auto absolute top-4 right-4 hover:text-red-700 cursor-pointer" v-on:click="deleteWork(data.id, work.id)">
                <font-awesome-icon :icon="['fas', 'trash']"/>
              </div>
              <h2 class="font-bold text-purple-500" v-on:click="currentWork= work.id">{{work.title}}</h2>
              <h3 class="text-sm font-semibold text-gray-500">{{work.author}}</h3>
              <p class="italic">{{work.description}}</p>
              <div class="flex items-center justify-end w-full space-x-2">
                <div class="font-bold text-pink-400">{{work.likes}}</div>
                <font-awesome-icon v-on:click="like(work.id)" v-if="work.is_liked == false" class="text-black text-pink-400 cursor-pointer" :icon="['far', 'heart']" size="lg"/>
                <font-awesome-icon v-on:click="dislike(work.id)" v-if="work.is_liked == true" class="text-black text-pink-400 cursor-pointer group-hover:text-white" :icon="['fas', 'heart']" size="lg"/>
              </div>
            </div>
          </div>
        </div>
      </div>
      <div class="grid grid-cols-5 gap-2">
        <input
            class="w-full h-8 col-span-2 px-4 py-5 border border-gray-300 rounded-md"
            placeholder="Ajouter une travail"
            data-ref="workTitle"
            type="text"
          />
        <input
          class="w-full col-span-2 h-8 px-4 py-5 border border-gray-300 rounded-md"
          placeholder="Ajouter une description (optionel)"
          data-ref="workDescription"
          type="text"
        />
        <Button       
            v-on:click="addWork(data.id)"
            text="Ajouter"
            color="pink-600"
            class="col-span-1">
        </Button>
      </div> 
    </div>
  </div>
</template>

<script>

import Button from "./Button.vue";
import Work from "./Work.vue";

import axios from 'axios';

export default {
  components: {
    Button,
    Work
  },
  props: {
   data: {}
  },
  data() {
    return {
      works: [],
      isAdmin: false,
      currentWork: null,  
      workSearch: '',
      titleEdit: false,
    }
  },
  mounted: function() {
    let admin = localStorage.getItem('admin');
    if(admin) {
        this.isAdmin = true;
    };
    this.getWorks(this.data.id);
    window.emitter.on('close', (e) => {
      if(e == "work") {
        this.currentWork = null;
      }
    })
  },
  methods: {
    orderByLikes: function() {
      this.works.sort((a, b) => (a.likes < b.likes) ? 1 : -1);
    },
    editAssessment: async function(id) {
      let response = await axios({
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
          },
        method: 'put',
        url: window.address + '/api/assessments/' + id,   
        data: {
          'title': this.data.title,
          'description': this.data.description,
          'dueDate': null,
        }
      })
      .catch(function (error) {
        console.log(error);
      });
      if(response) {
        this.titleEdit = false;
      }
    },
    getWorks: async function(id) {
      let response = await axios({
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
          },
        method: 'get',
        url: window.address + '/api/assessments/' + id + '/works',   
      })
      .catch(function (error) {
        console.log(error);
      });
      if(response) {
        console.log(response);
        this.works = response.data.works;
      }
    },

    addWork: async function(id) {
      let response = await axios({
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
          },
        method: 'post',
        url: window.address + '/api/assessments/' + id + '/work',   
        data: {
          "title": document.querySelector('[data-ref="workTitle"]').value,
          "description": document.querySelector('[data-ref="workDescription"]').value,
          "is_public": true,
        }
      })
      .catch(function (error) {
        console.log(error);
      });
      if(response) {
        this.getWorks(id);
        document.querySelector('[data-ref="workTitle"]').value = '';
        document.querySelector('[data-ref="workDescription"]').value = '';
      }
    },


    deleteWork: async function(id, workId) {
      let response = await axios({
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
          },
        method: 'delete',
        url: window.address + '/api/works/' + workId,   
      })
      .catch(function (error) {
        console.log(error);
      });
      if(response) {
        this.getWorks(id);
      }
    },

    like: async function(id) {
      let response = await axios({
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
          },
        method: 'post',
        url: window.address + '/api/like/' + id,   
      })
      .catch(function (error) {
        console.log(error);
      });
      if(response) {
        console.log(response);
        this.getWorks(this.data.id);
      }
    },

    dislike: async function(id) {
      let response = await axios({
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
          },
        method: 'delete',
        url: window.address + '/api/like/' + id,   
      })
      .catch(function (error) {
        console.log(error);
      });
      if(response) {
        console.log(response);
        this.getWorks(this.data.id);
      }
    },

    close: function() {
      window.emitter.emit('close', 'assessment')
    }
  }
}
</script>