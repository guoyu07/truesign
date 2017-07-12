<template>
    <div class="top_router_view" style="background-color: white;margin-top: 60px">
        <vue-editor v-model="content" :editorToolbar="customToolbar"></vue-editor>


    </div>
</template>

<script>
    import { VueEditor } from 'vue2-editor'

    export default {
        data() {
            return {
                content: '<h1>Some initial content</h1>',
                customToolbar: [
//                    ['bold', 'italic', 'underline', 'strike'],        // toggled buttons
//                    ['blockquote', 'code-block'],

                    [{ 'header': 1 }, { 'header': 2 }],               // custom button values
//                    [{ 'list': 'ordered' }, { 'list': 'bullet' }],
//                    [{ 'script': 'sub' }, { 'script': 'super' }],      // superscript/subscript
//                    [{ 'indent': '-1' }, { 'indent': '+1' }],          // outdent/indent
//                    [{ 'direction': 'rtl' }],                         // text direction

//                    [{ 'size': ['small', false, 'large', 'huge'] }],  // custom dropdown
//                    [{ 'header': [1, 2, 3, 4, 5, 6, false] }],

                    [{ 'color': [] }],          // dropdown with defaults from theme
                    [{ 'align': [] }],

//                    ['clean']

                ]
            }
        },
        methods: {

        },
        components:{
            VueEditor,

        },
    }
</script>

<style >

</style>