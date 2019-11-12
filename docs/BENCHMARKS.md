# Benchmarks

<!-- doctoc --title '**Table of contents**' --entryprefix '  - '  docs/BENCHMARKS.md -->
<!-- START doctoc generated TOC please keep comment here to allow auto update -->
<!-- DON'T EDIT THIS SECTION, INSTEAD RE-RUN doctoc TO UPDATE -->
**Table of contents**

  -  [What it should do](#what-it-should-do)
  -  [What to benchmark?](#what-to-benchmark)
  -  [Skeleton benchmark test](#skeleton-benchmark-test)
  -  [Possible commands](#possible-commands)
  -  [Links](#links)

<!-- END doctoc generated TOC please keep comment here to allow auto update -->

## Usage

    cd tests

    # run all benchmarks (default)
    ./runBenchmarks.sh

    # run all benchmarks, output and store results in a html file in
    # /docs/Benchmarks/current.html file
    ./runBenchmarks.sh --output=default

    # use '--store' to save a xml file. E.g:
    ./runBenchmarks.sh --store
    ./runBenchmarks.sh --output=default --store


## What it should do

+ Detect performance changes over the time when collecting results
    - On implementation changes
    - On implementation extension
    - On implementation reduction
    - On e.g. php version or other runner software changes/ updates

+ Finding the best way on how to use the functionality with less resources

+ Detect performance issues
    + Decision helper
        + for generalized code vs. simple RAM intensive and less maintainable code
    + Getting ideas to improve code


## What to benchmark?

+ Basic/ core functionality
    + Of a library (micro checks)
    + Complex mvc function/action calls (complete function calls)

+ Typical usage of a functionality

+ Common functionality to:
    + detect performance changes when changing implementation
    + to have a reporting which tells: Better or worst

+ Long term change detection

+ As helper to find the best usage of an implementation (from now, the current 
  php version on)



## Skeleton benchmark test

Like a phpunit test, here a benchmark test default:

    ```
    /**
     * <className> Benchmarks
     *
     * @BeforeMethods({"beforeBenchmark"})
     * @AfterMethods({"afterBenchmark"})
     *
     * @Iterations(3)               - 3 times of all tests for this class as 
     *                              default. 3 times if possible!
     *                              Otherwise also per methode set-able.
     * @Sleep(mircoseconds)         - Dont forget to set for big calls which may 
     *                              use the filesystem or the database to cooldown
     *                              third party connections which may can end in
     *                              error. Also per method set-able
     */
    class <className>
        extends Mumsys_Benchmarks_Testcase
    {
        /**
         * @var <className>
         */
        private $_object;


        public function beforeBenchmark(): void
        {
            $this->_object = new <className>( /* your params */ );
        }


        public function afterBenchmark(): void
        {
            unset( $this->_object );
        }


        // your benchmarks test methodes like: 
        // public function bench<your benchmark methode name>() {}
        // eg:
        /**
         * Your benchmark: init a new stdClass
         *
         * @Subject                             To be set when not prefixing tests 
         *                                      with "bench<my test method>()"
         * @Warmup(5)                           Warmup: N calls without collecting 
         *                                      the time
         * @Revs(10000)                         10-50K for micro checks
         *                                      1 - 5 for complex tests (eg 
         *                                      application calls) if possible
         * @Sleep(n)                            Good to be set for application 
         *                                      calls to reduce connection problems
         *                                      or file IO
         *
         * @covers <class>::method              Optional but good to have it for 
         *                                      mapping of benchmark test method
         *                                      names to real tested methodes.
         *                                      Future result renderer required!
         */
        public function benchInitPhpStdClass()
        {
            new stdClass();
        }
    ```



## Possible commands

Default:

        cd tests
        ./runBenchmarks.sh

        # store a result for future analysis
        ./runBenchmarks.sh --store

        # default phpbench calls
        vendor/bin/phpbench --help


Playground (internal):

    // ./vendor/bin/phpbench run tests/src/Mumsys_TimerBench.php --report=aggregate --iterations 3
    // ./vendor/bin/phpbench run tests/src/Mumsys_TimerBench.php --report='{"extends": "aggregate", "cols": ["subject", "mode"]}'
    // ./vendor/bin/phpbench run tests/src/Mumsys_TimerBench.php --report=aggregate --progress=dots --report='generator: "table", break: ["revs"]'
    // ./vendor/bin/phpbench run tests/src/Mumsys_TimerBench.php --report=aggregate --progress=dots --report='generator: "table", compare: "revs", cols: ["subject", "mean"], compare_fields: ["mean", "mode"]'



## Links

+ [https://phpbench.readthedocs.io/en/latest/](https://phpbench.readthedocs.io/en/latest/)
+ [https://mike42.me/blog/2019-07-benchmarking-php-code-with-phpbench](https://mike42.me/blog/2019-07-benchmarking-php-code-with-phpbench)
