<template xmlns:v-on="http://www.w3.org/1999/xhtml">
    <body>
    <div id="semantic_app" >
        <div class="ui cards">
            <div class="card">
                <div class="content">
                    <div class="header">Elliot Fu</div>
                    <div class="description">Elliot Fu is a film-maker from New York. </div>
                </div>
                <div class="ui bottom attached button"><i class="add icon"></i> Add Friend </div>
            </div>
            <div class="card">
                <div class="content">
                    <div class="header">Veronika Ossi</div>
                    <div class="description">Veronika Ossi is a set designer living in New York who enjoys kittens, music, and partying. </div>
                </div>
                <div class="ui bottom attached button"><i class="add icon"></i> Add Friend </div>
            </div>
            <div class="card">
                <div class="content">
                    <div class="header">Jenny Hess</div>
                    <div class="description">Jenny is a student studying Media Management at the New School </div>
                </div>
                <div class="ui bottom attached button"><i class="add icon"></i> Add Friend </div>
            </div>
        </div>
        <div class="ui search">
            <div class="ui icon input">
                <input class="prompt" type="text" placeholder="Search countries...">
                <i class="search icon"></i>
            </div>
            <div class="results"></div>
        </div>
    </div>
    </body>
</template>

<script>


    export default({
        data () {
            return {
            }
        },
        mounted () {
        }
    })
</script>

<style>

</style>
