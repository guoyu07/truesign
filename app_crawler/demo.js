/**
 * Created by Administrator on 2017/6/20.
 */
phantom.outputEncoding="GBK"
var page = require('webpage').create();
page.viewportSize = {
    width: 1366,
    height: 800
};
var urls = ["https://www.baidu.com/", "https://zrysmt.github.io/"];
page.open(urls[0], function() {
    console.log('welcome!');
    page.render('screen.png');
    phantom.exit();
});