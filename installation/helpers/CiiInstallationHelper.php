<?php
namespace app\helpers;

abstract class CiiInstallationHelper {
    public $result;
    public $title = 'CiiInstallationHelper';

    abstract function phaseName();


     /**
     * Displays a usage error.
     * This method will then terminate the execution of the current application.
     * @param string $message the error message
     */
    function usageError($message)
    {
        echo "Error: $message\n\n";
        exit(1);
    }

    function replaceParameterInFile($file, $param, $value = '') {
        return $this->replaceRegexInFile(
            $file,
            "/'".$param."'\s*=>\s*'([^']*)'/",
            "'".$param."' => '".str_replace("'", "\'", $value)."'"
        );
    }

    function replaceRegexInFile($file, $search, $replace) {
        if($content = file_get_contents($file)) {
            if(empty($content) || $content = preg_replace($search, $replace, $content)) {
                return file_put_contents($file, $content);
            }
        }


        return false;
    }


     /**
     * Renders the requirements check result.
     * The output will vary depending is a script running from web or from console.
     */
    function render() {
        
        $baseViewFilePath = $this->viewBase();
    
        $viewFileName = $baseViewFilePath . 'index.php';
        
        $layoutFile = $this->installationBase() . 'views' . DIRECTORY_SEPARATOR . 'layouts' . DIRECTORY_SEPARATOR . 'main.php';
        
        $this->renderViewFile($layoutFile, [
            'title' => $this->title,
            'content' => $this->renderViewFile($viewFileName, $this->result, true)
        ]);
    }

     /**
     * Renders a view file.
     * This method includes the view file as a PHP script
     * and captures the display result if required.
     * @param string $_viewFile_ view file
     * @param array $_data_ data to be extracted and made available to the view file
     * @param boolean $_return_ whether the rendering result should be returned as a string
     * @return string the rendering result. Null if the rendering result is not required.
     */
    function renderViewFile($_viewFile_, $_data_ = null, $_return_ = false) {
        // we use special variable names here to avoid conflict when extracting data
        if (is_array($_data_)) {
            extract($_data_, EXTR_PREFIX_SAME, 'data');
        } else {
            $data = $_data_;
        }
        if ($_return_) {
            ob_start();
            ob_implicit_flush(false);
            require($_viewFile_);

            return ob_get_clean();
        } else {
            require($_viewFile_);
        }
    }

    function viewBase() {
        return $this->installationBase() . 'views' . DIRECTORY_SEPARATOR . $this->phaseName() . DIRECTORY_SEPARATOR;
    }
    
    function installationBase() {
        return realpath(dirname(__FILE__) . DIRECTORY_SEPARATOR . '..') . DIRECTORY_SEPARATOR;
    }

    function frameworkBase() {
        return dirname(__FILE__) .  DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'modules' . DIRECTORY_SEPARATOR . 'cii' . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . 'yiisoft' . DIRECTORY_SEPARATOR . 'yii2' . DIRECTORY_SEPARATOR;
    }
}
