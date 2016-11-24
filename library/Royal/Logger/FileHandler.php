<?php
/**
 * User: heyi
 * Date: 14/11/3
 * Time: PM9:48
 */

namespace Royal\Logger;


use Monolog\Formatter\LineFormatter;
use Monolog\Handler\StreamHandler;

class FileHandler extends StreamHandler {
    private $filename;

    public function __construct($filename, $level = \Monolog\Logger::DEBUG, $bubble=true) {
        $this->filename = $filename;

        parent::__construct($this->getTimedFilename(), $level, $bubble);
    }
    

    public function write(array $record) {
        $this->stream = null;
        $this->url = $this->getTimedFilename();
        parent::write($record);
    }

    protected function getTimedFilename() {
        $fileInfo = pathinfo($this->filename);

        $dir = $fileInfo['dirname'];
        $currentDir = sprintf('%s/%s', $dir, date('Ymd'));

        if (!file_exists($currentDir)) {
            @mkdir($currentDir, 0777, true);
        }
        $timedFilename = sprintf('%s/%s.%s', $currentDir, $fileInfo['filename'], $fileInfo['extension']);

        return $timedFilename;
    }
}
