<template xmlns:v-on="http://www.w3.org/1999/xhtml">
    <body>
    <div id="semantic_app" >
        <div class="ui icon message">
            <i class="notched circle loading icon"></i>
            <div class="content">
                <div class="header">Just one second </div>
                <p>We're fetching that content for you.</p>
            </div>
        </div>
        <div class="ui message transition ">
            <i class="close icon"></i>
            <div class="header">Welcome back! </div>
            <p>This is a special notification which you can dismiss if you're bored with it.</p>
        </div>
        <div style="display: inline-block" class="ui icon input loading">
            <input type="text" placeholder="Search...">
            <i class="search icon"></i>
        </div>
        <div style="display: inline-block" class="ui left icon input loading">
            <input type="text" placeholder="Search...">
            <i class="search icon"></i>
        </div>
        <div class="ui inverted segment">
            <div class="ui active inverted loader"></div>
            <br>
            <br>
            <br>
            <br>
        </div>

        <div class="ui top attached menu">
            <div class="ui dropdown icon item">
                <i class="wrench icon"></i>
                <div class="menu">
                    <div class="item">
                        <i class="dropdown icon"></i>
                        <span class="text">New</span>
                        <div class="menu">
                            <div class="item">Document</div>
                            <div class="item">图片</div>
                        </div>
                    </div>
                    <div class="item">Open... </div>
                    <div class="item">Save... </div>
                    <div class="item">Edit Permissions</div>
                    <div class="divider"></div>
                    <div class="header">Export </div>
                    <div class="item">Share... </div>
                </div>
            </div>
            <div class="right menu">
                <div class="ui right aligned category search item">
                    <div class="ui transparent icon input">
                        <input class="prompt" type="text" placeholder="Search animals...">
                        <i class="search link icon"></i>
                    </div>
                    <div class="results"></div>
                </div>
            </div>
        </div>

        <div class="ui bottom attached segment">
            <button id="show_bar" class="ui button">Follow</button>
        </div>
        <hr>
        <h3>遮罩</h3>
        <div class="dimmer demo">
            <div class="ui segment">
                <h3 class="ui header">
                    遮罩层区
                </h3>
                <div class="ui small images">
                    <img>
                    <img>
                    <img>
                </div>
                <p></p>
                <div class="ui dimmer"></div>
            </div>
            <div class="ui ignored ignored icon buttons">
                <div class="ui show button"><i class="plus icon"></i></div>
                <div class="ui hide button"><i class="minus icon"></i></div>
            </div>
        </div>
        <div class="dimmer demo">
            <div class="ui segment">
                <h3 class="ui header">
                    反色遮罩层
                </h3>
                <div class="ui small images">
                    <img>
                    <img>
                    <img>
                </div>
                <p></p>
                <div class="ui inverted dimmer"></div>
            </div>
            <div class="ui ignored ignored icon buttons">
                <div class="ui show button"><i class="plus icon"></i></div>
                <div class="ui hide button"><i class="minus icon"></i></div>
            </div>
        </div>
        <div class="dimmer demo">
            <div class="ui segment">
                <h3 class="ui header">
                    内容遮罩
                </h3>
                <div class="ui small images">
                    <img>
                    <img>
                    <img>
                </div>
                <p></p>
                <div class="ui dimmer">
                    <div class="content">
                        <div class="center">
                            <h2 class="ui inverted icon header">
                                <i class="heart icon"></i>
                                遮罩信息！
                            </h2>
                        </div>
                    </div>
                </div>
            </div>
            <div class="ui ignored ignored icon buttons">
                <div class="ui show button"><i class="plus icon"></i></div>
                <div class="ui hide button"><i class="minus icon"></i></div>
            </div>
        </div>
        <div class="dimmer demo">
            <div class="ui page button">
                <i class="plus icon"></i> 页面遮罩
            </div>
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
            $('.message .close')
                .on('click', function() {
                    $(this)
                        .closest('.message')
                        .transition('fade')
                    ;
                })
            ;
            $('.ui.basic.modal')
                .modal('show')
            ;
            $('#show_bar').on('click',function () {
                $('.ui.sidebar')
                    .sidebar('toggle')
                ;
            })
            var
                $pageDimmer = $('.demo.page.dimmer'),
                $demo       = $('.dimmer.demo'),
                $showButton = $demo.find('.show.button'),
                $pageButton = $demo.find('.page.button'),
                $hideButton = $demo.find('.hide.button'),
                // alias
                handler
                ;
            // event handlers
            handler = {
                show: function() {
                    $(this)
                        .closest('.demo')
                        .find('.segment')
                        .dimmer('show')
                    ;
                },
                hide: function() {
                    $(this)
                        .closest('.demo')
                        .find('.segment')
                        .dimmer('hide')
                    ;
                },
                page: function() {
                    $('body> #semantic_app')
                        .dimmer('show')
                    ;
                }
            };
            $pageDimmer
                .dimmer()
            ;
            $pageButton
                .on('click', handler.page)
            ;
            $showButton
                .on('click', handler.show)
            ;
            $hideButton
                .on('click', handler.hide)
            ;
        }
    })
</script>

<style>
    .dimmer.demo {
        margin: 2em 0em;
    }
    h4 + .dimmer.demo {
        margin-top: 0em;
    }
</style>
