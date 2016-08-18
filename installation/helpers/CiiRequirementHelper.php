<?php
namespace app\helpers;
require 'CiiInstallationHelper.php';
use app\helpers\CiiInstallationHelper;

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */



/**
 * YiiRequirementChecker allows checking, if current system meets the requirements for running the Yii application.
 * This class allows rendering of the check report for the web and console application interface.
 *
 * Example:
 *
 * ```php
 * require_once('path/to/YiiRequirementChecker.php');
 * $requirementsChecker = new YiiRequirementChecker();
 * $requirements = array(
 *     array(
 *         'name' => 'PHP Some Extension',
 *         'mandatory' => true,
 *         'condition' => extension_loaded('some_extension'),
 *         'by' => 'Some application feature',
 *         'memo' => 'PHP extension "some_extension" required',
 *     ),
 * );
 * $requirementsChecker->checkYii()->check($requirements)->render();
 * ```
 *
 * If you wish to render the report with your own representation, use [[getResult()]] instead of [[render()]]
 *
 * Requirement condition could be in format "eval:PHP expression".
 * In this case specified PHP expression will be evaluated in the context of this class instance.
 * For example:
 *
 * ```php
 * $requirements = array(
 *     array(
 *         'name' => 'Upload max file size',
 *         'condition' => 'eval:$this->checkUploadMaxFileSize("5M")',
 *     ),
 * );
 * ```
 *
 * Note: this class definition does not match ordinary Yii style, because it should match PHP 4.3
 * and should not use features from newer PHP versions!
 *
 * @property array|null $result the check results, this property is for internal usage only.
 *
 * @author Paul Klimov <klimov.paul@gmail.com>
 * @since 2.0
 */
class CiiRequirementHelper extends CiiInstallationHelper
{
    public $title = 'Welcome to Cii';
    function phaseName() {
        return 'requirements';
    }

    /**
     * Check the given requirements, collecting results into internal field.
     * This method can be invoked several times checking different requirement sets.
     * Use [[getResult()]] or [[render()]] to get the results.
     * @param array|string $requirements requirements to be checked.
     * If an array, it is treated as the set of requirements;
     * If a string, it is treated as the path of the file, which contains the requirements;
     * @return $this self instance.
     */
    function check($requirements)
    {
        if (is_string($requirements)) {
            $requirements = require($requirements);
        }
        if (!is_array($requirements)) {
            $this->usageError('Requirements must be an array, "' . gettype($requirements) . '" has been given!');
        }
        if (!isset($this->result) || !is_array($this->result)) {
            $this->result = array(
                'summary' => array(
                    'total' => 0,
                    'errors' => 0,
                    'warnings' => 0,
                ),
                'requirements' => array(),
            );
        }
        foreach ($requirements as $key => $rawRequirement) {
            $requirement = $this->normalizeRequirement($rawRequirement, $key);
            $this->result['summary']['total']++;
            if (!$requirement['condition']) {
                if ($requirement['mandatory']) {
                    $requirement['error'] = true;
                    $requirement['warning'] = true;
                    $this->result['summary']['errors']++;
                } else {
                    $requirement['error'] = false;
                    $requirement['warning'] = true;
                    $this->result['summary']['warnings']++;
                }
            } else {
                $requirement['error'] = false;
                $requirement['warning'] = false;
            }
            $this->result['requirements'][] = $requirement;
        }

        return $this;
    }

