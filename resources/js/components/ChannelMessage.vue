<template>
    <div class="box__item channel__message">

        <template v-if="message.isAction()">
            <div class="channel__message-content">
                <strong>{{ message.user.username }}</strong>
                <template v-if="message.action === 'join'">joined</template>
            </div>
        </template>

        <template v-else-if="message.isText()">
            <span class="channel__message-author">{{ message.user ? message.user.username : 'SYSTEM' }}</span>
            <div class="channel__message-content">{{ message.message }}</div>
        </template>

        <time class="channel__message-time">{{ message.postedAt.format('L LT') }}</time>

    </div>
</template>

<script>
    export default {
        name: "ChannelMessage",

        props: {
            message: {
                required: true,
                type: Object,
            },
        },
    };
</script>