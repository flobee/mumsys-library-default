<?php

/**
 * Test class for Mumsys_FileSystem_Default.
 */
class Mumsys_FileSystem_DefaultTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_FileSystem_Default
     */
    protected $_object;

    /**
     * List of test files to be created and removed
     * @var string
     */
    protected $_testdirs;

    /**
     * Test directory.
     * @var string
     */
    protected $_testsDir;


    protected function setUp()
    {
        $this->_version = '3.1.0';
        $this->_versions = array(
            'Mumsys_FileSystem_Default' => $this->_version,
            'Mumsys_FileSystem_Common_Abstract' => '3.1.0',
        );

        $this->_testsDir = realpath( dirname( __FILE__ ) . '/../' );
        $this->_testdirs = array(
            'rm1' => $this->_testsDir . '/tmp/unittest-mkdir/mkdirs/testfile',
            'rm2' => $this->_testsDir . '/tmp/unittest-mkdir/testfile',
            'dir' => $this->_testsDir . '/tmp/unittest-mkdir',
            'dirs' => $this->_testsDir . '/tmp/unittest-mkdir/mkdirs',
            'file' => $this->_testsDir . '/tmp/unittest',
            'file2' => $this->_testsDir . '/tmp/unittest_2',
            'invalid' => '/root/goes/here',
            'rm3' => $this->_testsDir . '/tmp/mkdirs',
            'rm4' => $this->_testsDir . '/tmp/unittest-mkdir.lnk',
        );
        touch( $this->_testdirs['file'] );

        $this->_object = new Mumsys_FileSystem_Default();
    }


    protected function tearDown()
    {
        foreach ( $this->_testdirs as $dir ) {
            if ( !@rmdir( $dir ) ) {
                @unlink( $dir );
            }
        }
    }


    /**
     * @covers Mumsys_FileSystem_Default::__construct
     */
    public function test__constructor()
    {
        $this->_object = new Mumsys_FileSystem_Default();
        $this->assertInstanceOf( 'Mumsys_FileSystem_Default', $this->_object );
        $this->assertInstanceOf( 'Mumsys_Abstract', $this->_object );
    }


    /**
     * @covers Mumsys_FileSystem_Default::scanDirInfo
     */
    public function testScanDirInfo()
    {
        @mkdir( $this->_testdirs['dir'], 0755 );
        @mkdir( $this->_testdirs['dirs'], 0755 );
        @touch( $this->_testdirs['dir'] . '/testfile' );
        @touch( $this->_testdirs['dirs'] . '/testfile' );

        $filters = array('/(unittest)/i');

        // simple directory
        $actual1 = $this->_object->scanDirInfo(
            $this->_testdirs['dir'], true, false, array(), -1, 1001
        );
        $expected1 = array(
            $this->_testsDir . '/tmp/unittest-mkdir/mkdirs' => array(
                'file' => $this->_testsDir . '/tmp/unittest-mkdir/mkdirs',
                'name' => 'mkdirs',
                'path' => $this->_testsDir . '/tmp/unittest-mkdir',
                'size' => 22,
                'type' => 'dir',
            ),
            $this->_testsDir . '/tmp/unittest-mkdir/testfile' => array(
                'file' => $this->_testsDir . '/tmp/unittest-mkdir/testfile',
                'name' => 'testfile',
                'path' => $this->_testsDir . '/tmp/unittest-mkdir',
                'size' => 0,
                'type' => 'file',
            )
        );

        // recursive directory + filter
        $actual2 = $this->_object->scanDirInfo(
            $this->_testdirs['dir'], true, true, $filters
        );
        $expected2 = array(
            $this->_testsDir . '/tmp/unittest-mkdir/mkdirs' => array(
                'file' => $this->_testsDir . '/tmp/unittest-mkdir/mkdirs',
                'name' => 'mkdirs',
                'path' => $this->_testsDir . '/tmp/unittest-mkdir',
                'size' => 22,
                'type' => 'dir',
            ),
            $this->_testsDir . '/tmp/unittest-mkdir/testfile' => array(
                'file' => $this->_testsDir . '/tmp/unittest-mkdir/testfile',
                'name' => 'testfile',
                'path' => $this->_testsDir . '/tmp/unittest-mkdir',
                'size' => 0,
                'type' => 'file',
            ),
            $this->_testsDir . '/tmp/unittest-mkdir/mkdirs/testfile' => array(
                'file' => $this->_testsDir . '/tmp/unittest-mkdir/mkdirs/testfile',
                'name' => 'testfile',
                'path' => $this->_testsDir . '/tmp/unittest-mkdir/mkdirs',
                'size' => 0,
                'type' => 'file',
            ),
        );
        unlink( $this->_testdirs['dir'] . '/testfile' );
        unlink( $this->_testdirs['dirs'] . '/testfile' );

        // test unreadable path
        $actual3 = $this->_object->scanDirInfo( '/root', true, true );

        $this->assertEquals( $expected1, $actual1 );
        $this->assertEquals( $expected2, $actual2 );
        $this->assertFalse( $actual3 );
    }


    /**
     * @covers Mumsys_FileSystem_Default::getFileDetails
     * @covers Mumsys_FileSystem_Default::_getFileDetailsPrepare
     */
    public function testGetFileDetails()
    {
        $actual1 = $this->_object->getFileDetails( __FILE__ );
        $expected1 = array(
            'file' => __FILE__,
            'name' => basename( __FILE__ ),
            'size' => filesize( __FILE__ ),
            'type' => 'file',
            'path' => dirname( __FILE__ ),
        );

        $actual2 = $this->_object->getFileDetails(
            dirname( __FILE__ ), basename( __FILE__ )
        );

        $this->assertEquals( $expected1, $actual1 );
        $this->assertEquals( $expected1, $actual2 );

        $this->expectExceptionMessageRegExp( '/(File "\/i\/don\/t\/exist" not found)/' );
        $this->expectException( 'Mumsys_FileSystem_Exception' );
        $actual2 = $this->_object->getFileDetails( '/i/don/t/exist' );
    }


    /**
     * @covers Mumsys_FileSystem_Default::getFileDetailsExtended
     * @covers Mumsys_FileSystem_Default::_getFileDetailsPrepare
     */
    public function testGetFileDetailsExtended()
    {
        // info for a file
        $curFile = __FILE__;
        $actual1 = $this->_object->getFileDetailsExtended( $curFile );
        $stat = @lstat( $curFile );
        $expected1 = array(
            'file' => $curFile,
            'name' => basename( $curFile ),
            'size' => filesize( $curFile ),
            'type' => 'file',
            'path' => dirname( $curFile ),
            'is_file' => true,
            'is_dir' => false,
            'is_link' => false,
            'is_readable' => true,
            'is_writable' => true,
            'permission' => $stat['mode'],
            'owner' => $stat['uid'],
            'group' => $stat['gid'],
            'mtime' => $stat['mtime'],
            'atime' => $stat['atime'],
            'ctime' => $stat['ctime'],
            'filetype' => trim( shell_exec( 'file -b -p "' . $curFile . '";' ) ),
            'is_executable' => true,
            'ext' => 'php',
            'mimetype' => 'text/x-php',
            'owner_name' => @reset( posix_getpwuid( $stat['uid'] ) ),
            'group_name' => @reset( posix_getgrgid( $stat['gid'] ) ),
        );
        // info for a directory
        $actual2 = $this->_object->getFileDetailsExtended( $this->_testsDir . '/tmp' );
        $stat = @lstat( $this->_testsDir . '/tmp' );
        $expected2 = array(
            'file' => $this->_testsDir . '/tmp',
            'name' => 'tmp',
            'size' => filesize( $this->_testsDir . '/tmp' ),
            'type' => 'dir',
            'path' => $this->_testsDir,
            'is_file' => false,
            'is_dir' => true,
            'is_link' => false,
            'is_readable' => true,
            'is_writable' => true,
            'permission' => $stat['mode'],
            'owner' => $stat['uid'],
            'group' => $stat['gid'],
            'mtime' => $stat['mtime'],
            'atime' => $stat['atime'],
            'ctime' => $stat['ctime'],
            'filetype' => trim(
                shell_exec( 'file -b -p "' . $this->_testsDir . '/tmp";' )
            ),
            'is_executable' => true,
            'ext' => false,
            'owner_name' => @reset( posix_getpwuid( $stat['uid'] ) ),
            'group_name' => @reset( posix_getgrgid( $stat['gid'] ) ),
        );
        // info for a link
        touch( $this->_testdirs['file'] );
        symlink( $this->_testdirs['file'], $this->_testsDir . '/tmp/link' );
        $stat = @lstat( $this->_testsDir . '/tmp/link' );
        $actual3 = $this->_object->getFileDetailsExtended(
            $this->_testsDir . '/tmp/link'
        );
        $expected3 = array(
            'file' => $this->_testsDir . '/tmp/link',
            'name' => 'link',
            'size' => $stat['size'],
            'type' => 'link',
            'path' => $this->_testsDir . '/tmp',
            'is_file' => true,
            'is_dir' => false,
            'is_link' => true,
            'is_readable' => true,
            'is_writable' => true,
            'permission' => $stat['mode'],
            'owner' => $stat['uid'],
            'group' => $stat['gid'],
            'mtime' => $stat['mtime'],
            'atime' => $stat['atime'],
            'ctime' => $stat['ctime'],
            'filetype' => $this->_object->getFileType( $this->_testsDir . '/tmp/link' ),
            'is_executable' => true,
            'ext' => '',
            'mimetype' => 'inode/x-empty',
            'target' => $this->_testsDir . '/tmp/unittest',
            'owner_name' => @reset( posix_getpwuid( $stat['uid'] ) ),
            'group_name' => @reset( posix_getgrgid( $stat['gid'] ) ),
        );
        @unlink( $this->_testsDir . '/tmp/link' );

        $this->assertEquals( $expected1, $actual1 );
        $this->assertEquals( $expected2, $actual2 );
        $this->assertEquals( $expected3, $actual3 );
    }


    /**
     * @covers Mumsys_FileSystem_Default::getFileType
     */
    public function testGetFileType()
    {
        $actual = $this->_object->getFileType( '/bin/sh' );

        // OS related output
        $expecteds = array(
            "cannot open `/usr/bin/sh' (No such file or directory)\n",
            "ERROR: cannot open `/usr/bin/sh' (No such file or directory)\n",
            "finfo::file(/usr/bin/sh): failed to open stream: No such file or "
            . "directory",
            "ELF 32-bit LSB shared object, Intel 80386, version 1 (SYSV)",
            'ELF 64-bit LSB executable, x86-64, version 1 (SYSV)',
            'ELF 64-bit LSB shared object, x86-64, version 1 (SYSV)',
            "symbolic link to dash\n",
        );

        $this->assertTrue( in_array( $actual, $expecteds ) );
    }


    /**
     * @covers Mumsys_FileSystem_Default::copy
     */
    public function testCopy()
    {
        $actual1 = $this->_object->copy(
            $this->_testdirs['file'], $this->_testdirs['file2']
        );
        $expected1 = $this->_testdirs['file2'];
        // keep copy
        $actual2 = $this->_object->copy(
            $this->_testdirs['file'], $this->_testdirs['file2'], true, 1
        );
        $expected2 = $this->_testdirs['file2'] . '.2';
        @unlink( $expected2 );
        // target is a directory
        $actual3 = $this->_object->copy(
            $this->_testdirs['file'], $this->_testsDir . '/tmp', true
        );
        $expected3 = $this->_testdirs['file'] . '.1';
        @unlink( $expected3 );

        $this->assertEquals( $expected1, $actual1 );
        $this->assertEquals( $expected2, $actual2 );
        $this->assertEquals( $expected3, $actual3 );

        // source is a dir exception
        $regex = '/(Source file: A directory was found. only file copying is '
            . 'implemented)/';
        $this->expectExceptionMessageRegExp( $regex );
        $this->expectException( 'Mumsys_FileSystem_Exception' );
        $this->_object->copy( $this->_testsDir . '/tmp/', '/home/' );
    }


    /**
     * @covers Mumsys_FileSystem_Default::copy
     */
    public function testCopyException()
    {
        $regex = '/(Copy error)/i';
        $this->expectExceptionMessageRegExp( $regex );
        $this->expectException( 'Mumsys_FileSystem_Exception' );
        $this->_object->copy( $this->_testdirs['file'], '/' );
    }


    /**
     * @covers Mumsys_FileSystem_Default::rename
     */
    public function testRename()
    {
        // default copy
        $source = $this->_testdirs['dir'] . '/testfile';
        $expected1 = $this->_testdirs['dir'] . '/filetest';
        @mkdir( $this->_testdirs['dir'], 0755 );
        @touch( $source );
        $actual1 = $this->_object->rename( $source, $expected1 );
        // target exists
        @touch( $source );
        $actual2 = $this->_object->rename( $source, $expected1, true );
        $expected2 = $this->_testdirs['dir'] . '/filetest.1';
        //stream context
        @touch( $source );
        $streamCtx = stream_context_create();
        $actual3 = $this->_object->rename(
            $source, $expected1, false, $streamCtx
        );
        $expected3 = $this->_testdirs['dir'] . '/filetest';

        @unlink( $expected1 );
        @unlink( $expected2 );
        @rmdir( $this->_testdirs['dir'] );

        $this->assertEquals( $expected1, $actual1 );
        $this->assertEquals( $expected2, $actual2 );
        $this->assertEquals( $expected3, $actual3 );

        // source emty exception
        $regex = '/(Rename failt for reason: Source "" is no directory and no file)/';
        $this->expectExceptionMessageRegExp( $regex );
        $this->expectException( 'Mumsys_FileSystem_Exception' );
        $this->_object->rename( '', $this->_testsDir . '/tmp/something' );
    }


    /**
     * @covers Mumsys_FileSystem_Default::rename
     */
    public function testRenameException()
    {
        // rename permission error
        $msg[] = 'Rename failt for reason: Copy error for: "'
            . $this->_testsDir . '/tmp/unittest" '
            . 'copy(/root//unittest): failed to open stream: Permission denied';
        $msg[] = 'Rename failt for reason: rename(): Permission denied';

        $this->expectException( 'Mumsys_FileSystem_Exception' );
        $this->_object->rename( $this->_testdirs['file'], '/root/' );
    }


    /**
     * @covers Mumsys_FileSystem_Default::link
     */
    public function testLink()
    {
        $actual1 = $this->_object->link(
            $this->_testsDir . '/tmp', $this->_testdirs['dir'], 'soft', 'abs',
            false
        );
        $expected1 = $this->_testdirs['dir'];
        // is_link OK
        $actual2 = $this->_object->link(
            $this->_testsDir . '/tmp', $this->_testdirs['dir'], 'soft', 'abs',
            false
        );
        @unlink( $this->_testdirs['dir'] );

        // hard link (owned by myself ok, otherwise possible error/exceptions
        // with write perms)
        touch( $this->_testdirs['dir'] );
        $actual3 = $this->_object->link(
            $this->_testdirs['dir'], $this->_testsDir . '/tmp/lnkname', 'hard',
            'abs', false
        );
        $expected3 = $this->_testsDir . '/tmp/lnkname';
        @unlink( $this->_testsDir . '/tmp/lnkname' );
        @unlink( $this->_testdirs['dir'] );

        // relative links
        $actual4 = $this->_object->link(
            $this->_testsDir . '/tmp', $this->_testdirs['dir'], 'soft', 'rel',
            false
        );
        $expected4 = $this->_testdirs['dir'];

        // keep copy
        $actual5 = $this->_object->link(
            $this->_testsDir . '/tmp', $this->_testdirs['dir'], 'soft', 'abs',
            true
        );
        $expected5 = $this->_testdirs['dir'] . '.lnk';

        $this->assertEquals( $expected1, $actual1 );
        $this->assertEquals( $expected1, $actual2 );
        $this->assertEquals( $expected3, $actual3 );
        $this->assertEquals( $expected4, $actual4 );
        $this->assertEquals( $expected5, $actual5 );

        // test realpath exception + last, catched exception
        unlink( $this->_testdirs['dir'] );
        $regex = '/(Linking failt for source: "'
            . str_replace( '/', '\/', $this->_testsDir ) . '\/tmp"; target: "'
            . str_replace( '/', '\/', $this->_testsDir )
            . '\/tmp\/unittest-mkdir\/mkdirs". '
            . 'Real path not found for "'
            . str_replace( '/', '\/', $this->_testsDir )
            . '\/tmp\/unittest-mkdir")/';
        $this->expectExceptionMessageRegExp( $regex );
        $this->expectException( 'Mumsys_FileSystem_Exception' );
        $this->_object->link(
            $this->_testsDir . '/tmp', $this->_testdirs['dirs'], 'soft', 'rel',
            false
        );
    }


    /**
     * @covers Mumsys_FileSystem_Default::link
     */
    public function testLinkException()
    {
        // invalid link type
        $regex = '/('
            . 'Linking failt for source: "'
            . str_replace( '/', '\/', $this->_testsDir )
            . '\/tmp"; target: "' . str_replace( '/', '\/', $this->_testsDir )
            . '\/tmp\/unittest-mkdir"\. Invalid link type "invalidType" '
            . '\(Use soft\|hard\)'
            . ')/';
        $this->expectExceptionMessageRegExp( $regex );
        $this->expectException( 'Mumsys_FileSystem_Exception' );
        $this->_object->link(
            $this->_testsDir . '/tmp', $this->_testdirs['dir'], 'invalidType',
            'rel', false
        );
    }


    /**
     * @covers Mumsys_FileSystem_Default::unlink
     * @covers Mumsys_FileSystem_Default::rmFile
     */
    public function testUnlinkRmFile()
    {
        $dir = $this->_testdirs['dir'];
        $this->_object->mkdir( $dir );
        touch( ($file = $dir . '/toUnlink.test' ) );

        $this->assertTrue( $this->_object->unlink( $dir ) ); // not a file
        $this->assertTrue( $this->_object->unlink( $file ) );

        $fileDifferentOwnership = '/usr/bin/php';
        try {
            $msg = 'Testing unlink() exception failed! '
                . 'Please create a file where you don\'t have write access but'
                . ' read access and with a different ownership! '
                . 'E.g.: ~/unittests/somefile.test (owner whether you, your '
                . 'team and not root) und try to test with this file location';
            // security check, backup
            if ( @copy( $fileDifferentOwnership,
                    $fileDifferentOwnership . '.bak' ) ) {
                $this->markTestIncomplete( 'Security abort!!! ' . $msg );
            }

            $this->_object->rmFile( $fileDifferentOwnership );

            $this->markTestIncomplete( $msg );
        }
        catch ( Exception $e ) {
            $message = sprintf(
                'Can not delete file "%1$s"', $fileDifferentOwnership
            );
            $this->assertEquals( $message, $e->getMessage() );
            $this->assertInstanceOf( 'Mumsys_FileSystem_Exception', $e );
        }
    }


    /**
     * @covers Mumsys_FileSystem_Default::mkdir
     */
    public function testMkdir()
    {
        $dir = $this->_testdirs['dir'];
        $this->_object->mkdir( $dir );

        $this->assertTrue( file_exists( $dir ) );
        $this->assertFalse( $this->_object->mkdir( $dir ) ); // exists error

        $regex = '/(Can not create dir: "\/xyz" mode: "755". Message: '
            . 'mkdir\(\): Permission denied)/';
        $this->expectExceptionMessageRegExp( $regex );
        $this->expectException( 'Mumsys_FileSystem_Exception' );
        $this->_object->mkdir( '/xyz' );
    }


    /**
     * @covers Mumsys_FileSystem_Default::mkdirs
     * @covers Mumsys_FileSystem_Default::mkdir
     *
     * @todo how to test for 100% code coverage?
     */
    public function testMkdirs()
    {
        $dir = $this->_testdirs['dirs'];
        $this->_object->mkdirs( $dir );

        $this->assertTrue( file_exists( $dir ) ); // created
        $this->assertTrue( $this->_object->mkdirs( $dir ) ); // exists
    }


    /**
     * Test mkdir fails and rm already created.
     *
     * @covers Mumsys_FileSystem_Default::mkdirs
     * @covers Mumsys_FileSystem_Default::mkdir
     */
    public function testMkdirsException()
    {
        $dir = $this->_testdirs['dirs'] . '/x/';
        $message = '';
        $this->_object->mkdirs(
            $this->_testdirs['dirs'] . '/x/../../home/user', 0700
        );
        $this->assertFalse( file_exists( $dir ) );

        $this->assertTrue( $this->_object->mkdirs( $dir ) ); // created
        $this->assertTrue( file_exists( $dir ) );

        @rmdir( $dir );
    }


    /**
     * @covers Mumsys_FileSystem_Default::rmdir
     * @covers Mumsys_FileSystem_Default::rmdirs
     */
    public function testRmDir()
    {
        $dir = $this->_testdirs['dirs'];
        $this->_object->mkdirs( $dir );

        $this->assertTrue( $this->_object->rmdir( '/not/existing/path' ) );
        $this->assertTrue( $this->_object->rmdir( $dir ) );

        $this->_object->mkdirs( $dir );
        $this->assertTrue( $this->_object->rmdirs( '/not/existing/path' ) );
        $this->assertTrue( $this->_object->rmdirs( $this->_testdirs['dir'] ) );

        $regex = '/(Can not delete directory "\/tmp")/';
        $this->expectException( 'Mumsys_FileSystem_Exception' );
        $this->expectExceptionMessageRegExp( $regex );
        $this->assertTrue( $this->_object->rmdir( '/tmp' ) );
    }


    /**
     * Removes a directory recusivly.
     * @covers Mumsys_FileSystem_Default::rmdirs
     */
    public function testRmDirsException()
    {
        $this->expectException( 'Exception' );
        $this->_object->rmdirs( '/root/' );
    }


    /**
     * @covers Mumsys_FileSystem_Default::getRelativeDir
     */
    public function testGetRelativeDir()
    {
        $actual1 = $this->_object->getRelativeDir( '/tmp/here', '/home/there' );
        $expected1 = '../../home/there/';

        $actual2 = $this->_object->getRelativeDir(
            '/home/user/mydir', '/share/data/thatdir'
        );
        $expected2 = '../../../share/data/thatdir/';

        $actual3 = $this->_object->getRelativeDir(
            './myfile', './data/thatdir'
        );
        $expected3 = '../data/thatdir/';

        $actual4 = $this->_object->getRelativeDir(
            $this->_testsDir . '/tmp/',
            $this->_testsDir . '/tmp/unittest-mkdir/mkdirs/'
        );
        $expected4 = './unittest-mkdir/mkdirs/';

        $this->assertEquals( $expected1, $actual1 );
        $this->assertEquals( $expected2, $actual2 );
        $this->assertEquals( $expected3, $actual3 );
        $this->assertEquals( $expected4, $actual4 );
    }


    /**
     * @covers Mumsys_FileSystem_Default::coolfilesize
     */
    public function testCoolfilesize()
    {
        $actual = array();
        $actual[] = $this->_object->coolfilesize( 1000, 2 );
        $actual[] = $this->_object->coolfilesize( 10000, 2 );
        $actual[] = $this->_object->coolfilesize( 10000000, 2 );
        $actual[] = $this->_object->coolfilesize( 10000000000, 2 );
        $actual[] = $this->_object->coolfilesize( 10000000000000, 2 );
        $actual[] = $this->_object->coolfilesize( 1000000000000000, 2 );
        $expected = array(
            '1000 Bytes',
            '10 KB',
            '10 MB',
            '10 GB',
            '10 TB',
            '1000 TB',
        );
        $this->assertEquals( $expected, $actual );
    }

    // --- test abstract and versions


    /**
     * @covers Mumsys_FileSystem_Common_Abstract::extGet
     */
    public function testextGet()
    {
        $actual1 = $this->_object->extGet( 'filename.ext' );
        $actual2 = $this->_object->extGet( 'info' );

        $this->assertEquals( 'ext', $actual1 );
        $this->assertEquals( '', $actual2 );
    }


    /**
     * @covers Mumsys_FileSystem_Common_Abstract::nameGet
     */
    public function testnameGet()
    {
        $actual1 = $this->_object->nameGet( 'filename.ext' );
        $actual2 = $this->_object->nameGet( '/some/file/at/filename' );

        $this->assertEquals( 'filename', $actual1 );
        $this->assertEquals( 'filename', $actual2 );
    }


    /**
     * @covers Mumsys_Abstract::getVersions
     */
    public function testgetVersions()
    {
        $message = 'A new version exists. You should have a look at '
            . 'the code coverage to verify all code was tested and not only '
            . 'all existing tests where checked!';
        $this->assertEquals(
            $this->_version, Mumsys_FileSystem_Default::VERSION, $message
        );

        $possible = $this->_object->getVersions();

        foreach ( $this->_versions as $must => $value ) {
            $message = 'Invalid: ' . $must . '::' . $value;
            $this->assertTrue( isset( $possible[$must] ), $message );
            $this->assertEquals( $possible[$must], $value, $message );
        }
    }

}
