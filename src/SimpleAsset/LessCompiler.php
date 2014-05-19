<?php
namespace SimpleAsset;

class LessCompiler
{
    private $lessCompilerPath;

    public function __construct($lessCompilerPath = null)
    {
        if ($lessCompilerPath) {
            $this->lessCompilerPath = $lessCompilerPath;
        } else if (defined("LESS_COMPILER_PATH")) {
            $this->lessCompilerPath = LESS_COMPILER_PATH;
        } else {
            $this->lessCompilerPath = `/usr/bin/env which lessc`;
        }
    }

    public function compile($inputFile, $outputFile, $forceCompile = false)
    {
        $shouldCompile = false;

        if ($forceCompile) {
            $shouldCompile = true; // compile no matter what
        } else {
            $inputModTime = @filemtime($inputFile);
            $outputModTime = @filemtime($outputFile) ?: 0; // output file may not exist yet
            $fileHasChanged = ($inputModTime > $outputModTime);

            $findImports = function($file) use (&$fileHasChanged, $outputModTime, $inputFile, &$findImports) {
                preg_match_all("`@import ['|\"](.+?)['|\"];`s", file_get_contents($file), $matches);
                if ($matches) {
                    foreach ($matches[1] as $match) {
                        $importFile = dirname($file) . '/' . $match;
                        if (filemtime($importFile) > $outputModTime) {
                            $fileHasChanged = true;
                            return;
                        } else {
                            $findImports($importFile);
                        }
                    }
                }
            };
            $findImports($inputFile);
            if ($fileHasChanged) {
                $shouldCompile = true;
            }
        }

        if ($shouldCompile) {
            @mkdir(dirname($outputFile), 0777, true);
            $command = "$this->lessCompilerPath $inputFile $outputFile 2>&1";
            ob_start();
            passthru($command, $exitCode);
            $output = ob_get_contents();
            $output = preg_replace('/\x1b(\[|\(|\))[;?0-9]*[0-9A-Za-z]/', "",$output);
            $output = preg_replace('/[\x03|\x1a]/', "", $output);
            ob_end_clean();
            if ($output) {
                throw new \RuntimeException("Less compilation failed: $output");
            }
        }
    }
}