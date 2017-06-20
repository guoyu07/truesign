<template>
    <div class="top_mobile_view">
        <mt-navbar v-model="selected">
            <mt-tab-item id="1">选项一</mt-tab-item>
            <mt-tab-item id="2">选项二</mt-tab-item>
            <mt-tab-item id="3">选项三</mt-tab-item>
        </mt-navbar>

        <!-- tab-container -->
        <mt-tab-container v-model="selected">
            <mt-tab-container-item id="1">
                <mt-cell v-for="n in 10" :key="n" :title="'内容 ' + n" />
            </mt-tab-container-item>
            <mt-tab-container-item id="2">
                <mt-cell v-for="n in 4" :key="n" :title="'测试 ' + n" />
            </mt-tab-container-item>
            <mt-tab-container-item id="3">
                <mt-cell v-for="n in 6" :key="n" :title="'选项 ' + n" />
            </mt-tab-container-item>
        </mt-tab-container>
        <mt-picker :slots="slots" @change="onValuesChange"></mt-picker>
    </div>
</template>


<script>
    require('../../../../../static/css/mobile_style.css')

    export default {
        data(){
            return {
                slots: [
                    {
                        flex: 1,
                        values: ['2015-01', '2015-02', '2015-03', '2015-04', '2015-05', '2015-06'],
                        className: 'slot1',
                        textAlign: 'right'
                    }, {
                        divider: true,
                        content: '-',
                        className: 'slot2'
                    }, {
                        flex: 1,
                        values: ['2015-01', '2015-02', '2015-03', '2015-04', '2015-05', '2015-06'],
                        className: 'slot3',
                        textAlign: 'left'
                    }
                ],
                selected:''
            }
        },
        props: {},
        components: {

        },
        computed: {},
        created(){

        },
        mounted(){


        },
        beforeDestroy(){

        },
        methods: {
            onValuesChange(picker, values) {
                if (values[0] > values[1]) {
                    picker.setSlotValue(1, values[0]);
                }
            }
        },

    }
</script>
<style>


</style>
