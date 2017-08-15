<?php

/**
 * Created by PhpStorm.
 * User: placeless
 * Date: 16/12/16
 * Time: 上午11:22
 */

namespace Royal\InitCommand;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Yaf_Exception;

class GenerateMVCCommand extends Command
{
    //Argument 需要自己在configure()中设置
    private $type = 'MVC';

    private $appName = '';

    private $baseControllerTemplatePath = TEMPLATE_PATH . '/AppBase.php';
    private $controllerTemplatePath = TEMPLATE_PATH . '/controller.php';
    private $targetControllerPath = CURRECT_APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'controllers';
    private $outputControllerName;

    private $baseServiceTemplatePath = TEMPLATE_PATH . '/BaseService.php';
    private $serviceTemplatePath = TEMPLATE_PATH . '/Service.php';
    private $targetServicePath = '';


    private $modulePath = CURRECT_APPLICATION_PATH . DIRECTORY_SEPARATOR . 'application' . DIRECTORY_SEPARATOR . 'modules';

    private $vuePath = TEMPLATE_PATH . '/vue.php';
    private $targetViewPath = '';

    public function __construct()
    {
        $this->appName = basename(CURRECT_APPLICATION_PATH);
        $this->targetServicePath = APPLICATION_PATH . DIRECTORY_SEPARATOR . 'common' . DIRECTORY_SEPARATOR . 'Service' . DIRECTORY_SEPARATOR . ucfirst($this->appName);
        $this->targetViewPath = APPLICATION_PATH.DIRECTORY_SEPARATOR.'app_vue'.DIRECTORY_SEPARATOR.'src'.DIRECTORY_SEPARATOR.'components'.DIRECTORY_SEPARATOR.'project'.DIRECTORY_SEPARATOR.($this->appName);
    }


    public function execute($moduleName = 'index', $controllerName = 'index', $desc = '', $adpName = '')
    {


        echo " 开始处理 Controllers ===========================".PHP_EOL;
        $this->outputControllerName = $this->targetControllerPath . DIRECTORY_SEPARATOR . ucfirst($controllerName) . '.php';
        $template = require_once $this->controllerTemplatePath;
        $data = sprintf($template,
            ucfirst($controllerName) . 'Controller',
            ucfirst(basename(CURRECT_APPLICATION_PATH)),
            ucfirst($controllerName),
            ucfirst($controllerName) . 'Controller',
            ucfirst($controllerName),
            ucfirst($controllerName),
            ucfirst($controllerName),
            $desc,
            ucfirst($controllerName),
            ucfirst($controllerName),
            $desc,
            ucfirst($controllerName),
            ucfirst($controllerName),
            $desc,
            ucfirst($controllerName),
            ucfirst($controllerName),
            $desc
        );


        if (!is_writable(dirname($this->outputControllerName))) {
            echo "控制器文件不可写" . PHP_EOL;
        }

        if (file_exists($this->outputControllerName)) {
            echo "控制器 " . $controllerName . " 文件存在" . PHP_EOL;


        } else {
            $controller = require_once $this->baseControllerTemplatePath;
            $targetControllerPath = $this->targetControllerPath . DIRECTORY_SEPARATOR . 'AppBase.php';
            file_put_contents($targetControllerPath, $controller);

            echo "Controller AppBase创建完成" . PHP_EOL;
            file_put_contents($this->outputControllerName, $data);
            echo $controllerName . "文件创建完成" . PHP_EOL;


        }

        echo "开始处理Service =================================".PHP_EOL;



        $targetServiceDir = $this->targetServicePath;
//        echo "准备创建Service文件夹" . $targetServiceDir.PHP_EOL;

        if (!is_dir($targetServiceDir)) {
            mkdir($targetServiceDir);
//            echo "创建文件夹" . $targetServiceDir ."完成" . PHP_EOL;
        }

        $serviceFilePath = $this->targetServicePath . DIRECTORY_SEPARATOR . ucfirst($controllerName) . 'Service.php';
        if (file_exists($serviceFilePath)) {
            echo "服务 " . $serviceFilePath . " 文件存在" . PHP_EOL;
        } else {
            $base_service = require_once $this->baseServiceTemplatePath;
            file_put_contents($this->targetServicePath . DIRECTORY_SEPARATOR . 'BaseService.php', sprintf($base_service, ucfirst($this->appName)));
            echo "Service BaseService 创建完成" . PHP_EOL;
            $servicefile = require_once $this->serviceTemplatePath;
            $service_data = sprintf($servicefile,
                ucfirst($this->appName),
                ucfirst($this->appName),
                $adpName,
                ucfirst($controllerName),
                $adpName
            );

            file_put_contents($serviceFilePath, $service_data);
            echo "Service 创建完成".PHP_EOL;
        }


        echo "开始处理 View =================================".PHP_EOL;
        $targetViewDir = $this->targetViewPath;
        echo $targetViewDir.PHP_EOL;
        if (!is_dir($targetViewDir)) {
            mkdir($targetViewDir);
            echo $targetViewDir.'文件夹创建完成'.PHP_EOL;
        }
        $targetViewFile = $targetViewDir.DIRECTORY_SEPARATOR.$controllerName.'.vue';
        $viewTemplateFile = require_once $this->vuePath;
        $view_data = sprintf($viewTemplateFile,
            ucfirst($controllerName),
            ucfirst($controllerName),
            ucfirst($controllerName),
            ucfirst($controllerName),
            ucfirst($controllerName),
            ucfirst($controllerName),
            ucfirst($controllerName),
            ucfirst($controllerName),
            ucfirst($controllerName),
            ucfirst($controllerName),
            ucfirst($controllerName),
            ucfirst($controllerName),
            ucfirst($controllerName),
            ucfirst($controllerName),
            ucfirst($controllerName),
            ucfirst($controllerName),
            ucfirst($controllerName),
            ucfirst($controllerName),
            ucfirst($controllerName),
            ucfirst($controllerName),
            ucfirst($controllerName)
            );
        file_put_contents($targetViewFile, $view_data);
        echo "View 创建完成".PHP_EOL;


    }
}