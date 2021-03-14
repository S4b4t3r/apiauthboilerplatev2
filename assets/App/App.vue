<template>
  <div class="main">
    <transition name="appear">
      <div class="relative darken" v-if="isOpen">
        <!-- <div class="absolute w-1/3 p-4 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-xl top-1/2 left-1/2">
          <upload></upload>
        </div> -->
        <category></category>
      </div>
    </transition>
    <navbar></navbar>
    <div class="mainButton" @click="openMenu">
      <div class="mainButton__drop">
        <img :src="require('../img/drop.svg').default" />
      </div>
      <img
        class="mainButton__logo"
        :src="require('../img/logo.svg').default"
      />
    </div>
    <img class="waves waves_1" :src="require('../img/waves_1.svg').default" />
    <img class="waves waves_2" :src="require('../img/waves_2.svg').default" />
  </div>
</template>

<script>
import Category from './components/Category.vue';
import Navbar from "./components/Navbar.vue";
// import Upload from "./components/Upload.vue";

export default {
  components: { Navbar, Category },
  data: function () {
    return {
      isOpen: false,
    };
  },
  methods: {
    openMenu: function() {
      let token = localStorage.getItem("token");
      if (token) {
        this.isOpen = true;
      } else {
        window.alert('Veuillez d\'abord vous connecter');
      }
    }
  },
  mounted: function() {

    // Popup manager

    window.emitter.on('close', (e) => {
      if(e == "finder") {
        this.isOpen = false;
      }
    })
  }
};
</script>

<style lang="scss" scoped>
 
.darken {
  width: 100vw;
  height: 100vh;
  background-color: rgba(0, 0, 0, 0.3);
  position: absolute;
  z-index: 10;
  top: 0;
  left: 0;
}

.mainButton {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translateX(-50%) translateY(-50%);
  transition: 0.3s ease all;
  cursor: pointer;
  &:hover {
    transform: translateX(-50%) translateY(-50%) scale(1.1);
  }

  .mainButton__logo {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translateX(-50%) translateY(-50%);
    height: 250px;
    max-width: 250px;
  }
  .mainButton__drop {
    height: 300px;
    width: 300px;
    position: absolute;
    z-index: -10;
    top: 50%;
    left: 50%;
    transform: translateX(-50%) translateY(-50%);

    img {
      position: absolute;
      left: 50%;
      top: 0;
      transform: translateX(-50%);
    }
  }
}
.waves {
  position: absolute;
  bottom: -100px;
  left: 0;
  width: 100%;
  transform: translateY(40%);
}

.appear-enter-active,
.appear-leave-active {
  transition: opacity 0.3s ease;
}
.appear-enter-from,
.appear-leave-to {
  opacity: 0;
}

</style>