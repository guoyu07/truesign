/**
 * Created by Administrator on 2017/6/22.
 */
var elasticsearch = require('elasticsearch');
var client = new elasticsearch.Client({
    host: '192.168.191.2:9200',
    log: 'trace'
});