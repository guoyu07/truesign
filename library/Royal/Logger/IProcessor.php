<?php
/**
 * User: iamsee
 * Date: 14/11/3
 * Time: PM10:06
 */

namespace Royal\Logger;


class IProcessor {
    /**
     * @param  array $record
     * @return array
     */
    public function __invoke(array $record) {

        $trace = debug_backtrace();

        // skip first since it's always the current method
        array_shift($trace);
        // the call_user_func call is also skipped
        array_shift($trace);

        $i = 0;

        $skipClasses = array('Royal\\Logger\\', 'Monolog\\');
        while (isset($trace[$i]['class'])) {
            foreach ($skipClasses as $part) {

                if (strpos($trace[$i]['class'], $part) !== false) {
                    $i++;
                    continue 2;
                }
            }
            break;
        }

        $extra = $record['extra'];
        if (!$extra) {
            $extra = array();
        }
        $request_source = [];
        for($j=sizeof($trace);$j>=$i;$j--){
            unset($trace[$j]['object']);
            $request_source[] = $trace[$j];
        }

        // we should have the call source now
        $extra = array_merge(
            $extra,
            array(
                'file' => isset($trace[$i - 1]['file']) ? $trace[$i - 1]['file'] : null,
                'line' => isset($trace[$i - 1]['line']) ? $trace[$i - 1]['line'] : null,
                'class' => isset($trace[$i]['class']) ? $trace[$i]['class'] : null,
                'function' => isset($trace[$i]['function']) ? $trace[$i]['function'] : null,
                'request_source' => json_encode($request_source,256)
            )
        );

        if ($extra['class']) {
            $extra['class'] = str_replace('\\', '_', $extra['class']);
        }

        if (php_sapi_name() != 'cli') {
            $extra['url'] = $_SERVER['REQUEST_URI'];
            $extra['ip'] = $_SERVER['REMOTE_ADDR'];
        }

        $record['extra'] = $extra;

        return $record;
    }
} 