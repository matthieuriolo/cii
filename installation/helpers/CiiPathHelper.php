<?php

namespace app\helpers;
use app\helpers\CiiInstallationHelper;


class CiiPathHelper extends CiiInstallationHelper {
    public $title = 'Check paths';
    function phaseName() {
        return 'paths';
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
            $this->result['summary']['total']++;
            $requirement = $this->normalizeRequirement($rawRequirement);



            if(
                ($requirement['isWritable'] != $requirement['is_w'])
                ||
                ($requirement['isReadable'] != $requirement['is_r'])
                ||
                ($requirement['isFile'] != $requirement['is_f'])
                ||
                ($requirement['isExecutable'] != $requirement['is_e'])
            ) {

                $requirement['error'] = true;
                $this->result['summary']['errors']++;
            }

            
            $this->result['requirements'][] = $requirement;
        }

        return $this;
    }

    function normalizeRequirement($requirement) {
        $p = realpath($requirement['path']);
        if(!isset($requirement['isExecutable'])) {
            $requirement['isExecutable'] = false;
        }

        if(!isset($requirement['isWritable'])) {
            $requirement['isWritable'] = false;
        }

        if(!isset($requirement['isReadable'])) {
            $requirement['isReadable'] = true;
        }

        if(!isset($requirement['isFile'])) {
            $requirement['isFile'] = false;
        }
        
        if(!$requirement['isFile']) {
            $requirement['isExecutable'] = false;
        }
        
        

        $requirement['is_f'] = is_file($p);
        $requirement['is_r'] = is_readable($p);
        $requirement['is_w'] = is_writable($p);
        $requirement['is_e'] = $requirement['is_f'] && is_executable($p);

        $requirement['error'] = false;
        return $requirement;
    }
}
