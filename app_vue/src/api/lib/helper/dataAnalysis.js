/**
 * Created by ql-win on 2017/4/19.
 */
import md5 from 'md5'
import sha256 from 'sha256'

// const dateTime_tools = require('date-time');
var time_tools = require('node-datetime');

export function analysis_socket_response(response) {

    var analysis_reponse = {
        response_type: response.type,
        base_response: response.data


    }

    if (analysis_reponse.response_type !== 'c2c_msg' && analysis_reponse.response_type !== 'ping' && analysis_reponse.response_type !== 'get_flow_data') {
        if (response.data.response) {
            let yaf_reponse = {
                error_response: 0,
                response_init_data: response.data.response.response_data.data,
                reponse_data: response.data.response.response_data.data.data,
                response_status: response.data.response.response_data.data.status,
                response_msg: response.data.response.response_data.data.msg,
                website_encryption_key: response.data.response.response_data.data.website_encryption_key,
                response_sysmsg: response.data.response.response_data.data.sys_msg,
                response_oss_uri: response.data.response.response_data.data.uri,
                response_website_level: response.data.response.response_data.data.website_level,
            }
            analysis_reponse = Object.assign(analysis_reponse, yaf_reponse)
        }
        else {
            let yaf_reponse = {
                error_response: 1
            }
            analysis_reponse = Object.assign(analysis_reponse, yaf_reponse)
        }

    }

    if (analysis_reponse.response_oss_uri) {
        var response_oss_config = {
            OSSAccessKeyId: response.data.response.response_data.data.param.OSSAccessKeyId,
            callback: response.data.response.response_data.data.param.callback,
            expire: response.data.response.response_data.data.param.expire,
            key: response.data.response.response_data.data.param.key,
            name: response.data.response.response_data.data.param.name,
            policy: response.data.response.response_data.data.param.policy,
            signature: response.data.response.response_data.data.param.signature,
            success_action_status: response.data.response.response_data.data.param.success_action_status,
        }
        analysis_reponse.response_oss_config = response_oss_config
    }

    return analysis_reponse
}
export function analysis_yaf_response(response) {
    response = JSON.parse(response)
    var analysis_reponse = {
        response_code: response.code,
        response_desc: response.desc
    }
    return analysis_reponse
}

/*
 用于处理yaf api 返回一会的数据格式，并附加打包完成的控件数据格式
 */
