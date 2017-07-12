<template>
    <div>


        {{ msg }}

        <div class="input-group" style="text-align: center">
            <input type="text" style="position: absolute;float: right" class="form-control" v-model="msg">
            <span class="input-group-btn" >
                <button class="btn btn-default" style="width: 400px" type="button">Go!</button>
            </span>
        </div><!-- /input-group -->
    </div>
</template>

<script>
    export default {
        data () {
            return {
                msg: '',

            }
        },
        methods: {
            msg() {
                // Emit the server side
                this.$socket.emit(this.msg, { a: 5, b: 3 });
            },

        },

        socket: {
            // Prefix for event names
            // prefix: "/counter/",

            // If you set `namespace`, it will create a new socket connection to the namespace instead of `/`
            // namespace: "/counter",

            events: {

                // Similar as this.$socket.on("changed", (msg) => { ... });
                // If you set `prefix` to `/counter/`, the event name will be `/counter/changed`
                //
                changed(msg) {
                    console.log('Something changed: ' + msg);
                }

                /* common socket.io events
                 connect() {
                 console.log("Websocket connected to " + this.$socket.nsp);
                 },

                 disconnect() {
                 console.log("Websocket disconnected from " + this.$socket.nsp);
                 },

                 error(err) {
                 console.error("Websocket error!", err);
                 }
                 */

            }
        }
    };
</script>

<style lang="css">
</style>
