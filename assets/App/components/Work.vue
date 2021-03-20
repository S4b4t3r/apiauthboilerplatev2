<template>
  <div class="flex flex-col justify-between w-full">
    <div class="space-y-4 flex-1">
      <div class="flex items-center space-x-4">
        <font-awesome-icon class=" text-black text-purple-500 cursor-pointer" :icon="['fas', 'arrow-left']" size="lg" v-on:click="close"/>
        <div class="text-purple-500">Retour</div>
      </div>
      <h1 class="text-xl font-semibold">{{data.title}}</h1>
      <p class="italic">{{data.description}}</p>
      <div class="space-y-4 font-bold text-purple-500">
        <div v-for="file in files" :key="file" class="flex space-x-4"> 
          <a :href="'/media/' + file.file_path">{{file.file_path ? file.file_path : 'Fichier sans nom'}}</a>
          {{}}
          <div v-if="isAdmin == true" class="ml-auto hover:text-red-700" v-on:click="deleteFile(data.id, file.id)">
            <font-awesome-icon :icon="['fas', 'times']" />
          </div>
        </div>
      </div>
    </div>
    <div class="grid grid-cols-5 gap-2">
      <label class="flex col-span-4 items-center justify-center block h-8 px-4 py-5 font-bold transition border-2 border-black rounded-md cursor-pointer hover:bg-black hover:text-white">
        Ajouter un fichier
        <font-awesome-icon class="ml-4" :icon="['fas', 'upload']" />
        <input
          class="hidden"
          name="file"
          type="file"
          data-ref="file"
        />
      </label>
      <Button
        v-on:click="addFile(data.id)"
        :icon="'check'"
        :text="'Envoyer'"
        color="blue-500"
        class="col-span-1"
      ></Button>
    </div> 
  </div>
</template>

<script>

import Button from "./Button.vue";

import axios from 'axios';

export default {
  components: {
    Button
  },
  props: {
   data: {}
  },
  data() {
    return {
      files: [],
      isAdmin: false,
    }
  },
  mounted: function() {
    let admin = localStorage.getItem('admin');
    if(admin) {
        this.isAdmin = true;
    };
    this.getFiles(this.data.id);
  },
  methods: {
    getFiles: async function(id) {
      let response = await axios({
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
          },
        method: 'get',
        url: window.address + '/api/works/' + id + '/files',   
      })
      .catch(function (error) {
        console.log(error);
      });
      if(response) {
        console.log(response);
        this.files = response.data.files;
      }
    },

    addFile: async function(id) {
      function getBase64(file) {
        return new Promise((resolve, reject) => {
          let reader = new FileReader();
          reader.readAsDataURL(file);
          reader.onload = function () {
            resolve(reader.result);
          };
          reader.onerror = function (error) {
            console.log('Error: ', error);
          };
        });
      }
      let file = document.querySelector('[data-ref="file"]')
      console.log(file.files[0]);
      let base64 = await getBase64(file.files[0]);
      let response = await axios(
        {
          headers: {
              'Authorization': `Bearer ${localStorage.getItem('token')}`,
              'Content-Type': 'application/json'
          },
          method: 'post',
          url: window.address + '/api/works/' + id + '/file',   
          data: {
            'file': base64,
            'filename': file.files[0].name 
          }
        }
      ).catch(function (error) {
        console.log(error);
      });  
      this.getFiles(id)
    },


    deleteFile: async function(id, fileId) {
      let response = await axios({
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
          },
        method: 'delete',
          url: window.address + '/api/files/' + fileId,   
      })
      .catch(function (error) {
        console.log(error);
      });
      if(response) {
        this.getFiles(id);
      }
    },

    close: function() {
      window.emitter.emit('close', 'assessment')
    }
  }
}
</script>