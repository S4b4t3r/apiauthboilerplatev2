<template>
  <div class="flex items-center justify-between">
    <div class="text-3xl font-bold text-black">{{ title }}</div>
    <font-awesome-icon class="text-black cursor-pointer" :icon="['fal', 'times']" size="lg"/>
  </div>
</template>

<script>

export default {
  props: {
    title: {
      type: String,
      default: "Lorem Ipsum"
    }
  }
}

</script>