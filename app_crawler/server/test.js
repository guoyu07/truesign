/**
 * Created by root on 7/13/17.
 */
var search_url = {
    taobao:'https://s.taobao.com/search?imgfile=&js=1&stats_click=search_radio_all%3A1&initiative_id=staobaoz_20170713&ie=utf8&sort=sale-desc&q=',
    jd:''
}
var casper = require('casper').create({
    clientScripts: [
        'lib/jquery.min.js',
    ],
    pageSettings: {
        webSecurityEnabled: false,
        loadImages: false,
        loadPlugins: false
    },
    logLevel: 'info',
    verbose: true,
    waitTimeout: 1000000,


})
casper.start(function () {
    this.echo('Starting...')
})
casper.then(function () {
    console.log('开始根据 search_key:'+123+ ' 获取具体商品url')
    casper.thenOpen(search_url.taobao+123, function () {
        this.echo(this.getPageContent());
    })
})
casper.run()