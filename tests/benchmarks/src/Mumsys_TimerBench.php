<?php

// ./vendor/bin/phpbench run tests/src/Mumsys_TimerBench.php --report=aggregate --iterations 3
// ./vendor/bin/phpbench run tests/src/Mumsys_TimerBench.php --report='{"extends": "aggregate", "cols": ["subject", "mode"]}'
// ./vendor/bin/phpbench run tests/src/Mumsys_TimerBench.php --report=aggregate --progress=dots --report='generator: "table", break: ["revs"]'
// ./vendor/bin/phpbench run tests/src/Mumsys_TimerBench.php --report=aggregate --progress=dots --report='generator: "table", compare: "revs", cols: ["subject", "mean"], compare_fields: ["mean", "mode"]'


/**
 * Mumsys_Timer Benchmarks
 *
 * @BeforeMethods({"setUp"})
 * @AfterMethods({"tearDown"})
 */
class Mumsys_TimerBench
//    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Timer
     */
    private $_object;


    public function setUp()
    {
        $this->_object = new Mumsys_Timer( true );
    }


    public function tearDown()
    {
        unset( $this->_object );
    }


    /**
     * @Subject
     *
     * @Warmup(5)
     * @Revs(1000)
     * @Iterations(1)
     */
    public function benchInitAndStart()
    {
        new Mumsys_Timer( true );
    }


    /**
     * @Subject
     *
     * @Warmup(5)
     * @Revs(1000)
     * @Iterations(1)
     */
    public function benchInitAndStartStop()
    {
        $object = new Mumsys_Timer( true );
        $object->stop();
    }


    /**
     * @Subject
     *
     * @Revs(1000)
     * @Iterations(1)
     *  @ Iterations({1,5,10})
     */
    public function benchInitThanStart()
    {
        $object = new Mumsys_Timer( false );
        $object->start();
    }

    /**
     * @Subject
     *
     * @Revs(1000)
     * @Iterations(1)
     *  @ Iterations({1,5,10})
     */
    public function benchInitThanStartStop()
    {
        $object = new Mumsys_Timer( false );
        $object->start();
        $object->stop();
    }

}