    /**
     * Performs the check for the Yii core requirements.
     * @return YiiRequirementChecker self instance.
     */
    function checkYii()
    {
        return $this->check(
            array(
                array(
                    'name' => 'PHP version',
                    'mandatory' => true,
                    'condition' => version_compare(PHP_VERSION, '5.4.0', '>='),
                    'by' => '<a href="http://www.yiiframework.com">Yii Framework</a>',
                    'memo' => 'PHP 5.4.0 or higher is required.',
                ),
                array(
                    'name' => 'Reflection extension',
                    'mandatory' => true,
                    'condition' => class_exists('Reflection', false),
                    'by' => '<a href="http://www.yiiframework.com">Yii Framework</a>',
                ),
                array(
                    'name' => 'PCRE extension',
                    'mandatory' => true,
                    'condition' => extension_loaded('pcre'),
                    'by' => '<a href="http://www.yiiframework.com">Yii Framework</a>',
                ),
                array(
                    'name' => 'SPL extension',
                    'mandatory' => true,
                    'condition' => extension_loaded('SPL'),
                    'by' => '<a href="http://www.yiiframework.com">Yii Framework</a>',
                ),
                array(
                    'name' => 'Ctype extension',
                    'mandatory' => true,
                    'condition' => extension_loaded('ctype'),
                    'by' => '<a href="http://www.yiiframework.com">Yii Framework</a>'
                ),
                array(
                    'name' => 'MBString extension',
                    'mandatory' => true,
                    'condition' => extension_loaded('mbstring'),
                    'by' => '<a href="http://www.php.net/manual/en/book.mbstring.php">Multibyte string</a> processing',
                    'memo' => 'Required for multibyte encoding string processing.'
                ),
                array(
                    'name' => 'OpenSSL extension',
                    'mandatory' => false,
                    'condition' => extension_loaded('openssl'),
                    'by' => '<a href="http://www.yiiframework.com/doc-2.0/yii-base-security.html">Security Component</a>',
                    'memo' => 'Required by encrypt and decrypt methods.'
                ),
                array(
                    'name' => 'Intl extension',
                    'mandatory' => false,
                    'condition' => $this->checkPhpExtensionVersion('intl', '1.0.2', '>='),
                    'by' => '<a href="http://www.php.net/manual/en/book.intl.php">Internationalization</a> support',
                    'memo' => 'PHP Intl extension 1.0.2 or higher is required when you want to use advanced parameters formatting
                    in <code>Yii::t()</code>, non-latin languages with <code>Inflector::slug()</code>,
                    <abbr title="Internationalized domain names">IDN</abbr>-feature of
                    <code>EmailValidator</code> or <code>UrlValidator</code> or the <code>yii\i18n\Formatter</code> class.'
                ),
                array(
                    'name' => 'ICU version',
                    'mandatory' => false,
                    'condition' => defined('INTL_ICU_VERSION') && version_compare(INTL_ICU_VERSION, '49', '>='),
                    'by' => '<a href="http://www.php.net/manual/en/book.intl.php">Internationalization</a> support',
                    'memo' => 'ICU 49.0 or higher is required when you want to use <code>#</code> placeholder in plural rules
                    (for example, plural in
                    <a href=\"http://www.yiiframework.com/doc-2.0/yii-i18n-formatter.html#asRelativeTime%28%29-detail\">
                    Formatter::asRelativeTime()</a>) in the <code>yii\i18n\Formatter</code> class. Your current ICU version is ' .
                    (defined('INTL_ICU_VERSION') ? INTL_ICU_VERSION : '(ICU is missing)') . '.'
                ),
                array(
                    'name' => 'ICU Data version',
                    'mandatory' => false,
                    'condition' => defined('INTL_ICU_DATA_VERSION') && version_compare(INTL_ICU_DATA_VERSION, '49.1', '>='),
                    'by' => '<a href="http://www.php.net/manual/en/book.intl.php">Internationalization</a> support',
                    'memo' => 'ICU Data 49.1 or higher is required when you want to use <code>#</code> placeholder in plural rules
                    (for example, plural in
                    <a href=\"http://www.yiiframework.com/doc-2.0/yii-i18n-formatter.html#asRelativeTime%28%29-detail\">
                    Formatter::asRelativeTime()</a>) in the <code>yii\i18n\Formatter</code> class. Your current ICU Data version is ' .
                    (defined('INTL_ICU_DATA_VERSION') ? INTL_ICU_DATA_VERSION : '(ICU Data is missing)') . '.'
                ),
                array(
                    'name' => 'Fileinfo extension',
                    'mandatory' => false,
                    'condition' => extension_loaded('fileinfo'),
                    'by' => '<a href="http://www.php.net/manual/en/book.fileinfo.php">File Information</a>',
                    'memo' => 'Required for files upload to detect correct file mime-types.'
                ),
                array(
                    'name' => 'DOM extension',
                    'mandatory' => false,
                    'condition' => extension_loaded('dom'),
                    'by' => '<a href="http://php.net/manual/en/book.dom.php">Document Object Model</a>',
                    'memo' => 'Required for REST API to send XML responses via <code>yii\web\XmlResponseFormatter</code>.'
                )
            )
        );
    }

    /**
     * Return the check results.
     * @return array|null check results in format:
     *
     * ```php
     * array(
     *     'summary' => array(
     *         'total' => total number of checks,
     *         'errors' => number of errors,
     *         'warnings' => number of warnings,
     *     ),
     *     'requirements' => array(
     *         array(
     *             ...
     *             'error' => is there an error,
     *             'warning' => is there a warning,
     *         ),
     *         ...
     *     ),
     * )
     * ```
     */
    function getResult()
    {
        if (isset($this->result)) {
            return $this->result;
        } else {
            return null;
        }
    }

    /**
     * Checks if the given PHP extension is available and its version matches the given one.
     * @param string $extensionName PHP extension name.
     * @param string $version required PHP extension version.
     * @param string $compare comparison operator, by default '>='
     * @return boolean if PHP extension version matches.
     */
    function checkPhpExtensionVersion($extensionName, $version, $compare = '>=')
    {
        if (!extension_loaded($extensionName)) {
            return false;
        }
        $extensionVersion = phpversion($extensionName);
        if (empty($extensionVersion)) {
            return false;
        }
        if (strncasecmp($extensionVersion, 'PECL-', 5) === 0) {
            $extensionVersion = substr($extensionVersion, 5);
        }

        return version_compare($extensionVersion, $version, $compare);
    }

