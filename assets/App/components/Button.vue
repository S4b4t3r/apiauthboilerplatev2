<template>
  <div
    :class="['inline-block px-8 py-2 text-center font-bold transition border-2 rounded-full cursor-pointer',
      reversed ? `text-white border-${color} hover:bg-transparent hover:text-${color} bg-${color}` :
      `text-${color} border-${color} hover:bg-${color} hover:text-white`,
    ]"
  >
    {{ text }}
    <div v-if="icon" class="inline-block ml-2 ">
      <font-awesome-icon :icon="['fas', icon]" />
    </div>
  </div>
</template>

<script>
export default {
  name: "Button",
  props: { 
    text: String,
    color: String,
    reversed: Boolean,
    icon: String,
  },
};
</script>