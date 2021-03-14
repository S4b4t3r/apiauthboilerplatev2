<template>
  <div class="p-4 space-y-4 border-t border-gray-300">
    <div class="flex items-center space-x-4">
      <font-awesome-icon class="text-black text-purple-500 cursor-pointer" :icon="['fas', 'arrow-left']" size="lg" v-on:click="close"/>
      <div class="text-purple-500">Retour</div>
    </div>
    <h1 class="text-xl font-semibold">{{data.title}}</h1>
    <p class="italic">{{data.description}}</p>
    <div class="grid grid-cols-5 gap-4">
      <div class="col-span-1 p-2 space-y-2 border border-gray-300 rounded-xl" v-for="work in works" :key="work">
        <h2 class="font-bold text-purple-500">{{work.title}}</h2>
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
</template>

<script>

import axios from 'axios';

export default {
  props: {
   data: {}
  },
  data() {
    return {
      works: []
    }
  },
  mounted: function() {
    this.getWorks(this.data.id);
  },
  methods: {
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