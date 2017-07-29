/**
 * Created by Administrator on 2017/6/20.
 */

phantom.outputEncoding="UTF8";
var casper = require('casper').create();

// phantom.setProxy('127.0.0.1','9630');
// casper.options.pageSettings.proxy = 'http://192.168.191.1:9630';
casper.start('http://iamsee.com:5001/index/getrequest?app=i_app', function() {

    this.echo(this.getPageContent());
});
casper.then(function () {
    // this.page.settings.proxy = 'http://192.168.191.1:9630';
    casper.thenOpen('http://iamsee.com:5001/index/getrequest?app=i_app', function () {
        this.echo(this.getPageContent());
    })
});
casper.run(function() {
    this.exit();
});


