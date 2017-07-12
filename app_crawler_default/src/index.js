/**
 * Created by ql-qf on 2017/7/11.
 */

phantom.outputEncoding = 'utf-8' //解决中文乱码
var axios = require('axios')
var $ = require('../node_modules/jquery/dist/jquery.min.js')
var config = {
  waitTime: 5000,
}
var uri = 'https://item.taobao.com/item.htm?spm=a21ig.146272.757693.1.1a44f7bPVSpmH&id=549067387968'
var post_result_uri = 'http://127.0.0.1:7000/common/log'
var i = 1;
var casper = require('casper').create({
  clientScripts: [
    '../node_modules/jquery/dist/jquery.min.js',
  ],
  pageSettings: {
    webSecurityEnabled: false,
    loadImages: false,
    loadPlugins: false
  },
  logLevel: 'info',
  verbose: true,
  waitTimeout: 1000000,
  onWaitTimeout: handleWaitTimeout,
  onTimeout: handleTimeout,
  onError: handleError,

})
// casper.options.clientScripts.push("../node_modules/babel-polyfill/dist/polyfill.js")
function repeat () {
  casper.thenOpen(uri).then(function () {
    out2png(this)
    this.echo('do something....')
    var page_content = this.getPageContent()
    var tmp = this.evaluate(function() {

      return $('#J_TabBar').html()
    });
    // console.log('tmp',tmp)
    this.click("#J_TabBar li:eq(1)");
    out2png(this)
    this.click("#J_TabBar li:eq(1) a.tb-tab-anchor");
    out2png(this)

    var params = {page_content:page_content}

    // casper.wait(1000, function() {
    //   this.echo("I've waited for a second.");
    //   // var jsonObject_fields = casper.evaluate(function(post_result_uri, params) {
    //   //   try {
    //   //     return JSON.parse(__utils__.sendAJAX(post_result_uri, 'POST', params, false));
    //   //   } catch (e) {
    //   //     console.log("Error in fetching json object");
    //   //   }
    //   // }, post_result_uri, params);
    //   //
    //   // console.log(JSON.stringify(jsonObject_fields));
    //   // this.echo('do something done->'+jsonObject_fields)
    // })


  })
  casper.wait(config.waitTime, function () {
    this.echo('wait time over!')
  })
  casper.run(repeat)
}

casper.start().then(function () {
  this.echo('Starting...')
})
casper.run(repeat)

function handleWaitTimeout (data) {
  console.log('handleWaitTimeout', data)
}
function handleTimeout (data) {
  console.log('handleTimeout', data)
}
function handleError (data) {
  console.log('handleError', data)
}
function out2png (obj) {
  obj.capture(i+".png");
  i++
}