    /**
     * Checks if PHP configuration option (from php.ini) is on.
     * @param string $name configuration option name.
     * @return boolean option is on.
     */
    function checkPhpIniOn($name)
    {
        $value = ini_get($name);
        if (empty($value)) {
            return false;
        }

        return ((int) $value === 1 || strtolower($value) === 'on');
    }

    /**
     * Checks if PHP configuration option (from php.ini) is off.
     * @param string $name configuration option name.
     * @return boolean option is off.
     */
    function checkPhpIniOff($name)
    {
        $value = ini_get($name);
        if (empty($value)) {
            return true;
        }

        return (strtolower($value) === 'off');
    }

    /**
     * Compare byte sizes of values given in the verbose representation,
     * like '5M', '15K' etc.
     * @param string $a first value.
     * @param string $b second value.
     * @param string $compare comparison operator, by default '>='.
     * @return boolean comparison result.
     */
    function compareByteSize($a, $b, $compare = '>=')
    {
        $compareExpression = '(' . $this->getByteSize($a) . $compare . $this->getByteSize($b) . ')';

        return $this->evaluateExpression($compareExpression);
    }

    /**
     * Gets the size in bytes from verbose size representation.
     * For example: '5K' => 5*1024
     * @param string $verboseSize verbose size representation.
     * @return integer actual size in bytes.
     */
    function getByteSize($verboseSize)
    {
        if (empty($verboseSize)) {
            return 0;
        }
        if (is_numeric($verboseSize)) {
            return (int) $verboseSize;
        }
        $sizeUnit = trim($verboseSize, '0123456789');
        $size = str_replace($sizeUnit, '', $verboseSize);
        $size = trim($size);
        if (!is_numeric($size)) {
            return 0;
        }
        switch (strtolower($sizeUnit)) {
            case 'kb':
            case 'k':
                return $size * 1024;
            case 'mb':
            case 'm':
                return $size * 1024 * 1024;
            case 'gb':
            case 'g':
                return $size * 1024 * 1024 * 1024;
            default:
                return 0;
        }
    }

    /**
     * Checks if upload max file size matches the given range.
     * @param string|null $min verbose file size minimum required value, pass null to skip minimum check.
     * @param string|null $max verbose file size maximum required value, pass null to skip maximum check.
     * @return boolean success.
     */
    function checkUploadMaxFileSize($min = null, $max = null)
    {
        $postMaxSize = ini_get('post_max_size');
        $uploadMaxFileSize = ini_get('upload_max_filesize');
        if ($min !== null) {
            $minCheckResult = $this->compareByteSize($postMaxSize, $min, '>=') && $this->compareByteSize($uploadMaxFileSize, $min, '>=');
        } else {
            $minCheckResult = true;
        }
        if ($max !== null) {
            $maxCheckResult = $this->compareByteSize($postMaxSize, $max, '<=') && $this->compareByteSize($uploadMaxFileSize, $max, '<=');
        } else {
            $maxCheckResult = true;
        }

        return ($minCheckResult && $maxCheckResult);
    }

    /**
     * Normalizes requirement ensuring it has correct format.
     * @param array $requirement raw requirement.
     * @param integer $requirementKey requirement key in the list.
     * @return array normalized requirement.
     */
    function normalizeRequirement($requirement, $requirementKey = 0)
    {
        if (!is_array($requirement)) {
            $this->usageError('Requirement must be an array!');
        }
        if (!array_key_exists('condition', $requirement)) {
            $this->usageError("Requirement '{$requirementKey}' has no condition!");
        } else {
            $evalPrefix = 'eval:';
            if (is_string($requirement['condition']) && strpos($requirement['condition'], $evalPrefix) === 0) {
                $expression = substr($requirement['condition'], strlen($evalPrefix));
                $requirement['condition'] = $this->evaluateExpression($expression);
            }
        }
        if (!array_key_exists('name', $requirement)) {
            $requirement['name'] = is_numeric($requirementKey) ? 'Requirement #' . $requirementKey : $requirementKey;
        }
        if (!array_key_exists('mandatory', $requirement)) {
            if (array_key_exists('required', $requirement)) {
                $requirement['mandatory'] = $requirement['required'];
            } else {
                $requirement['mandatory'] = false;
            }
        }
        if (!array_key_exists('by', $requirement)) {
            $requirement['by'] = 'Unknown';
        }
        if (!array_key_exists('memo', $requirement)) {
            $requirement['memo'] = '';
        }

        return $requirement;
    }


    /**
     * Evaluates a PHP expression under the context of this class.
     * @param string $expression a PHP expression to be evaluated.
     * @return mixed the expression result.
     */
    function evaluateExpression($expression)
    {
        return eval('return ' . $expression . ';');
    }
}
