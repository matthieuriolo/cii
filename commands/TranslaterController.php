<?php

namespace app\commands;

use Yii;

use yii\console\Exception;
use yii\console\Controller;
use yii\helpers\FileHelper;

use yii\helpers\VarDumper;

/**
 * A simplified version of the MessageControler
 * Only for php files and only for packages/layouts
 * The output will be echoed instead saved to a file
 */
class TranslaterController extends Controller {
    public $packageBasePath = '@app/modules';
    public $layoutBasePath = '@app/layouts';

    public $type;
    public $name;
    public $original = null;
    public $override = false;
    public function options($actionID)
    {
        return array_merge(parent::options($actionID), [
            'type',
            'name',
            'original',
            'override'
        ]);
    }


    public function actionIndex() {
        $type = $this->type;
        $name = $this->name;
        $destination = $this->original;
        if(!$type || !$name) {
            throw new Exception("--type and --name must be set");
        }

        if(!($type == 'package' || $type == 'layout')) {
            throw new Exception("Type must be either package or layout");
        }

        if($type == 'package') {
            $sourcePath = Yii::getAlias($this->packageBasePath) . '/' . $name;
            $searchTerm = 'Yii::p';
        }else {
            $sourcePath = Yii::getAlias($this->layoutBasePath) . '/' . $name;
            $searchTerm = 'Yii::l';
        }

        if(!is_dir($sourcePath)) {
            throw new Exception("Not a directory at: " . $sourcePath);
        }

        $originalMessages = [];
        if($destination) {
            if(!is_file($destination)) {
                throw new Exception("The translation file for updating does not exists at: " . $destination);
            }

            $originalMessages = require($destination);
            if(!is_array($originalMessages)) {
                throw new Exception("Invalid original file", 1);
            }
        }

        $files = FileHelper::findFiles($sourcePath, [
            'only' => ['*.php'],
            'except' => [
                '.svn',
                '.git',
                '.gitignore',
                '.gitkeep',
                '.hgignore',
                '.hgkeep',
                '/vendor',
            ]
        ]);

        $messages = [];
        foreach($files as $file) {
            $messages = array_merge_recursive($messages, $this->extractMessages($file, $searchTerm));
        }
        
        if(count($messages) > 1) {
            throw new Exception('Found more then one category! ' . VarDumper::export(array_keys($messages)));
        }

        if(!isset($messages[$name])) {
            throw new Exception("Missing category: " . $name);
        }

        $messages = $messages[$name];
        $this->printMessages($messages, $originalMessages);
    }


    public function printMessages($messages, $originalMessages) {
        $ret = [];
        foreach($messages as $value) {
            if(isset($originalMessages[$value])) {
                $ret[$value] = $originalMessages[$value];
            }else {
                $ret[$value] = $value;
            }
        }

        ksort($ret);
        $output = "<?php\nreturn " . VarDumper::export($ret) . ";";
        if($this->override) {
            file_put_contents($this->original, $output);
        }else {
            echo $output;
        }
    }


    protected function extractMessages($fileName, $translator, $ignoreCategories = []) {
        $subject = file_get_contents($fileName);
        $messages = [];

        foreach ((array) $translator as $currentTranslator) {
            $translatorTokens = token_get_all('<?php ' . $currentTranslator);
            array_shift($translatorTokens);
            $tokens = token_get_all($subject);
            $messages = array_merge_recursive($messages, $this->extractMessagesFromTokens($tokens, $translatorTokens, []));
        }

        return $messages;
    }


    protected function extractMessagesFromTokens(array $tokens, array $translatorTokens, array $ignoreCategories) {
        $messages = [];
        $translatorTokensCount = count($translatorTokens);
        $matchedTokensCount = 0;
        $buffer = [];
        $pendingParenthesisCount = 0;

        foreach ($tokens as $token) {
            // finding out translator call
            if ($matchedTokensCount < $translatorTokensCount) {
                if ($this->tokensEqual($token, $translatorTokens[$matchedTokensCount])) {
                    $matchedTokensCount++;
                } else {
                    $matchedTokensCount = 0;
                }
            } elseif ($matchedTokensCount === $translatorTokensCount) {
                // translator found

                // end of function call
                if ($this->tokensEqual(')', $token)) {
                    $pendingParenthesisCount--;

                    if ($pendingParenthesisCount === 0) {
                        // end of translator call or end of something that we can't extract
                        if (isset($buffer[0][0], $buffer[1], $buffer[2][0]) && $buffer[0][0] === T_CONSTANT_ENCAPSED_STRING && $buffer[1] === ',' && $buffer[2][0] === T_CONSTANT_ENCAPSED_STRING) {
                            // is valid call we can extract
                            $category = stripcslashes($buffer[0][1]);
                            $category = mb_substr($category, 1, mb_strlen($category) - 2);

                            //if (!$this->isCategoryIgnored($category, $ignoreCategories)) {
                                $message = stripcslashes($buffer[2][1]);
                                $message = mb_substr($message, 1, mb_strlen($message) - 2);

                                $messages[$category][] = $message;
                            //}

                            $nestedTokens = array_slice($buffer, 3);
                            if (count($nestedTokens) > $translatorTokensCount) {
                                // search for possible nested translator calls
                                $messages = array_merge_recursive($messages, $this->extractMessagesFromTokens($nestedTokens, $translatorTokens, $ignoreCategories));
                            }
                        } else {
                            // invalid call or dynamic call we can't extract
                            //$line = Console::ansiFormat($this->getLine($buffer), [Console::FG_CYAN]);
                            //$skipping = Console::ansiFormat('Skipping line', [Console::FG_YELLOW]);
                            //$this->stdout("$skipping $line. Make sure both category and message are static strings.\n");
                        }

                        // prepare for the next match
                        $matchedTokensCount = 0;
                        $pendingParenthesisCount = 0;
                        $buffer = [];
                    } else {
                        $buffer[] = $token;
                    }
                } elseif ($this->tokensEqual('(', $token)) {
                    // count beginning of function call, skipping translator beginning
                    if ($pendingParenthesisCount > 0) {
                        $buffer[] = $token;
                    }
                    $pendingParenthesisCount++;
                } elseif (isset($token[0]) && !in_array($token[0], [T_WHITESPACE, T_COMMENT])) {
                    // ignore comments and whitespaces
                    $buffer[] = $token;
                }
            }
        }

        return $messages;
    }


    protected function tokensEqual($a, $b)
    {
        if (is_string($a) && is_string($b)) {
            return $a === $b;
        } elseif (isset($a[0], $a[1], $b[0], $b[1])) {
            return $a[0] === $b[0] && $a[1] == $b[1];
        }
        return false;
    }
}
