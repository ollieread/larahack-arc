<template>
    <div class="box__item channel__message"
         :class="{'channel__message--action':message.isAction(), 'channel__message--system':!message.user}">

        <template v-if="message.isAction()">
            <template v-if="message.action === 'join'">
                <div class="channel__message-content">
                    <strong>{{ message.user ? message.user.username : 'SYSTEM' }}</strong> joined
                </div>
            </template>
            <template v-if="message.action === 'deploy.running'">
                <span class="channel__message-author">{{ message.user ? message.user.username : 'SYSTEM' }}</span>
                <div class="channel__message-content">
                    Deployment running
                </div>
            </template>
            <template v-if="message.action === 'deploy.completed'">
                <span class="channel__message-author">{{ message.user ? message.user.username : 'SYSTEM' }}</span>
                <div class="channel__message-content">
                    Deployment completed <br>
                    "<span>{{ message.metadata.end_revision.message }}</span>"
                </div>
            </template>
        </template>

        <template v-else-if="message.isText()">
            <span class="channel__message-author">{{ message.user ? message.user.username : 'SYSTEM' }}</span>
            <div class="channel__message-content" v-html="markdown"></div>
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

        computed: {
            markdown() {
                return this.$marked(this.message.message);
            },
        },
    };
</script>