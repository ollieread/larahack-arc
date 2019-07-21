<template>
    <div class="box__item channel__message">

        <template v-if="message.isAction()">
            <div class="channel__message-content">
                <strong>{{ message.user ? message.user.username : 'SYSTEM' }}</strong>
                <template v-if="message.action === 'join'">joined</template>
                <template v-if="message.action === 'deploy.running'">
                    <p class="mb-1">System deployment running ({{ message.metadata.start_revision.ref }} - {{ message.metadata.end_revision.ref }})</p>
                    <p>Commit message: {{ message.metadata.end_revision.message }}</p>
                </template>
                <template v-if="message.action === 'deploy.completed'">System deployment completed</template>
            </div>
        </template>

        <template v-else-if="message.isText()">
            <span class="channel__message-author">{{ message.user ? message.user.username : 'SYSTEM' }}</span>
            <div class="channel__message-content" v-html="markdown"></div>
        </template>

        <time class="channel__message-time">{{ message.postedAt.format('L LT') }}</time>

    </div>
</template>

<script>
    import * as marked from 'marked';

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
                return marked(this.message.message);
            }
        }
    };
</script>