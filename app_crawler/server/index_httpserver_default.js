/**
 * Created by Administrator on 2017/6/20.
 */
var utils = require('utils');

phantom.outputEncoding = "UTF8";
var casper = require('casper').create();

// phantom.setProxy('127.0.0.1','9630');
// casper.options.pageSettings.proxy = 'http://192.168.191.1:9630';
// casper.start('http://baidu.com/', function() {
//
// });
casper.start('http://localhost:8000/?types=0&count=100&country=%E5%9B%BD%E5%86%85').then(function () {

    var proxy_data = JSON.parse(this.getHTML('pre'))
    this.repeat(10, function () {
        var proxy_index = Math.floor(Math.random() * (proxy_data.length))

        var proxy_url = 'http://' + proxy_data[proxy_index][0] + ':' + proxy_data[proxy_index][1]
        this.page.settings.proxy = proxy_url;
        this.echo('代理设置成 ' + proxy_url)

        casper.thenOpen('http://iamsee.com:5001/index/getrequest?app=i_app', function () {
            this.echo(this.getHTML('body'))
        })
    })

})


casper.run(function () {
    return true
});


