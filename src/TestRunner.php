<?php

declare(strict_types=1);

namespace Wizaplace\PHPUnit\Slicer;

use PHPUnit\Framework\Test;
use PHPUnit\Framework\TestResult;
use PHPUnit\Framework\TestSuite;

class TestRunner extends \PHPUnit\TextUI\TestRunner
{
    public function doRun(Test $suite, array $arguments = [], $exit = true): TestResult
    {
        $this->handleConfiguration($arguments);

        $filters = (new \ReflectionClass($this))->getMethod('processSuiteFilters');
        $filters->setAccessible(true);
        $filters->invoke($this, $suite, $arguments);

        if (isset($arguments['totalSlices'], $arguments['currentSlice']) && $suite instanceof TestSuite) {
            TestSuiteSlicer::slice($suite, $arguments);
        }

        return parent::doRun($suite, $arguments, $exit);
    }
}
