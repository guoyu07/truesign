<template xmlns:v-on="http://www.w3.org/1999/xhtml">

    <div class="ui container register-page">
        <h1 class="ui header"><i class="add user icon"></i> 用户登录</h1>
        <div class="ui internally celled two column grid">
            <div class="row">
                <div class="column">

                    <form class="ui form" action="auth/login" method="post" >
                        <div class="field">
                            <label>电子邮箱</label>
                            <input type="email" name="email" placeholder="">
                        </div>
                        <div class="field">
                            <label>密码</label>
                            <input type="password" name="password" placeholder="">
                        </div>
                        <button class="ui primary button" type="submit">登录</button>
                        <button class="ui link button" type="button"
                                @click="forgotPassword(event)">忘记密码
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>

</template>

<script>
    import InfoDialog from './InfoDialog'

    export default({
        data () {
            return {
                pageTitle: '用户登录'
            }
        },
        events: {
            onFormComplete: function (form, response) {
                InfoDialog.confirm('你好')
            }
        },
        methods: {
            forgotPassword: function (event) {
            }
        },
        components: {}
    })
</script>

<style>
</style>
