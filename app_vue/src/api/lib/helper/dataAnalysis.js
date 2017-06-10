/**
 * Created by ql-win on 2017/4/19.
 */

export function analysis_socket_response(response) {

    var analysis_reponse = {
        response_type : response.type,
        base_response : response.data



    }

    if(analysis_reponse.response_type !== 'c2c_msg' && analysis_reponse.response_type!== 'ping'  && analysis_reponse.response_type!== 'get_flow_data'){
         if(response.data.response){
             let yaf_reponse = {
                 error_response : 0,
                 response_init_data : response.data.response.response_data.data,
                 reponse_data:response.data.response.response_data.data.data,
                 response_status : response.data.response.response_data.data.status,
                 response_msg : response.data.response.response_data.data.msg,
                 website_encryption_key : response.data.response.response_data.data.website_encryption_key,
                 response_sysmsg :  response.data.response.response_data.data.sys_msg,
                 response_oss_uri: response.data.response.response_data.data.uri,
                 response_website_level: response.data.response.response_data.data.website_level,
             }
             analysis_reponse = Object.assign(analysis_reponse,yaf_reponse)
         }
         else{
             let yaf_reponse = {
                 error_response : 1
             }
             analysis_reponse = Object.assign(analysis_reponse,yaf_reponse)
         }

    }

    if(analysis_reponse.response_oss_uri){
        var response_oss_config = {
            OSSAccessKeyId:response.data.response.response_data.data.param.OSSAccessKeyId,
            callback:response.data.response.response_data.data.param.callback,
            expire:response.data.response.response_data.data.param.expire,
            key:response.data.response.response_data.data.param.key,
            name:response.data.response.response_data.data.param.name,
            policy:response.data.response.response_data.data.param.policy,
            signature:response.data.response.response_data.data.param.signature,
            success_action_status:response.data.response.response_data.data.param.success_action_status,
        }
        analysis_reponse.response_oss_config = response_oss_config
    }

    return analysis_reponse
}

export function dbResponseAnalysis2WidgetData(dbResponse) {
    if(dbResponse == null){

        dbResponse = {
            code: -1,
            err_desc : '未获取到数值，可能后端并未返回json数据',
            tip: convertCode2Tip('-1'),
        }
    }
    else if(dbResponse != null && typeof dbResponse === 'object' &&  !dbResponse.code){
        dbResponse = {
            code: 0,
            tip: convertCode2Tip('0'),
            count : dbResponse.statistic.count,
            data : dbResponse.data,
            tableaccess: dbResponse.access_rules.tableaccess,
            rules:dbResponse.access_rules.rules,
        }

        var widgetData = [];
        if(dbResponse.rules && dbResponse.data){
            for (var datarow of dbResponse.data){
                var widgetData_item = []
                for (var datakey in datarow){
                    // console.log(datakey,datarow[datakey])
                    var widgetData_item_attr = {}
                    widgetData_item_attr.value = datarow[datakey]
                    for (var rulekey in dbResponse.rules){
                       if(datakey === rulekey){
                           // widgetData_item_attr = dbResponse.rules[rulekey]
                            var widget_type = judgeWidgetTypeByKeyAndKeyType(dbResponse.rules[rulekey].name,dbResponse.rules[rulekey].type)
                           widgetData_item_attr.key = dbResponse.rules[rulekey].name
                           widgetData_item_attr.label = dbResponse.rules[rulekey].title
                           widgetData_item_attr.type = widget_type
                           widgetData_item_attr.regex = dbResponse.rules[rulekey].regex
                           widgetData_item_attr.access = false
                       }
                    }
                    widgetData_item.push(widgetData_item_attr)
                }
                widgetData.push(widgetData_item);
            }

        }
        dbResponse.widgetdata = widgetData
    }
    else if(typeof dbResponse === 'object' && dbResponse.code){
        dbResponse = {
            code : dbResponse.code,
            err_desc : dbResponse.desc,
            tip: convertCode2Tip(dbResponse.code)
        }
    }
    console.log(dbResponse)
    return dbResponse

}
function convertCode2Tip(code='0') {
    var tips  = [
        {code:'0',tip:'正常'},
        {code:'-1',tip:'未获取到数据'},
        {code:'2002',tip:'数据库连接出现问题'},
    ]
    var response_tip = ''
    for (var k of tips){
        if(k.code === code+''){

            response_tip = k.tip
        }
    }
    return response_tip === ''?'未定义code代码:'+code:response_tip

}
function judgeWidgetTypeByKeyAndKeyType(key,keytype){

    if(key === 'document_id' || keytype === 'int' ||  key.substr(key.length-3) === 'num'){
        return 'num'
    }
    else if(key.substr(key.length-4) === 'time'){
        return 'time'
    }
    else if(key.substr(key.length-4) === 'file'){
        return 'upfile'
    }
    else{
        return 'str'
    }

}
