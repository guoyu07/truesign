/**
 * Created by ql-win on 2017/3/20.
 */
class AnalysisUrl{

    static GetQueryString(name,str)
    {
        var reg = new RegExp("(^|&)"+ name +"=([^&]*)(&|$)");
        var r = str.substr(1).match(reg);
        if(r!=null)
            return  r[2]
        return null;
    }
}