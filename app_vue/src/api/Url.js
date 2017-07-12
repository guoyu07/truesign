/**
 * Created by ql-win on 2017/3/28.
 */
export function parseURL(url) {
    var a = document.createElement('a');
    a.href = url;
    var build_url = {
        source: url,
        protocol: a.protocol.replace(':',''),
        host: a.hostname,
        port: a.port,
        query: a.search,
        params: (function(){
            var ret = {},
                seg = a.search.replace(/^\?/,'').split('&'),
                len = seg.length, i = 0, s;
            for (; i<len; i++ ){
                if (!seg[i]) { continue; }
                s = seg[i].split('=');
                ret[s[0]] = s[1];
            }
            return ret;
        })(),
        file: (a.pathname.match(/\/([^/?#]+)$/i) || [''])[1],
        hash: a.hash.replace('#',''),
        path: a.pathname.replace(/^([^/])/,'/$1'),
        relative: (a.href.match(/tps?:\/\/[^/]+(.+)/) || [''])[1],
        segments: a.pathname.replace(/^\//,'').split('/'),
        // all_host:build_url.protocol+'://'+build_url.host+':'+build_url.port+'/'
    }
    build_url.all_host = build_url.protocol+'://'+build_url.host+':'+build_url.port+'/'
    console.log('build_url=>')
    console.log(build_url)
    return build_url
}