export function dbResponseAnalysis2WidgetData(dbResponse) {
    if (dbResponse == null) {

        dbResponse = {
            code: -1,
            err_desc: '未获取到数值，可能后端并未返回json数据',
            tip: convertCode2Tip('-1'),
        }
    }
    else if (dbResponse != null && typeof dbResponse === 'object' && !dbResponse.code) {
        dbResponse = {
            code: 0,
            tip: convertCode2Tip('0'),
            count: dbResponse.statistic.count,
            data: dbResponse.data,
            tableaccess: dbResponse.access_rules.tableaccess,
            rules: dbResponse.access_rules.rules,
        }

        var widgetData = [];
        if (dbResponse.rules && dbResponse.data) {
            for (var datarow of dbResponse.data) {
                var widgetData_item = []
                var searchWidget = []
                var sorterWidget = []
                for (var datakey in datarow) {
                    // console.log(datakey,datarow[datakey])
                    var widgetData_item_attr = {}
                    widgetData_item_attr.value = datarow[datakey]
                    for (var rulekey in dbResponse.rules) {
                        if (datakey === rulekey) {
                            // widgetData_item_attr = dbResponse.rules[rulekey]
                            var widget_type = judgeWidgetTypeByKeyAndKeyType(dbResponse.rules[rulekey].name, dbResponse.rules[rulekey].type, dbResponse.rules[rulekey].widgetType)
                            if (widget_type === 'time') {
                                console.log('datarow[datakey]',datarow[datakey])
                                widgetData_item_attr.value = new Date(datarow[datakey] * 1000);
                                // datarow[datakey] = dateTime_tools({date:new Date(datarow[datakey]*1000)})
                                // datarow[datakey] = time_tools.create(datarow[datakey],'Y-m-d H:M:S')
                                datarow[datakey] = new Date(datarow[datakey] * 1000);

                            }
                            widgetData_item_attr.key = dbResponse.rules[rulekey].name
                            widgetData_item_attr.label = dbResponse.rules[rulekey].title
                            widgetData_item_attr.type = widget_type
                            widgetData_item_attr.issearch = dbResponse.rules[rulekey].issearch
                            widgetData_item_attr.issorter = dbResponse.rules[rulekey].issorter
                            widgetData_item_attr.regex = dbResponse.rules[rulekey].regex === false ? false : eval(dbResponse.rules[rulekey].regex)
                            widgetData_item_attr.able_show = dbResponse.rules[rulekey].name === 'document_id' ? false : dbResponse.rules[rulekey].able_show
                            widgetData_item_attr.able_modify = dbResponse.rules[rulekey].name === 'document_id' ? false : dbResponse.rules[rulekey].able_modify
                            widgetData_item_attr.access = true
                            widgetData_item_attr.widgetType = dbResponse.rules[rulekey].widgetType
                            widgetData_item_attr.widgetStyle = dbResponse.rules[rulekey].widgetStyle
                            if (widget_type === 'checkbox') {
                                widgetData_item_attr.value = isEmptyValue(widgetData_item_attr.value) ? [] : JSON.parse(widgetData_item_attr.value)
                                // console.log('getcheckbox',typeof widgetData_item_attr.value, widgetData_item_attr.value )
                            }
                            if (dbResponse.rules[rulekey].issorter) {
                                let sorter_item = {}
                                sorter_item.key = dbResponse.rules[rulekey].name
                                switch (dbResponse.rules[rulekey].issorter === 'asc') {
                                    case 'asc':
                                        sorter_item.way = 'ascending'
                                        break;
                                    case 'desc':
                                        sorter_item.way = 'descending'
                                        break;
                                    default:
                                        sorter_item.way = 'ascending'
                                }
                                sorterWidget.push(sorter_item)

                            }
                            if (dbResponse.rules[rulekey].issearch) {
                                searchWidget.push({
                                    search_value: '',
                                    search_key: dbResponse.rules[rulekey].name,
                                    search_title: dbResponse.rules[rulekey].title
                                })
                            }

                            var widget_width = judgeWidth(dbResponse.rules[rulekey].name, widget_type, datarow[datakey])
                            dbResponse.rules[rulekey].width = 0
                            // dbResponse.rules[rulekey].width = widget_width>dbResponse.rules[rulekey].width?widget_width:dbResponse.rules[rulekey].width
                            dbResponse.rules[rulekey].width = widget_width
                        }
                    }
                    widgetData_item.push(widgetData_item_attr)
                }
                widgetData.push(widgetData_item);
                // 如果数据组长度大于一说明很可能是列表，为了适配table_model页面要进行
                // if(dbResponse.data.length > 1){
                //
                // }
            }

        }
        dbResponse.widgetdata = widgetData
        dbResponse.sorterWidget = sorterWidget
        dbResponse.searchWidget = searchWidget

    }
    else if (typeof dbResponse === 'object' && dbResponse.code) {
        dbResponse = {
            code: dbResponse.code,
            err_desc: dbResponse.desc,
            tip: convertCode2Tip(dbResponse.code)
        }
    }
    // console.log(dbResponse)
    return dbResponse

}

export function resolveWidgetData2FormData(WidgetData, flag = true) {
    if (flag) {
        var formdata = {}

        for (let item of WidgetData) {

            if (item.type === 'checkbox') {
                item.value = JSON.stringify(item.value)

            }
            if (item.type === 'radio') {

            }
            if (item.type === 'time') {
                item.value = Date.parse(item.value) / 1000
            }
            if (item.type === 'password') {
                console.log('item', item.value.length)
                if (isEmpty(item.value) || (item.value.length < 8) || ( item.value.length > 64 )) {
                    item.value = ''
                }
                else if (item.value.length === 64) {
                    item.value = item.value
                }
                else {
                    item.value = sha256(md5(item.value))
                }
            }
            formdata[item.key] = item.value
        }
        return formdata
    }
    else {
        for (let item of WidgetData) {

            if (item.type === 'checkbox') {
                item.value = JSON.parse(item.value)

            }
            if (item.type === 'radio') {

            }
            if (item.type === 'time') {
                item.value = new Date(item.value * 1000)
            }
        }
    }


}

export function countdownPrettyTimeByTimestmp(limit_time) {
    let login_time = Date.parse(limit_time) / 1000 + 10 * 24 * 60 * 60

    let last_time = login_time - Date.parse(new Date()) / 1000

    let day = parseInt(last_time / (3600 * 24))
    let hour = parseInt(last_time % (3600 * 24) / 3600)
    let minute = parseInt(last_time % (3600 * 24) % 3600 / 60)
    let sec = parseInt(last_time % (3600 * 24) % 3600 % 60)
    return day + '天' + hour + '时' + minute + '分' + sec + '秒'
}
export function timestamp2datetime(timestamp) {
    var dt = time_tools.create(timestamp, 'Y-m-d H:M:S')
    var formattedDate = dt.format();
    return formattedDate
}

