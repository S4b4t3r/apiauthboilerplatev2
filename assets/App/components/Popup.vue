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
  },
  setup(props) {
    let submit = async () => {
      let data = {};
      for(let i = 0; i < props.fields.length; i++) {
        let fieldName = props.fields[i].name;
        let fieldValue = document.querySelector(`[name="${fieldName}"]`).value;
        data[fieldName] = fieldValue;
      }
      let response = await axios(
          {
            method: 'post',
            url: '',   
            data: data
          }
        )
    };

    return {
      submit,
    };
  },
};
</script>

<style>
</style>    