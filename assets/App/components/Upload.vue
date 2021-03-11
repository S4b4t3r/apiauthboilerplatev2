<template>
  <div class="flex flex-col space-y-4">
    <Title title="Ajouter un fichier"></Title>
    <label class="flex items-center justify-center block h-8 px-4 py-5 font-bold transition border-2 border-black rounded-md cursor-pointer hover:bg-black hover:text-white">
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
      v-on:click="submit"
      :icon="'check'"
      :text="'Envoyer'"
      color="blue-500"
      class="w-full"
    ></Button>
  </div>
</template>

<script>
import Button from "./Button.vue";
import Title from "./Title.vue";
import axios from 'axios';

export default {
  components: { Button, Title },

  methods: {
    submit: async function() {
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
      const description = document.querySelector('[data-ref="description"]').value;
      let base64 = await getBase64(file);
      console.log('bep');
      let response = await axios(
        {
          headers: {
              'Authorization': `Bearer ${localStorage.getItem('token')}`,
              'Content-Type': 'application/json'
          },
          method: 'post',
          url: window.address + '/api/works/9/file',   
          data: {
            'file': base64,
            'filename': file.name 
          }
        }
      ).catch(function (error) {
        console.log(error);
      });  
      console.log(response);
    }
  }
};
</script>

<style>
</style>    