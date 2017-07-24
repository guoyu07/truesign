/**
 * Created by root on 7/13/17.
 */
var comment_list  = []
var item_comment = {url:'http://iamsee.com:5001/index/getrequest?app=i_app'}
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
    verbose: false,
    waitTimeout: 1000000,


})
casper.start(function () {
    this.echo('Starting...')
})
casper.then(function () {

    this.repeat(5, function() {
        casper.thenOpen(item_comment.url,function () {
            this.echo(this.getPageContent());
        })
        console.log(1)
    })
    // while (true){
    //     console.log(1)
    //     casper.then(function () {
    //         console.log(1)
    //         casper.thenOpen(item_comment.url,function () {
    //             // utils.dump(this.getPageContent)
    //             this.echo(this.getPageContent());
    //         })
    //     })
    //
    //
    // }

})

casper.run(function() {
    return true
})