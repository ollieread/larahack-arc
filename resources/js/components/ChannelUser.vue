<template>
    <a href="#" class="box__item channel__user"
       :class="{'channel__user--current':user.isCurrent, 'channel__user--offline':! user.isOnline, 'channel__user--admin':user.can(channel.uuid.toString(), 64)}">
        {{ user.username }}
    </a>
</template>

<script>
    export default {
        name: "ChannelUser",

        props: {
            user: {
                required: true,
                type: Object,
            },
            channel: {
                required: true,
                type: Object,
            },
        },
    };
</script>

<style scoped>

</style>