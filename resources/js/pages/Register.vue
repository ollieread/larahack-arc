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
                <h1 class="box__title">Enroll</h1>
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
                        <label for="login-email" class="input__label">Email</label>
                        <div class="input__field">
                            <input type="email" class="input__field-input" id="login-email" v-model="email" name="email"
                                   v-validate="rules.email" placeholder="email">
                        </div>
                        <div class="input__feedback" :class="{'input__feedback--error' : errors.has('email')}"
                             v-if="errors.has('email')">{{ errors.first('email') }}
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
                    <div class="input">
                        <label for="login-password_confirmation" class="input__label">Password Confirmation</label>
                        <div class="input__field">
                            <input type="password" class="input__field-input" id="login-password_confirmation"
                                   v-model="password_confirmation"
                                   name="password" v-validate="rules.password_confirmation"
                                   placeholder="confirm_password">
                        </div>
                        <div class="input__feedback"
                             :class="{'input__feedback--error' : errors.has('password_confirmation')}"
                             v-if="errors.has('password_confirmation')">{{
                            errors.first('password_confirmation') }}
                        </div>
                    </div>
                </main>
                <footer class="box__footer box__footer--secondary">
                    <button class="button button--simple" @click.prevent="register">Submit</button>
                    <router-link :to="{name:'login'}" class="button button--simple">GOTO : Authenticate</router-link>
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
                        <div class="progress__line" v-if="progress.data">
                            <div class="progress__line-text">Transmitting Data</div>
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
                email: '',
                password: '',
                password_confirmation: '',
                rules: {
                    username: {
                        required: true,
                        min: 3,
                        regex: /^[a-zA-Z0-9\.]*$/,
                    },
                    email: {
                        required: true,
                        email: true,
                    },
                    password: {
                        required: true,
                        confirmed: 'password_confirmation',
                        min: 8,
                    },
                    password_confirmation: {},
                },
                progress: {
                    connection: false,
                    data: false,
                    verifying: false,
                },
                loading: false,
                animating: false,
            };
        },

        methods: {
            register() {
                let username              = this.username;
                let password              = this.password;
                let email                 = this.email;
                let password_confirmation = this.password_confirmation;

                if (username !== '' && password !== '' && email !== '' && password_confirmation !== '' && this.$validator.errors.count() === 0) {
                    this.$store
                        .dispatch('Auth/register', {username, email, password, password_confirmation})
                        .then(() => {
                            this.loading             = true;
                            this.progress.connection = true;
                            setTimeout(() => this.progress.data = true, 1550);
                            setTimeout(() => this.progress.verifying = true, 2550);
                            setTimeout(() => this.animating = true, 3550);
                            setTimeout(() => this.$router.push('/login'), 4050);
                        });
                }
            },
        },

    };
</script>

<style scoped>

</style>