/**
 * Created by ql-win on 2017/4/19.
 */
export function analysis_socket_response(response) {

    var analysis_reponse = {
        response_type : response.type,
        base_response : response.data



    }

    if(analysis_reponse.response_type !== 'c2c_msg' && analysis_reponse.response_type!== 'ping'  ){
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

