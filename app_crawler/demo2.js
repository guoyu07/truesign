/**
 * Created by Administrator on 2017/6/20.
 */
var casper = require('casper').create();
casper.start();
casper.thenOpen('http://www.baidu.com/', function () {
    casper.captureSelector('baidu.png', 'html');
});
casper.run();