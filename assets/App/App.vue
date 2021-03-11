<template>
  <div class="main">
    <transition name="appear">
      <div class="relative darken" v-show="isOpen">
        <div class="absolute w-1/3 p-4 transform -translate-x-1/2 -translate-y-1/2 bg-white rounded-xl top-1/2 left-1/2">
          <upload></upload>
        </div>
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
    <img class="waves" :src="require('../img/waves.svg').default" />
  </div>
</template>

<script>
import Navbar from "./components/Navbar.vue";
import Upload from "./components/Upload.vue";

export default {
  components: { Navbar, Upload },
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

nav {
  padding: 20px;

  .nav__menu {
    display: inline-flex;
    float: right;

    .nav__menu__profile {
      margin-left: 20px;
    }
  }
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
    animation: 10s linear mainButtonSpin infinite;

    img {
      position: absolute;
      left: 50%;
      top: 0;
      transform: translateX(-50%);
    }

    @keyframes mainButtonSpin {
      0% {
        transform: translateX(-50%) translateY(-50%) rotate(0deg);
      }

      100% {
        transform: translateX(-50%) translateY(-50%) rotate(360deg);
      }
    }
  }
}
.waves {
  position: absolute;
  bottom: 0;
  left: 0;
  width: 100%;
  transform: translateY(20%);
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