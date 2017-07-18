/**
 * Created by Administrator on 2017/6/20.
 */

phantom.outputEncoding = "UTF8";
var casper = require('casper').create();

// phantom.setProxy('127.0.0.1','9630');
// casper.options.pageSettings.proxy = 'http://192.168.191.1:9630';
casper.start().then(function () {
    this.echo('starting')
})
casper.then(function () {

    casper.thenOpen('http://www.baidu.com', function () {
        this.repeat(10, function () {
            var new_ip = this.evaluate(function (wsurl) {
                var test_ip = 'http://iamsee.com:5001/index/getrequest?app=i_app'
                return __utils__.sendAJAX(test_ip, 'GET', false, false);
            })
            this.echo(new_ip)
        })
    })


});
casper.run(function () {
    this.exit();
});


