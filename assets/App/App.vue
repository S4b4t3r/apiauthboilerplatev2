<template>
    <div>
        <h2>Welcome on vue !</h2>
        <button @click="counter++">Click me {{counter}}</button>
    </div>
</template>

<script>
export default {
    data: function() {
        return {
            counter: 0,
        }
    }
}
</script>

<style>

</style>