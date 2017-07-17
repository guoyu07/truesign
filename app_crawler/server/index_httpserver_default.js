/**
 * Created by Administrator on 2017/6/20.
 */

phantom.outputEncoding="UTF8";
var casper = require('casper').create();

// phantom.setProxy('127.0.0.1','9630');
// casper.options.pageSettings.proxy = 'http://192.168.191.1:9630';
casper.start().then(function () {
    this.echo('starting')
})
casper.then(function () {
    // this.page.settings.proxy = 'http://192.168.191.1:9630';
    var url='https://rate.tmall.com/list_detail_rate.htm?itemId=539727675733&spuId=421828632&sellerId=882810118&order=3&currentPage=1&append=0&content=1&tagId=&posi=&picture=&ua=118UW5TcyMNYQwiAiwQRHhBfEF8QXtHcklnMWc%3D%7CUm5Ockp3SnVOck9xSnRPdSM%3D%7CU2xMHDJ5HzNPKUosSzVTfxVuCXcNYBs1FTtSNBhkAmEHYB54VCtGPUcmQScJKQdBIEY6S2UzZQ%3D%3D%7CVGhXd1llXWBdYlllWGZdY1hiVWhKfkVxRHxCeURxRH9LdkpxTmA2%7CVWldfS0RMQgzDy8bOxVwUSsKKw4oDHZYDlg%3D%7CVmhIGCUFOBgsGCUFOwA1ACAcIR4nBz0GMxMvEi0UNA41AFYA%7CV29PHzFxJWYbdhNKI14zSWdHFy0RLg42DDFnR3padFp6QHxCFEI%3D%7CWGFcYUF8XGNDf0Z6WmRcZkZ%2FX2NXAQ%3D%3D&needFold=0&_ksTS=1500261037692_798&'
    casper.thenOpen(url, function () {
        this.echo(this.getPageContent());
    })
});
casper.run(function() {
    this.exit();
});


