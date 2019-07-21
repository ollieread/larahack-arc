<template>
    <div class="modal" v-show="display" :class="{'crt--off':animating}">
        <div class="container">
            <div class="box box--small box--centered">
                <header class="box__header box__header--error">
                    <h2 class="box__header-title">Warning</h2>
                </header>
                <div class="box__body">
                    <pre class="logo logo--small">
                      :::!~!!!!!:.
                  .xUHWH!! !!?M88WHX:.
                .X*#M@$!!  !X!M$$$$$$WWx:.
               :!!!!!!?H! :!$!$$$$$$$$$$8X:
              !!~  ~:~!! :~!$!#$$$$$$$$$$8X:
             :!~::!H!<   ~.U$X!?R$$$$$$$$MM!
             ~!~!!!!~~ .:XW$$$U!!?$$$$$$RMM!
               !:~~~ .:!M"T#$$$$WX??#MRRMMM!
               ~?WuxiW*`   `"#$$$$8!!!!??!!!
             :X- M$$$$       `"T#$T~!8$WUXU~
            :%`  ~#$$$m:        ~!~ ?$$$$$$
          :!`.-   ~T$$$$8xx.  .xWW- ~""##*"
.....   -~~:<` !    ~?T#$$@@W@*?$$      /`
W$@@M!!! .!~~ !!     .:XUW$W!~ `"~:    :
#"~~`.:x%`!!  !H:   !WM$$$$Ti.: .!WUn+!`
:::~:!!`:X~ .: ?H.!u "$$$B$$$!W:U!T$$M~
.~~   :X@!.-~   ?@WTWo("*$$$W$TH$! `
Wi.~!X$?!-~    : ?$$$B$Wu("**$RM!
$R@i.~~ !     :   ~$$$$$B$$en:``
?MXT@Wx.~    :     ~"##*$$$$M~
                    </pre>
                    <div class="text--centered">
                        <p>
                            This website contains flashing images that may not be suitable for people with
                            photosensitive epilepsy.
                        </p>
                    </div>
                </div>
                <footer class="box__footer">
                    <button class="button button--simple box__footer--right" @click.prevent="accept">Ok</button>
                    <button class="button button--simple box__footer--right" @click.prevent="exit">Exit</button>
                </footer>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Photosensitive",

        data: () => {
            return {
                animating: false,
                display: true,
            };
        },

        methods: {
            exit() {
                window.location.href = 'https://google.com';
            },

            accept() {
                this.animating = true;

                setTimeout(() => {
                    this.display = false;
                    this.$emit('input', !this.display)
                }, 550)
            },
        },
    };
</script>

<style scoped>

</style>