<template>
    <div class="mobile_aboutus">

    </div>

</template>


<script>
    export default {
        components: {

        },
        directives: {
            TransferDom
        },
        data () {
            return {
                show1: false,
                menus1: {
                    menu1: 'Share to friends',
                    menu2: 'Share to timeline'
                },
                show2: false,
                menus2: {
                    menu1: 'Take Photo',
                    menu2: 'Choose from photos'
                },
                show3: false,
                show4: false,
                show5: false,
                show6: false,
                show7: false,
                show8: false,
                menus5: [{
                    label: 'actionsheet header',
                    type: 'info'
                }, {
                    label: 'Primary',
                    type: 'primary',
                    value: 'primary'
                }, {
                    label: 'Warn',
                    type: 'warn'
                }, {
                    label: 'Disabled',
                    type: 'disabled'
                }, {
                    label: 'Default'
                }],
                menu7: {
                    menu1: '北京烤鸭',
                    menu2: '陕西油泼面',
                    menu3: '西安肉夹馍'
                },
                showSuccess: false,
                menus3: {
                    'title.noop': 'actionsheet header',
                    delete: '<span style="color:red">Delete</span>'
                },
                menus8: {
                    menu1: 'Close me',
                    menu2: 'Close me'
                }
            }
        },
        methods: {
            demo8doClose () {
                this.$vux.loading.show({
                    text: 'processing'
                })
                setTimeout(() => {
                    this.$vux.loading.hide()
                    this.show8 = false
                }, 1000)
            },
            console (msg) {
                console.log(msg)
            },
            click (key) {
                console.log(key)
            },
            onDelete () {
                this.showSuccess = true
            }
        }
    }
</script>

<style>
    .popup0 {
        padding-bottom:15px;
        height:200px;
    }
    .popup1 {
        width:100%;
        height:100%;
    }
</style>
