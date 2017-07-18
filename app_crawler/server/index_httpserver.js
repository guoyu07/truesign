//filename: server.js
var utils = require('utils');
var fs = require('fs');

//define ip and port to web service
var ip_server = '127.0.0.1:6600';

//includes web server modules
var server = require('webserver').create();
var proxy_index = 0
var proxy_data = []
//start web server
var service = server.listen(ip_server, function (request, response) {
    var userAgent = [
        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; AcooBrowser; .NET CLR 1.1.4322; .NET CLR 2.0.50727)",
        "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.0; Acoo Browser; SLCC1; .NET CLR 2.0.50727; Media Center PC 5.0; .NET CLR 3.0.04506)",
        "Mozilla/4.0 (compatible; MSIE 7.0; AOL 9.5; AOLBuild 4337.35; Windows NT 5.1; .NET CLR 1.1.4322; .NET CLR 2.0.50727)",
        "Mozilla/5.0 (Windows; U; MSIE 9.0; Windows NT 9.0; en-US)",
        "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; Win64; x64; Trident/5.0; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET CLR 2.0.50727; Media Center PC 6.0)",
        "Mozilla/5.0 (compatible; MSIE 8.0; Windows NT 6.0; Trident/4.0; WOW64; Trident/4.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; .NET CLR 1.0.3705; .NET CLR 1.1.4322)",
        "Mozilla/4.0 (compatible; MSIE 7.0b; Windows NT 5.2; .NET CLR 1.1.4322; .NET CLR 2.0.50727; InfoPath.2; .NET CLR 3.0.04506.30)",
        "Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN) AppleWebKit/523.15 (KHTML, like Gecko, Safari/419.3) Arora/0.3 (Change: 287 c9dfb30)",
        "Mozilla/5.0 (X11; U; Linux; en-US) AppleWebKit/527+ (KHTML, like Gecko, Safari/419.3) Arora/0.6",
        "Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.2pre) Gecko/20070215 K-Ninja/2.1.1",
        "Mozilla/5.0 (Windows; U; Windows NT 5.1; zh-CN; rv:1.9) Gecko/20080705 Firefox/3.0 Kapiko/3.0",
        "Mozilla/5.0 (X11; Linux i686; U;) Gecko/20070322 Kazehakase/0.4.5",
        "Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.9.0.8) Gecko Fedora/1.9.0.8-1.fc10 Kazehakase/0.5.6",
        "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.56 Safari/535.11",
        "Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_3) AppleWebKit/535.20 (KHTML, like Gecko) Chrome/19.0.1036.7 Safari/535.20",
        "Opera/9.80 (Macintosh; Intel Mac OS X 10.6.8; U; fr) Presto/2.9.168 Version/11.52",
        "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.11 TaoBrowser/2.0 Safari/536.11",
        "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.71 Safari/537.1 LBBROWSER",
        "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; .NET4.0E; LBBROWSER)",
        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; QQDownload 732; .NET4.0C; .NET4.0E; LBBROWSER)",
        "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/535.11 (KHTML, like Gecko) Chrome/17.0.963.84 Safari/535.11 LBBROWSER",
        "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; .NET4.0E)",
        "Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; .NET4.0E; QQBrowser/7.0.3698.400)",
        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; QQDownload 732; .NET4.0C; .NET4.0E)",
        "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Trident/4.0; SV1; QQDownload 732; .NET4.0C; .NET4.0E; 360SE)",
        "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; QQDownload 732; .NET4.0C; .NET4.0E)",
        "Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 6.1; WOW64; Trident/5.0; SLCC2; .NET CLR 2.0.50727; .NET CLR 3.5.30729; .NET CLR 3.0.30729; Media Center PC 6.0; .NET4.0C; .NET4.0E)",
        "Mozilla/5.0 (Windows NT 5.1) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1",
        "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.1 (KHTML, like Gecko) Chrome/21.0.1180.89 Safari/537.1",
        "Mozilla/5.0 (iPad; U; CPU OS 4_2_1 like Mac OS X; zh-cn) AppleWebKit/533.17.9 (KHTML, like Gecko) Version/5.0.2 Mobile/8C148 Safari/6533.18.5",
        "Mozilla/5.0 (Windows NT 6.1; Win64; x64; rv:2.0b13pre) Gecko/20110307 Firefox/4.0b13pre",
        "Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:16.0) Gecko/20100101 Firefox/16.0",
        "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.11 (KHTML, like Gecko) Chrome/23.0.1271.64 Safari/537.11",
        "Mozilla/5.0 (X11; U; Linux x86_64; zh-CN; rv:1.9.2.10) Gecko/20100922 Ubuntu/10.10 (maverick) Firefox/3.6.10"
    ]
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
            waitTimeout: 10000,
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
            if (proxy_data.length === 0) {
                this.echo('获取代理数据池')
                proxy_data = this.evaluate(function (wsurl) {
                    while (true) {
                        var get_proxy_url = 'http://127.0.0.1:8000/?types=0&count=100&country=%E5%9B%BD%E5%86%85'
                        var tmp_proxy_data = JSON.parse(__utils__.sendAJAX(get_proxy_url, 'GET', null, false));
                        if (tmp_proxy_data) {

                            break
                        }

                    }
                    return tmp_proxy_data

                });
            }
            else {
                this.echo('存在代理池')
            }

            // this.repeat(1, function () {
            //     if (response_data.response.comment_req.length === 1) {
            //         console.log('获取到足够评论，跳过循环')
            //
            //     }
            //     else {
            //         var proxy_index = Math.floor(Math.random() * (proxy_data.length))
            //
            //         var proxy_url = 'http://' + proxy_data[proxy_index][0] + ':' + proxy_data[proxy_index][1]
            //         this.page.settings.proxy = proxy_url;
            // this.page.settings.proxy = 'http://192.168.2.1:9630';

            // this.echo('设置代理 '+proxy_url)
            casper.thenOpen(response_data.response.source, function () {
                this.echo('检查url')
                this.echo(response_data.response.source)

                this.echo('检查IP')
                var new_ip = this.evaluate(function () {
                    var test_ip = 'http://iamsee.com:5001/index/getrequest?app=i_app'
                    return __utils__.sendAJAX(test_ip, 'GET', false, false);
                })
                this.echo(new_ip)
                console.log('准备提取评论request jsonp url')
                // console.log()
                var s = ''
                fs.write('debug.txt', s, 'w');
                fs.write('debug.txt', this.getPageContent(), 'a');
                // console.log(this.getElementAttribute('#reviews', 'data-listapi'))
                // obj.capture('imgs/1.png');
                item_comment_req = {}
                item_comment_req.request_url = 'http:' + this.getElementAttribute('#reviews', 'data-listapi') + '&currentPageNum=1&pageSize=50&rateType=1'
                console.log(item_comment_req.request_url)
                // item_comment_req.request_url = 'http://iamsee.com:5001/index/getrequest?app=i_app'

                this.repeat(5, function () {
                    if (response_data.response.comment_req.length === 1) {
                        console.log('获取到足够评论，跳过循环')
                    }
                    else {
                        item_comment_req.comment_data = ''
                        item_comment_req.request_url = item_comment_req.request_url.replace(/currentPageNum=1/, 'currentPageNum=' + (response_data.response.comment_req.length+1))

                        console.log('获取并解析第　 ' + (response_data.response.comment_req.length + 1) + '　页评论')
                        casper.thenOpen(item_comment_req.request_url, function () {
                            var body_info = this.getHTML('body');
                            this.echo(body_info)
                            fs.write('debug.txt', body_info, 'w');
                            body_info = iGetInnerText(body_info)
                            body_info = body_info.replace(/\(/, '')
                            body_info = body_info.replace(/\)/, '')
                            body_info = eval('[' + body_info + ']')
                            if (body_info[0].hasOwnProperty('rgv587_flag')) {
                                this.echo('出现问题，进行检查')
                                this.echo('检查IP')

                                var comment_proxy_ip = this.evaluate(function () {
                                    // var  ip_cn_result = __utils__.sendAJAX('http://ip.cn', 'GET', false);
                                    // var  test_ip = ''
                                    // test_ip = ip_cn_result.match(/<code>\S*<\/code>/)[0]
                                    // return test_ip
                                    var  ip_cn_result = __utils__.sendAJAX('http://iamsee.com:5001/index/getrequest?app=i_app', 'GET', false);
                                    return ip_cn_result


                                })
                                this.echo(comment_proxy_ip)

                                this.echo('检查UA')
                                var new_ua = casper.evaluate(function () {
                                    return window.navigator.userAgent;
                                });
                                this.echo(new_ua)


                                // utils.dump(proxy_data)
                                var proxy_index = Math.floor(Math.random() * (proxy_data.length))

                                var proxy_url = 'http://' + proxy_data[proxy_index][0] + ':' + proxy_data[proxy_index][1]
                                this.page.settings.proxy = proxy_url;
                                this.page.settings.proxy = 'http://192.168.2.1:9630';



                                var ua_index = Math.floor(Math.random() * (userAgent.length));
                                var currect_ua = userAgent[ua_index]
                                this.echo('IP封禁，设置代理 =>' + proxy_url + ' index=>' + proxy_index)
                                this.echo('UA封禁，设置UA =>' + currect_ua + ' index=>' + ua_index)
                                casper.userAgent(currect_ua)
                                // response_data.response.comment_req.push({'disc':'越过'})

                                // response_data.response.comment_req.push({'desc':'轮空'})
                            }
                            else {
                                this.echo('获取到评论数据')
                                item_comment_req.comment_data = body_info[0].comments
                                response_data.response.comment_req.push(item_comment_req)
                            }
                        })
                    }
                })
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