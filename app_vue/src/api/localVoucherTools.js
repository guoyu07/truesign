/**
 * Created by ql-win on 2017/3/19.
 */
const _ = require('lodash');

const LocalVoucherTools = {

    data:{
        StorageMode : '',
        keys : [],
        StorageEngine : null,
        this_vue:null
    },
    checkStorageMode(){
        if(window.localStorage){
            this.data.StorageMode = 'localStorage'
        }
    },
    initEngine(){
        if(this.data.StorageMode === 'localStorage'){
            this.data.StorageEngine = window.localStorage;
            return
        }
    },
    getKeys(){
        if(this.data.StorageEngine){
            if(this.data.StorageEngine['keys']){
                this.data.keys = this.data.StorageEngine['keys'].split(',')
            }
            else{
                this.data.keys = []
            }

            return this.data.keys
        }
        else{
            console.log('存储引擎还未初始化')
        }
    },
    getValue(key){
        if(this.data.StorageEngine){
            let value  = this.data.StorageEngine[key]
            return value
        }
        else{
            console.log('存储引擎还未初始化')
        }
    },
    setKeyValue(key,value){
        if(this.data.StorageEngine) {
            if (key && value) {
                this.data.keys.push(key)
                this.data.keys = _.uniq(this.data.keys)
                this.data.StorageEngine['keys'] = this.data.keys.join(',')
                this.data.StorageEngine[key] = value
            }
        }
        else{
            console.log('存储引擎还未初始化')
        }
    },
    clear(){
        this.data.StorageEngine.clear()
    }
}
export  default  LocalVoucherTools
