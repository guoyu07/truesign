var casper = require('casper').create();
casper.options.pageSettings.proxy = 'http://192.168.2.1:9630';

casper.start('http://casperjs.org/', function() {
    this.echo(this.getTitle());
});

casper.thenOpen('http://phantomjs.org', function() {
    this.echo(this.getTitle());
});

casper.run();