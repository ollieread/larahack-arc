<template>
    <div>
        <div class="crt--on" v-if="accepted">
            <transition enter-active-class="crt--in" leave-active-class="crt--off">
                <router-view></router-view>
            </transition>
        </div>
        <photosensitive v-model="accepted"></photosensitive>
    </div>
</template>

<script>
    export default {
        name: "Layout",

        data: () => {
            return {
                accepted: false,
            };
        },
    };
</script>

<style scoped>

</style>