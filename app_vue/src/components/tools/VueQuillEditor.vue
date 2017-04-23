<template>
    无法定义工具栏（有问题暂且搁置 改用 vue2-editor）
    <div style="margin-top: 60px">

            <div class="quill-editor-example">
                <div id="toolbar" slot="toolbar">
                    <!-- Add a bold button -->
                    <button class="ql-bold">Bold</button>
                    <button class="ql-italic">Italic</button>
                    <!-- Add font size dropdown -->
                    <select class="ql-size">
                        <option value="small"></option>
                        <!-- Note a missing, thus falsy value, is used to reset to default -->
                        <option selected></option>
                        <option value="large"></option>
                        <option value="huge"></option>
                    </select>
                    <!-- Add subscript and superscript buttons -->
                    <button class="ql-script" value="sub"></button>
                    <button class="ql-script" value="super"></button>

                </div>
                <!-- quill-editor -->
                <quill-editor v-model="content"
                              :options="editorOption">


                </quill-editor>
            </div>

    </div>
</template>

<script>
    import Quill from 'quill'
    import VueQuillEditor from 'vue-quill-editor'
    import { quillEditor } from 'vue-quill-editor'
    export default {
        data() {
            return {
                name: 'custom-toolbar-example',
                content: '<h2>I am Example 3</h2>',
                editorOption: {
                    modules: {
                        toolbar: '#toolbar'
                    }
                }
            }
        },
        methods: {

        },
        components:{
            quillEditor,

        },
    }
</script>

<style >

</style>