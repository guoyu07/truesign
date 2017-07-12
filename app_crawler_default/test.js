/**
 * Created by ql-qf on 2017/7/11.
 */
var fs = require('fs')
fs.writeFile('aaaa.log', '12324321', function (err) {
  if (err) throw err;
  console.log("Export Account Success!");
});