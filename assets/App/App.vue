<template>
  <div class="main">
    <div class="notification z-50 absolute left-4 top-4 space-y-4">
      <div v-for="(notification, index) in notifications" :key="notification" class="bg-white border-purple-500 border rounded-lg p-4 grid grid-cols-5 gap-2 w-48">
        <div class="col-span-4">
          {{notification.text}}
        </div>
        <div>
          <font-awesome-icon class="col-span-1 ml-auto cursor-pointer" :icon="['fas', 'times']" v-on:click="deleteNotification(notification.id)"/>
        </div>
      </div>
    </div>
    <transition name="appear">
      <div class="relative darken" v-if="isOpen">
        
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
import axios from 'axios';

// import Upload from "./components/Upload.vue";

export default {
  components: { Navbar, Category },
  data: function () {
    return {
      isOpen: false,
      notifications: []
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
    },

    checkNotifications: async function() {
      let response = await axios({
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
          },
        method: 'get',
        url: window.address + '/api/notifications'
      })
      .catch(function (error) {
        console.log(error);
      });
      if(response) {
        this.notifications = response.data.notifications;
      }
    },

    deleteNotification: async function(id) {
      let response = await axios({
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${localStorage.getItem('token')}`,
          },
        method: 'post',
        url: window.address + '/api/notifications/' + id
      })
      .catch(function (error) {
        console.log(error);
      });
      if(response){
        notifications.splice(index, 1);
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

    let intervalId = window.setInterval(() => {
      this.checkNotifications();
    }, 2000);
  }
};
</script>

<style lang="scss" scoped>
 
body {
  overflow: hidden;
}

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