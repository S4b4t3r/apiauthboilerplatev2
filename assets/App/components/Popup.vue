<template>
  <div class="flex flex-col p-8 bg-white border-2 border-black rounded-md">
    <form>
      <div v-for="field in fields" :key="field.name" class="mb-4">
        <input
          class="h-8 px-4 py-5 border-2 border-black rounded-md"
          :placeholder="field.placeholder"
          :name="field.name"
          :type="field.type"
        />
      </div>
      <Button
        v-on:click="submit"
        :icon="ctaIcon"
        :text="cta"
        color="purple-500"
        class="w-full"
      ></Button>
    </form>
  </div>
</template>

<script>
import Button from "./Button.vue";
import axios from 'axios';

export default {
  components: { Button },
  props: {
    fields: Array,
    cta: String,
    ctaIcon: String,
    method: String,
    submit: String,
    route: String,
  },
  setup(props, {emit}) {
    let submit = async () => {
      let data = {};
      for(let i = 0; i < props.fields.length; i++) {
        let fieldName = props.fields[i].name;
        let fieldValue = document.querySelector(`[name="${fieldName}"]`).value;
        data[fieldName] = fieldValue;
      }
      let response = await axios(
          {
            headers: {
                'Content-Type': 'application/json'
            },
            method: 'post',
            url: window.address + props.route,   
            data: data
          }
      ).catch(function (error) {
        console.log(error);
      });
      if(response) {
        if(props.submit == 'login' && response.status == 200) {
          let token = response.data.token;
          localStorage.setItem('token', token);
          window.alert('Connexion réussie !');
          emit('login', token)
          console.log(response);
          window.emitter.emit('name', response.data.data.name);
          localStorage.setItem('name', response.data.data.name);
          if(response.data.data.admin == true) {
            localStorage.setItem('admin', true);
            window.emitter.emit('admin', true);
          }
        }
        
        else if(props.submit == 'signup' && response.status == 200) {
          window.alert('Inscription réussie, veuillez vous connecter !');
          emit('close')
        } 

        else {
          window.alert('Une erreur est survenue, désolé');
        }
      }  
      else {
        window.alert('Une erreur est survenue, désolé');
      }
    };

    return {
      submit,
    };
  },
};
</script>

<style>
</style>    