function convertCode2Tip(code = '0') {
    var tips = [
        {code: '0', tip: '正常'},
        {code: '-1', tip: '未获取到数据'},
        {code: '2002', tip: '数据库连接出现问题'},
    ]
    var response_tip = ''
    for (var k of tips) {
        if (k.code === code + '') {

            response_tip = k.tip
        }
    }
    return response_tip === '' ? '未定义code代码:' + code : response_tip

}
function judgeWidgetTypeByKeyAndKeyType(key, keytype, widgetType) {
    if (widgetType) {
        widgetType = widgetType[0]
    }
    if (widgetType === 'text') {
        return 'text'
    }
    else if (key === 'document_id' || (keytype === 'int' && widgetType !== 'time' && widgetType !== 'status') || widgetType === 'num') {
        return 'num'
    }
    else if (widgetType === 'time') {
        return 'time'
    }
    else if (widgetType === 'mail') {
        return 'mail'
    }
    else if (widgetType === 'headpic') {
        return 'upimg'
    }
    else if (widgetType === 'file') {
        return 'upfile'
    }
    else if (widgetType === 'password') {
        return 'password'
    }
    else if (widgetType === 'radio') {
        return 'radio'
    }
    else if (widgetType === 'checkbox') {

        return 'checkbox'
    }
    else if (widgetType === 'color') {

        return 'color'

    }
    // else if(key.substr(key.length-3) === 'obj'){
    //     return 'obj'
    // }
    else if (widgetType === 'obj') {
        return 'obj'
    }
    else if (widgetType === 'status') {
        return 'status'
    }
    else {
        return 'str'
    }

}
function judgeWidth(key, keytype, value) {
    var base_width = 130;
    switch (keytype) {
        case 'str':
            base_width = 130
            break
        case 'time':
            base_width = 120
            break;
        case 'text':
            base_width = 200
            break;
        case 'file':
            base_width = 170
            break;
        case 'img':
            base_width = 80
            break;
        case 'obj':
            base_width = 150
            break
        case 'mail':
            base_width = 200
            break
        case 'num':
            base_width = 120
            break
        default:
            base_width = 120
    }

    var tmp_width = 0;
    tmp_width = key.length * 8
    if (key === 'document_id') {
        return 90
    }
    else {
        return base_width + tmp_width
    }


}
function arr2str(arr) {
    if (isEmptyValue(arr)) {
        return ''
    }
    else {
        return arr.join(';')
    }
}
function str2arr(str, delimiter = ';') {
    if (isEmptyValue(str)) {
        return []
    }
    else {
        return str.split(delimiter)
    }
}

function isJSON(str, pass_object) {
    if (pass_object && isObject(str)) return true;

    // if (!isString(str)) return false;

    str = str.replace(/\s/g, '').replace(/\n|\r/, '');

    if (/^\{(.*?)\}$/.test(str)) {
        return /"(.*?)":(.*?)/g.test(str);
    }

    if (/^\[(.*?)\]$/.test(str)) {
        return str.replace(/^\[/, '')
            .replace(/\]$/, '')
            .replace(/},{/g, '}\n{')
            .split(/\n/)
            .map(function (s) {
                return isJSON(s);
            })
            .reduce(function (prev, curr) {
                return !!curr;
            });
    }

    return false;
}


//无论是空字符串、空整形、空对象都返回true
export function isEmpty(obj) {
    var _isEmpty = false;
    if (typeof obj === 'string' || typeof obj === 'number') {
        _isEmpty = !obj;
    } else if (isPlainObj(obj)) {
        var value = "";
        for (var i in obj) {
            //这里的递归解决{a:{b:{c:''}}}这样的情况
            value = !value && isEmpty(obj[i]);
        }
        _isEmpty = value;
    }

    return _isEmpty;
}

//判断是否是字面量对象
function isPlainObj(obj) {
    return (obj instanceof Object ) && (obj.constructor === Object);
}

export function isEmptyValue(value) {
    var type;
    if (value == null) { // 等同于 value === undefined || value === null
        return true;
    }
    type = Object.prototype.toString.call(value).slice(8, -1);
    switch (type) {
        case 'String':
            return !$.trim(value);
        case 'Array':
            return !value.length;
        case 'Object':
            return $.isEmptyObject(value); // 普通对象使用 for...in 判断，有 key 即为 false
        default:
            return false; // 其他对象均视作非空
    }
};
export function deleteEmptyObj(key, container) {
    container = container || window;
    if (isEmpty(container[key])) {
        delete container[key];
    } else if (isPlainObj(container[key])) {
        for (var i in container[key]) {
            deleteEmptyObj(i, container[key]);
        }
    }
}


export function deleteEmptyString(test, recurse) {

    for (var i in test) {
        if (test[i] === '') {
            delete test[i];
        } else if (recurse && typeof test[i] === 'object') {
            deleteEmptyString(test[i], recurse);
        }
    }

}






