//filename: server.js
var utils = require('utils');
//define ip and port to web service
var ip_server = '127.0.0.1:6600';

//includes web server modules
var server = require('webserver').create();

//start web server
var service = server.listen(ip_server, function (request, response) {
    var $ = require('../node_modules/jquery/dist/jquery.min.js')
    var searchkey = ''
    var item_comment_req = {}
    var response_data = {}
    var i = 1
    var casper = require('casper').create(
        {
            clientScripts: [
                'lib/jquery.min.js',
            ],
            pageSettings: {
                webSecurityEnabled: false,
                loadImages: false,
                loadPlugins: false
            },

            logLevel: 'debug',
            verbose: false,
            waitTimeout: 1000000,
            // onWaitTimeout: handleWaitTimeout,
            // onTimeout: handleTimeout,
            // onError: handleError,

        }
    );
    var search_url = {
        taobao: 'https://s.taobao.com/search?imgfile=&js=1&stats_click=search_radio_all%3A1&initiative_id=staobaoz_20170713&ie=utf8&sort=sale-desc&q=',
        jd: ''
    }
    casper.start().then(function () {

        if (request.method === 'POST') {
            searchkey = request.post.searchkey
            console.log('request.method->', request.method)
            utils.dump(request.post.searchkey)
            utils.dump(request.postRaw)
        }
        response_data = {status: true, response: {comment_req: []}}
        response_data.response.searchkey = searchkey


    })
    casper.then(function () {
        // this.page.settings.proxy = 'http://192.168.191.1:9630';
        console.log('准备根据 searchkey:' + response_data.response.searchkey + ' 爬取销量最高商品的URL')
        var build_search_url = search_url.taobao + response_data.response.searchkey
        console.log(build_search_url)
        casper.thenOpen(build_search_url, function () {

            links = this.evaluate(getLinks_BySearch);
            response_data.response.source = 'http:' + links


        })


    });
    casper.then(function () {

        if (response_data.response.source.indexOf('taobao') >= 0) {
            console.log('获取到 【淘宝】 商品连接 ' + response_data.response.source)

            casper.thenOpen(response_data.response.source, function () {

                console.log('准备提取评论request jsonp url')
                item_comment_req = {}
                item_comment_req.request_url = 'http:' + this.getElementAttribute('#reviews', 'data-listapi') + '&currentPageNum=1&pageSize=50&rateType=1'
                console.log(item_comment_req.request_url)
                item_comment_req.request_url = 'http://iamsee.com:5001/index/getrequest?app=i_app'
                this.repeat(50, function() {
                    if(response_data.response.comment_req.length === 50){
                        console.log('获取到足够评论，跳过循环')
                        return
                    }
                    else{
                        console.log('获取并解析第　 ' + (response_data.response.comment_req.length + 1) + '　页评论')
                        casper.thenOpen(item_comment_req.request_url,function () {
                            var body_info = this.getHTML('body');
                            body_info = iGetInnerText(body_info)
                            body_info = body_info.replace(/\(/, '')
                            body_info = body_info.replace(/\)/, '')
                            body_info = eval('[' + body_info + ']')
                            utils.dump(body_info)
                            if (body_info[0].hasOwnProperty('rgv587_flag')) {
                                this.page.settings.proxy = 'http://192.168.2.1:9630';
                                response_data.response.comment_req.push({'desc':'轮空'})
                            }
                            else {
                                console.log(body_info)
                                item_comment_req.comment_data = body_info[0].comments
                                response_data.response.comment_req.push(item_comment_req)
                                item_comment_req.request_url = item_comment_req.request_url.replace(/currentPageNum=1/, 'currentPageNum=' + response_data.response.comment_req.length + 2)
                                item_comment_req.comment_data = ''
                            }
                        })
                    }

                })
                // while (response_data.response.comment_req.length < 2) {

                    // var test = that.evaluate(function(item_comment_req, response_data,that) {
                    //     console.log(11111111)
                    //     // casper.thenOpen(item_comment_req.request_url, function (){
                    //     //     console.log(this.this.getPageContent();)
                    //     // })
                    // }, item_comment_req, response_data,that);
                    // (function (item_comment_req, that) {
                    //
                    //     casper.thenOpen(item_comment_req.request_url, function () {
                    //
                    //         console.log('获取并解析第　 ' + (response_data.response.comment_req.length + 1) + '　页评论')
                    //
                    //         var body_info = this.getHTML('body');
                    //         body_info = iGetInnerText(body_info)
                    //         body_info = body_info.replace(/\(/, '')
                    //         body_info = body_info.replace(/\)/, '')
                    //         body_info = eval('[' + body_info + ']')
                    //         // utils.dump(body_info)
                    //         if (body_info[0].hasOwnProperty('rgv587_flag')) {
                    //             console.log('更换代理')
                    //         }
                    //         else {
                    //             item_comment_req.comment_data = body_info[0].comments
                    //             response_data.response.comment_req.push(item_comment_req)
                    //             item_comment_req.request_url = item_comment_req.request_url.replace(/currentPageNum=1/, 'currentPageNum=' + response_data.response.comment_req.length + 2)
                    //             item_comment_req.comment_data = ''
                    //             current += 1
                    //         }
                    //
                    //     })
                    // })(item_comment_req, that);
                    // (function(response_data,item_comment_req) {
                    //         console.log(1)
                    //         response_data = response_data.push({aa:'aa'})
                    // console.log('comment_req length=>', response_data.response.comment_req.length)
                    // casper.thenOpen(item_comment_req.request_url, function () {
                    //
                    //     console.log('获取并解析第　 ' + (response_data.response.comment_req.length + 1) + '　页评论')
                    //
                    //     var body_info = this.getHTML('body');
                    //     body_info = iGetInnerText(body_info)
                    //     body_info = body_info.replace(/\(/, '')
                    //     body_info = body_info.replace(/\)/, '')
                    //     body_info = eval('[' + body_info + ']')
                    //     // utils.dump(body_info)
                    //     if (body_info[0].hasOwnProperty('rgv587_flag')) {
                    //         console.log('更换代理')
                    //     }
                    //     else {
                    //         item_comment_req.comment_data = body_info[0].comments
                    //         response_data.response.comment_req.push(item_comment_req)
                    //         item_comment_req.request_url = item_comment_req.request_url.replace(/currentPageNum=1/, 'currentPageNum=' + response_data.response.comment_req.length + 2)
                    //         item_comment_req.comment_data = ''
                    //         current += 1
                    //     }
                    //
                    // })
                    // },response_data,item_comment_req)

                // }


                // var ajax_comment = this.evaluate(function (url) {
                //     console.log('url->'+url)
                //     var comment_response = ''
                //     $.ajax({
                //         async: false,
                //         url: url,      //跨域到http://www.wp.com，另，http://test.com也算跨域
                //         type:'GET',                                //jsonp 类型下只能使用GET,不能用POST,这里不写默认为GET
                //         // dataType:'jsonp',                          //指定为jsonp类型
                //         success:function(result){
                //             console.log('work!')
                //             console.log('jsonp_response_comment'+JSON.stringify(result))
                //             comment_response =  JSON.stringify(result)
                //         },
                //         error:function(msg){
                //             console.log(msg)
                //             comment_response =  JSON.stringify(msg)
                //         }
                //     });
                //     // return __utils__.sendAJAX('http://iamsee.com:5001/index/getrequest?app=i_app', "GET" , null, false, { contentType: "text/html;charset=GBK" })
                //     return comment_response
                // },item_comment_req.request_url);


            })
        }
        else if (response_data.response.source.indexOf('tmall') >= 0) {
            console.log('获取到 【天猫】 商品连接 ' + response_data.response.source)
            if (false) {
                console.log('修改天猫商品链接为手机端天猫商品连接')
                response_data.response.source = response_data.response.source.replace(/detail\./, 'detail.m.')
                console.log(response_data.response.source)
                casper.thenOpen(response_data.response.source, function () {

                    console.log('准备提取评论request jsonp url')
                    casper.waitForSelector('header a:nth-child(3)', function () {
                        console.log('评论链接出现，准备点击')
                        var click_value = this.getHTML('header a:nth-child(3)')
                        this.click('header a:nth-child(3)');

                        this.click('div.toshop');
                        // this.mouseEvent('click', 'div.toshop', "20%", "50%");
                        var page_content = this.getPageContent();
                        out2png(this, page_content)

                    });

                })
            }
            else {
                console.log('进行tmall电脑端爬取')
                console.log(response_data.response.source)
                casper.thenOpen(response_data.response.source, function () {
                    console.log('准备提取评论request jsonp url')
                    var links = document.querySelectorAll('head script');
                    var currect_link = Array.prototype.map.call(links, function (e) {
                        if (typeof e.getAttribute('src') === 'string') {
                            return e.getAttribute('src')
                        }
                    })
                    out2png(this, this.getPageContent())

                    this.click('#J_ItemRates')
                    casper.waitFor(function check() {
                        return this.evaluate(function () {
                            console.log('debug---------------------------')

                            var links = document.querySelectorAll('head script');
                            var currect_link = Array.prototype.map.call(links, function (e) {
                                if (typeof e.getAttribute('src') === 'string') {
                                    return e.getAttribute('src').indexOf('adapter.js')
                                }
                            })
                            console.log(currect_link)
                            for (var item in currect_link) {
                                if (currect_link[item] >= 0) {
                                    return true
                                }
                            }
                            // console.log($('.rate-grid')[0].innerHTML)

                        });
                    }, function then() {
                        this.echo('第一次加载完成，等待点击进入第二次加载')
                        if (this.exists('#J_TabBar li')) {
                            console.log('存在评论点击链接，准备点击')
                            this.click('#J_ItemRates')
                            this.click('#J_TabBar li:nth-child(2)')
                            out2png(this, this.getPageContent())
                            casper.waitFor(function check() {
                                return this.evaluate(function () {
                                    console.log('debug 第二次加载中---------------------------')

                                    var links = document.querySelectorAll('head script');
                                    var currect_link = Array.prototype.map.call(links, function (e) {
                                        if (typeof e.getAttribute('src') === 'string') {
                                            return e.getAttribute('src').indexOf('&currentPage=1&append=0&content=1&tagId=&posi=&picture=&ua')
                                        }
                                    })
                                    console.log(currect_link)
                                    for (var item in currect_link) {
                                        if (currect_link[item] >= 0) {
                                            return true
                                        }
                                    }
                                    // console.log($('.rate-grid')[0].innerHTML)

                                });
                            }, function then() {
                                console.log('评论数据加载完成')
                                var currect_links = this.evaluate(getlinksBycurrectDom)
                                // console.log(currect_links)
                                // console.log(typeof currect_links)
                                for (var item in currect_links) {

                                    if (currect_links[item].indexOf('&currentPage=1&append=0&content=1&tagId=&posi=&picture=&ua') >= 0) {
                                        var jsonp_url = currect_links[item]
                                        console.log('提取到jsonp 评论url => ')
                                        // var jsonp_fun = jsonp_url.match(/callback=\S*/)[0].replace(/callback=/,'')
                                        var jsonp_fun = jsonp_url.match(/callback=\S*/)[0]
                                        jsonp_url = jsonp_url.replace(jsonp_fun, '')
                                        var item_comment_req = {}
                                        item_comment_req.request_url = jsonp_url
                                        console.log(item_comment_req.request_url)

                                        // this.evaluate(function (url) {
                                        //     console.log('url->'+url)
                                        //     $.ajax({
                                        //         async: false,
                                        //         url: url,      //跨域到http://www.wp.com，另，http://test.com也算跨域
                                        //         type:'GET',                                //jsonp 类型下只能使用GET,不能用POST,这里不写默认为GET
                                        //         dataType:'jsonp',
                                        //         // jsonpCallback:jsonp_fun,
                                        //         success:function(result){
                                        //             console.log('work!')
                                        //             console.log('jsonp_response_comment'+JSON.stringify(result))
                                        //
                                        //         },
                                        //         error:function(msg){
                                        //             console.log('error')
                                        //             console.log(JSON.stringify(msg))
                                        //         },
                                        //
                                        //     });
                                        //     function jsonp_success(data) {
                                        //         console.log('jsonp_success',data)
                                        //     }
                                        // },jsonp_url);
                                        casper.thenOpen(item_comment_req.request_url, function () {
                                            this.echo('获取并解析第　１　页评论')
                                            var body_info = this.getHTML('body');
                                            body_info = iGetInnerText(body_info)
                                            body_info = body_info.replace(/"rateDetail":/, '')

                                            body_info = eval('[' + body_info + ']')
                                            // body_info = JSON.stringify(body_info)
                                            item_comment_req.comment_data = body_info[0].rateList
                                            response_data.response.comment_req.push(item_comment_req)

                                            item_comment_req.request_url = item_comment_req.request_url.replace(/currentPage=1/, 'currentPage=2')
                                            item_comment_req.comment_data = ''

                                        })
                                        casper.thenOpen(item_comment_req.request_url, function () {
                                            this.echo('获取并解析第　２　页评论')

                                            var body_info = this.getHTML('body');
                                            body_info = iGetInnerText(body_info)
                                            body_info = body_info.replace(/"rateDetail":/, '')

                                            body_info = eval('[' + body_info + ']')
                                            // body_info = JSON.stringify(body_info)
                                            item_comment_req.comment_data = body_info[0].rateList
                                            response_data.response.comment_req.push(item_comment_req)
                                            item_comment_req.request_url = item_comment_req.request_url.replace(/currentPage=1/, 'currentPage=3')
                                            item_comment_req.comment_data = ''
                                        })
                                        casper.thenOpen(item_comment_req.request_url, function () {
                                            this.echo('获取并解析第　 3　页评论')

                                            var body_info = this.getHTML('body');
                                            body_info = iGetInnerText(body_info)
                                            body_info = body_info.replace(/"rateDetail":/, '')

                                            body_info = eval('[' + body_info + ']')
                                            // body_info = JSON.stringify(body_info)
                                            item_comment_req.comment_data = body_info[0].rateList
                                            response_data.response.comment_req.push(item_comment_req)
                                        })
                                        console.log('done')
                                    }
                                }
                            })
                        }
                    });


                })
            }

        }


    })


    // }
    // casper.on('remote.message', function(remote_console) {
    //
    //     console.log('remote->',remote_console)
    //     var remote_console = remote_console
    //     response_data.aaaa =  remote_console
    //     if(remote_console.indexOf('jsonp_response_comment')>=0){
    //         response_data.bbbbbbb = 'bbbbbbbb'
    //
    //         console.log('接收到jsonp 评论数据')
    //         var build_remote_console = remote_console.replace(/jsonp_response_comment/,'')
    //         console.log('------------------------')
    //
    //         if(isJSON(build_remote_console)){
    //             console.log('获取到评论数据!')
    //             response_data.comment_data = build_remote_console
    //             out2png(this,build_remote_console)
    //
    //         }
    //         else{
    //             console.log('IP封禁!')
    //
    //         }
    //     }
    //     if(remote_console.indexOf('debug')>=0){
    //         // console.log('remote->debug->',remote_console)
    //     }
    // })
    casper.on('error', function (err) {
        console.log('err', err)
    })
    casper.on('timeout', function (out) {
        console.log('timeout', out)
    })


    function getLinks_BySearch() {
        var links = document.querySelectorAll('.items div.J_MouserOnverReq[data-index="0"] div.pic a');
        return Array.prototype.map.call(links, function (e) {
            return e.getAttribute('href');
        });

    }

    function getlinksBycurrectDom(selector, attr) {
        var links = document.querySelectorAll('head script');
        return Array.prototype.map.call(links, function (e) {
            return e.getAttribute('src');
        });
    }

    function isJSON(str) {
        if (typeof str == 'string') {
            try {
                var obj = JSON.parse(str);
                if (str.indexOf('{') > -1) {
                    return true;
                } else {
                    return false;
                }

            } catch (e) {
                console.log(e);
                return false;
            }
        }
        return false;
    }

    function iGetInnerText(testStr) {
        var resultStr = testStr.replace(/\ +/g, ""); //去掉空格
        resultStr = testStr.replace(/[ ]/g, "");    //去掉空格
        resultStr = testStr.replace(/[\r\n]/g, ""); //去掉回车换行
        return resultStr;
    }

    function handleWaitTimeout(data) {
        console.log('handleWaitTimeout', data)
    }

    function handleTimeout(data) {
        console.log('handleTimeout', data)
    }

    function handleError(data) {
        console.log('handleError', data)
    }

    function out2png(obj, info) {
        console.log('准备发送调试信息')
        var imgname = i + ".png"
        obj.capture('imgs/' + imgname);
        i++
        console.log('发送调试信息完成')
    }

    casper.run(function () {
        // response.setHeader('Content-Type', 'text/html; charset=utf-8');
        response.statusCode = 200;

        if (request.method === 'POST') {
            searchkey = request.post.searchkey
            if (searchkey) {
                response.write(JSON.stringify(response_data))

            }
            else {

                response.write(JSON.stringify({status: false, response: '', desc: 'searchkey不得为空'}))
            }
        }
        else {
            response.write(JSON.stringify({status: false, response: '', desc: '只允许POST提交'}))
        }
        response.close()

    });

});
console.log('Server running at http://' + ip_server + '/');