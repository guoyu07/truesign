/**
 * Created by ql-win on 2017/4/22.
 */
export function String2Blob(str) {
    var blob = new Blob([str], {
        type: 'text/plain'
    });
    return blob
}
export function TypeArray2Blob(typearray) {
    //将 TypeArray  转换成 Blob 对象
    //var array = new Uint16Array([97, 32, 72, 101, 108, 108, 111, 32, 119, 111, 114, 108, 100, 33]);
    //测试成功
    //var blob = new Blob([array], { type: "application/octet-binary" });
    //测试成功
    // var blob = new Blob([array]);
    var blob = new Blob(typearray)
    //将 Blob对象 读成字符串
    var reader = new FileReader();
    reader.readAsText(blob, 'utf-8');
    reader.onload = function (e) {
        console.info(reader.result); //a Hello world!
        return reader.result
    }
}
export function Blob2String(blob) {
//将字符串转换成 Blob对象
//     var blob = new Blob(['中文字符串'], {
//         type: 'text/plain'
//     });
//将Blob 对象转换成字符串
    var reader = new FileReader();
    reader.readAsText(blob, 'utf-8');
    reader.onload = function (e) {
        console.info(reader.result);
        return reader.result
    }
}

export function Blob2ArrayBuffer(blob) {
    // //将字符串转换成 Blob对象
    // var blob = new Blob(['中文字符串'], {
    //     type: 'text/plain'
    // });
//将Blob 对象转换成 ArrayBuffer
    var reader = new FileReader();
    reader.readAsArrayBuffer(blob);
    reader.onload = function (e) {
        console.info(reader.result); //ArrayBuffer {}
        return reader.result
        //经常会遇到的异常 Uncaught RangeError: byte length of Int16Array should be a multiple of 2
        //var buf = new int16array(reader.result);
        //console.info(buf);

        // //将 ArrayBufferView  转换成Blob
        // var buf = new Uint8Array(reader.result);
        // console.info(buf); //[228, 184, 173, 230, 150, 135, 229, 173, 151, 231, 172, 166, 228, 184, 178]
        // reader.readAsText(new Blob([buf]), 'utf-8');
        // reader.onload = function () {
        //     console.info(reader.result); //中文字符串
        // };
        //
        // //将 ArrayBufferView  转换成Blob
        // var buf = new DataView(reader.result);
        // console.info(buf); //DataView {}
        // reader.readAsText(new Blob([buf]), 'utf-8');
        // reader.onload = function () {
        //     console.info(reader.result); //中文字符串
        // };
    }
}
