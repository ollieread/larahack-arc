<template>
    <div class="page page--single" :class="{'crt--off':animating}">
        <div class="crt"></div>
        <div class="container">
            <pre class="logo">
  :::.    :::::::..     .,-:::::
  ;;`;;   ;;;;``;;;;  ,;;;'````'
 ,[[ '[[,  [[[,/[[['  [[[
c$$$cc$$$c $$$$$$c    $$$
 888   888,888b "88bo,`88bo,__,o,
 YMM   ""` MMMM   "W"   "YUMMMMMP"
            </pre>
            <article class="box box--small box--headerless">
                <h1 class="box__title">Authenticate</h1>
                <main class="box__body">
                    <div class="input">
                        <label for="login-username" class="input__label">Username</label>
                        <div class="input__field">
                            <input type="text" class="input__field-input" id="login-username" v-model="username"
                                   name="username"
                                   v-validate="rules.username" placeholder="username">
                        </div>
                        <div class="input__feedback" :class="{'input__feedback--error' : errors.has('username')}"
                             v-if="errors.has('username')">{{ errors.first('username') }}
                        </div>
                    </div>
                    <div class="input">
                        <label for="login-password" class="input__label">Password</label>
                        <div class="input__field">
                            <input type="password" class="input__field-input" id="login-password" v-model="password"
                                   name="password" v-validate="rules.password" placeholder="password">
                        </div>
                        <div class="input__feedback" :class="{'input__feedback--error' : errors.has('password')}"
                             v-if="errors.has('password')">{{ errors.first('password') }}
                        </div>
                    </div>

                </main>
                <footer class="box__footer">
                    <button class="button button--simple box__footer--right" @click.prevent="login">Submit</button>
                    <button class="button button--simple box__footer--left">GOTO : Reset</button>
                    <router-link :to="{name:'register'}" class="button button--simple box__footer--left">GOTO : Enroll
                    </router-link>
                </footer>
            </article>
        </div>

        <div class="modal crt--in" v-if="loading">
            <div class="box box--small">
                <header class="box__header">
                    <h2 class="box__header-title">Authenticating</h2>
                </header>
                <div class="box__body">
                    <div class="progress">
                        <div class="progress__line" v-if="progress.connection">
                            <div class="progress__line-text">Establishing Connection</div>
                            <div class="progress__line-status">
                                <div class="progress__line-status-body"></div>
                            </div>
                        </div>
                        <div class="progress__line" v-if="progress.authenticating">
                            <div class="progress__line-text">Transmitting Authentication</div>
                            <div class="progress__line-status">
                                <div class="progress__line-status-body"></div>
                            </div>
                        </div>
                        <div class="progress__line" v-if="progress.verifying">
                            <div class="progress__line-text">Verifying Signature</div>
                            <div class="progress__line-status">
                                <div class="progress__line-status-body"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Login",

        data: () => {
            return {
                username: '',
                password: '',
                rules: {
                    username: {
                        required: true,
                        min: 3,
                    },
                    password: {
                        required: true,
                        min: 8,
                    },
                },
                progress: {
                    connection: false,
                    authenticating: false,
                    verifying: false,
                },
                loading: false,
                animating: false,
            };
        },

        methods: {
            login() {
                let username = this.username;
                let password = this.password;

                if (username !== '' && password !== '' && this.$validator.errors.count() === 0) {
                    this.$store
                        .dispatch('Auth/login', {username, password})
                        .then(() => {
                            this.loading             = true;
                            this.progress.connection = true;
                            setTimeout(() => this.progress.authenticating = true, 1550);
                            setTimeout(() => this.progress.verifying = true, 2550);
                            setTimeout(() => this.animating = true, 3550);
                            setTimeout(() => this.$router.push('/'), 4050);
                        });
                }
            }
            ,
        },

    };
</script>

<style scoped>

</style>