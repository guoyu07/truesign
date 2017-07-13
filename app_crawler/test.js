casper.start(function () {
    this.echo('Starting...')
})
casper.then(function () {
    console.log('开始根据 search_key:'+search_key+ ' 获取具体商品url')
    casper.thenOpen(search_url.taobao+'123', function () {
        this.echo(this.getPageContent());
    })
})
