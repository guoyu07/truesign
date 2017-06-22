/**
 * Created by Administrator on 2017/6/20.
 */
phantom.outputEncoding="GBK";
var casper = require('casper').create();

casper.start('http://www.baidu.com/', function() {
    this.echo(this.getTitle());
});

casper.thenOpen('http://www.163.com/', function() {
    this.echo(this.getTitle());
});

casper.run();