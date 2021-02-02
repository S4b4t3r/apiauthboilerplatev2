<template>
    <div>
        <div class="darken" @click="isOpen = !isOpen" v-if="isOpen"></div>
        <div class="main">
            <nav>
                <img :src="require('../img/smallLogo.svg').default">
                <div class="nav__menu">
                    <img :src="require('../img/grid.svg').default">
                    <div class="nav__menu__profile">
                        <img :src="require('../img/profilePlaceholder.png').default">
                    </div>
                </div>
            </nav>
            <div class="mainButton" @click="isOpen = !isOpen">
                <div class="mainButton__drop">
                    <img :src="require('../img/drop.svg').default">
                </div>
                <img class="mainButton__logo" :src="require('../img/logo.svg').default">
            </div>
            <img class="waves" :src="require('../img/waves.svg').default">
        </div>
    </div>
</template>

<script>
export default {
    data: function() {
        return {
            isOpen: false
        }
    }
}
</script>

<style lang="scss">

    .darken {
        width: 100vw;
        height: 100vh;
        background-color: black;
        opacity: 0.3;
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
            width: 250px;
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
</style>