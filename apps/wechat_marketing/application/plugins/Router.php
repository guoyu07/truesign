<?php
/**
 * @name SamplePlugin
 * @desc Yaf定义了如下的6个Hook,插件之间的执行顺序是先进先Call
 * @see http://www.php.net/manual/en/class.yaf-plugin-abstract.php
 * @author ql_os
 */
class RouterPlugin extends Yaf_Plugin_Abstract {

	public function routerStartup(Yaf_Request_Abstract $request, Yaf_Response_Abstract $response) {
        $controller = ucfirst(strtolower($request->getControllerName()));
        $file = sprintf('%s/application/controllers/%s.php', APPLICATION_PATH, $controller);
        if (!file_exists($file) && preg_match('/^(.*)\/([^\/]*)$/', $file, $matched)) {
            list(, $dir, $file) = $matched;
            $file = strtolower($file);
            foreach (glob("$dir/*") as $realfile) {
                $realFileName = preg_replace('/.*\//', '', $realfile);
                if (strtolower($realFileName) == $file) {
                    $controller = preg_replace('/\.php/i', '', $realFileName);
                    $request->setControllerName($controller);
                    break;
                }
            }
        }
	}


}
