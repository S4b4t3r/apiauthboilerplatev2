<template>
    <nav class="relative">
        <img class="inline-block" :src="require('../../img/smallLogo.svg').default">
        <div class="inline-block nav__menu">
        <div>
            <Button v-on:click="popupType = 'signup'" class="mr-4" text="Inscription" color="black"></Button>
            <Button v-on:click="popupType = 'login'" reversed=true text="Connexion" color="black"></Button>
        </div>
            <div class="absolute right-0 transform translate-y-full right-6 -bottom-8">
                <transition name="appear" mode="out-in">
                    <Popup v-if="popupType == 'login'" cta="Se connecter" ctaIcon="sign-in-alt" :fields="[
                        {type: 'text', placeholder: 'Nom de compte', name: 'login'},
                        {type: 'password', placeholder: 'Mot de passe', name: 'password'},
                    ]">
                    </Popup>
                    <Popup v-else-if="popupType == 'signup'" cta="S'inscrire" ctaIcon="user-plus" :fields="[
                        {type: 'email', placeholder: 'Addresse mail', name: 'mail'},
                        {type: 'text', placeholder: 'Nom de compte', name: 'login'},
                        {type: 'password', placeholder: 'Mot de passe', name: 'password'},
                    ]">
                    </Popup>
                </transition>
            </div>
        </div>
    </nav>
</template>

<script>
import Button from './Button.vue'
import Popup from './Popup.vue'
export default {
  components: { Button, Popup },
  data() {
      return {
        popupType: ''
      }
  }
}
</script>

<style scoped>
    .appear-enter-active, .appear-leave-active {
        transition: opacity .3s ease;
    }
    .appear-enter-from, .appear-leave-to {
        opacity: 0;
    }
</style>