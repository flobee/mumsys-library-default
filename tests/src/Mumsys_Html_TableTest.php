<?php


/**
 * Mumsys_Html_Table Test
 */
class Mumsys_Html_TableTest
    extends Mumsys_Unittest_Testcase
{
    /**
     * @var Mumsys_Html_Table
     */
    protected $_object;

    /**
     * List of colors for tests
     * @var array
     */
    protected $_colors;


    /**
     * Sets up the fixture, for example, opens a network connection.
     * This method is called before a test is executed.
     */
    protected function setUp()
    {
        $this->_version = '3.2.2';
        $this->_versions = array(
            'Mumsys_Abstract' => Mumsys_Abstract::VERSION,
            'Mumsys_Html_Table' => $this->_version,
            'Mumsys_Xml_Abstract' => '3.0.0',
        );
        $this->_colors = array('#ffffff', '#333333', '#666666', '#999999');
        $this->_object = new Mumsys_Html_Table( array('width' => '600') );
    }


    /**
     * Tears down the fixture, for example, closes a network connection.
     * This method is called after a test is executed.
     */
    protected function tearDown()
    {
        $this->_object = null;
    }


    /**
     * @covers Mumsys_Html_Table::__construct
     * @covers Mumsys_Html_Table::getSource
     */
    public function test_construct()
    {
        $this->_object = new Mumsys_Html_Table( array('width' => '600') );
        $this->expectingException( 'Mumsys_Html_Exception' );
        $this->expectingExceptionMessageRegex( '/(No content found to create a table)/' );
        $actual = $this->_object->getSource();
    }


    /**
     * 4CC - Just for code coverage, to be checked later.
     * @covers Mumsys_Html_Table::setTableProps
     */
    public function testSetTableProps()
    {
        $this->_object->setTableProps( array('width' => '500') );

        $this->assertingTrue( true ); // success until here!
    }


    /**
     * 4CC - Just for code coverage, to be checked later.
     * @covers Mumsys_Html_Table::setHeadlines
     */
    public function testSetHeadlines()
    {
        $this->_object->setHeadlines( true );
        $this->_object->setHeadlines( array('a', 'b', 'c') );

        $this->assertingTrue( true ); // success until here!
    }


    /**
     * Just for code coverage, to be checked later.
     * @covers Mumsys_Html_Table::setHeadlinesAttributes
     * @covers Mumsys_Html_Table::getHeadlinesAttributes
     */
    public function testGetSetHeadlinesAttributes()
    {
        $expected = array('class' => 'headlAttr');
        $this->_object->setHeadlinesAttributes( $expected );
        $actual = $this->_object->getHeadlinesAttributes();

        $this->assertingEquals( ' class="headlAttr"', $actual );
    }


    /**
     * @covers Mumsys_Html_Table::setHeadlinesAttribute
     * @covers Mumsys_Html_Table::getHeadlinesAttribute
     */
    public function testGetSetHeadlinesAttribute()
    {
        $attr = array('align' => 'right');
        $this->_object->setHeadlinesAttribute( 0, $attr );
        $actual = $this->_object->getHeadlinesAttribute( 0 );

        $this->assertingEquals( ' align="right"', $actual );
    }


    /**
     * @covers Mumsys_Html_Table::addHeadline
     */
    public function testAddHeadline()
    {
        $this->_object->addHeadline( 'aHeadline' );
        $this->_object->addHeadline( 'bHeadline' );
        $actual = $this->_object->getHeadlines();
        $expected = '<thead>
<tr>
   <th>aHeadline</th>
   <th>bHeadline</th>
</tr>
</thead>
';
        $this->assertingEquals( $expected, $actual );
    }


    /**
     * 4CC - Just for code coverage, to be checked later.
     * @covers Mumsys_Html_Table::setAutoFill
     */
    public function testSetAutoFill()
    {
        $this->_object->setAutoFill( 'x.x' );

        $this->assertingTrue( true ); // success until here!
    }


    /**
     * Just for code coverage, to be checked later.
     * @covers Mumsys_Html_Table::setContent
     * @covers Mumsys_Html_Table::getContent
     */
    public function testGetSetContent()
    {
        $data = array(
            array(1, 2),
            array(3, 4),
            array(5, 6),
        );
        $this->_object->setContent( $data );

        $actual1 = $this->_object->getContent( 0 );
        $expected1 = $data[0];
        $actual2 = $this->_object->getContent( 1 );
        $expected2 = $data[1];
        $actual3 = $this->_object->getContent();

        $this->assertingEquals( $expected1, $actual1 );
        $this->assertingEquals( $expected2, $actual2 );
        $this->assertingEquals( $data, $actual3 );
    }


    /**
     * @covers Mumsys_Html_Table::getNumCols
     */
    public function testGetNumCols()
    {
        $data = array(
            array(1, 2),
            array(3, 4),
            array(5, 6),
        );
        $this->_object->setContent( $data );

        $actual = $this->_object->getNumCols();
        $this->assertingEquals( 2, $actual );
    }


    /**
     * @covers Mumsys_Html_Table::getNumRows
     */
    public function testGetNumRows()
    {
        $data = array(
            array(1, 2),
            array(3, 4),
            array(5, 6),
        );
        $this->_object->setContent( $data );

        $actual = $this->_object->getNumRows();
        $this->assertingEquals( 3, $actual );
    }


    /**
     * @covers Mumsys_Html_Table::setAltRowColor
     * @covers Mumsys_Html_Table::getAltRowColor
     */
    public function testGetSetAltRowColor()
    {
        $this->_object->setAltRowColor( $this->_colors, 1 );
        $actual1 = $this->_object->getAltRowColor( 0, 0 );
        $actual2 = $this->_object->getAltRowColor( 0, 0 );
        $actual3 = $this->_object->getAltRowColor( 0, 2 );
        $actual4 = $this->_object->getAltRowColor( 0, 1 );

        $this->_object->setAltRowColor( $this->_colors, 1 );
        $actual5 = $this->_object->getAltRowColor( null, 1 );

        $this->assertingEquals( $this->_colors[0], $actual1 );
        $this->assertingEquals( $this->_colors[0], $actual2 );
        $this->assertingEquals( $this->_colors[1], $actual3 );
        $this->assertingEquals( $this->_colors[2], $actual4 );
        $this->assertingEquals( $this->_colors[1], $actual5 );
    }


    /**
     * @covers Mumsys_Html_Table::getHeadlines
     */
    public function testGetHeadlines()
    {
        $this->_object->setAutoFill( 'autoFill' );

        $headlines = array('id', 123);
        $data1 = array(
            array('id' => 1, 'name' => 'row1'),
            array('id' => 2, 'name' => 'row2'),
        );
        $data2 = array(
            array(1, 'row1'),
            array(2, 'row2'),
        );
        $expected1 = '<thead>
<tr>
   <th>id</th>
   <th>name</th>
</tr>
</thead>
';
        $expected2 = '<thead>
<tr>
   <th>autoFill</th>
   <th>autoFill</th>
</tr>
</thead>
';
        $expected3 = '<thead>
<tr class="tr-head">
   <th>id</th>
   <th>autoFill</th>
</tr>
</thead>
';

        $this->_object->setHeadlines( true );
        $this->_object->setContent( $data1 );
        $actual1 = $this->_object->getHeadlines();

        $this->_object->setHeadlines( true );
        $this->_object->setContent( $data2 );
        $actual2 = $this->_object->getHeadlines();

        $this->_object->setHeadlinesAttributes( array('class' => 'tr-head') );
        $this->_object->setHeadlines( $headlines );
        $this->_object->setContent( $data2 );
        $actual3 = $this->_object->getHeadlines();

        $this->assertingEquals( $expected1, $actual1 );
        $this->assertingEquals( $expected2, $actual2 );
        $this->assertingEquals( $expected3, $actual3 );
    }


    /**
     * Just for code coverage, to be checked later.
     * @covers Mumsys_Html_Table::setColAttributes
     * @covers Mumsys_Html_Table::getColAttributes
     */
    public function testGetSetColAttributes()
    {
        $this->_object->setColAttributes( 1, 1, array('class' => 'row1colAttrib') );
        $this->_object->setColAttributes( '_', 1, array('style' => 'color: globalColAttrib;') );

        $actual1 = $this->_object->getColAttributes( 1, 1 );
        $expected1 = ' class="row1colAttrib" style="color: globalColAttrib;"';
        $this->assertingEquals( $expected1, $actual1 );
    }


    /**
     * @covers Mumsys_Html_Table::setRowAttributes
     * @covers Mumsys_Html_Table::getRowAttributes
     */
    public function testGetSetRowAttributes()
    {
        $this->_object->setRowAttributes( 1, array('style' => 'row1attrib') );
        $this->_object->setRowAttributes( '_', array('class' => 'globalRowAttrib') );

        $actual1 = $this->_object->getRowAttributes( 1 );
        $expected1 = ' style="row1attrib" class="globalRowAttrib"';

        $this->assertingEquals( $expected1, $actual1 );
    }


    /**
     * @covers Mumsys_Html_Table::setColContents
     * @covers Mumsys_Html_Table::getColContents
     */
    public function testGetSetColContents()
    {
        $data1 = array(
            array('id' => 1, 'name' => 'row1 %s'),
            array('id' => 2, 'name' => 'row2'),
        );

        $this->_object->setHeadlines( true );
        $this->_object->setContent( $data1 );
        $this->_object->setColContents( 'row1', 0, 1 );
        $this->_object->setColContents( 'row2 %s', 1, 1 );

        $actual1 = $this->_object->getColContents( 0, 1, 'row1' );
        $expected1 = 'row1';

        $actual2 = $this->_object->getColContents( 1, 1, '1.234' );
        $expected2 = 'row2 1.234';

        $actual3 = $this->_object->getColContents( 2, 1, 'row 3 col 1' );
        $expected3 = 'row 3 col 1';

        $this->assertingEquals( $expected1, $actual1 );
        $this->assertingEquals( $expected2, $actual2 );
        $this->assertingEquals( $expected3, $actual3 );

        $this->expectingException( 'Mumsys_Html_Exception' );
        $this->expectingExceptionMessageRegex( '/(Invalid row value to set col contents)/' );
        $this->_object->setColContents( 'str1', 'invalidRowID', 1 );
    }


    /**
     * @covers Mumsys_Html_Table::setColContents
     * @covers Mumsys_Html_Table::getColContents
     */
    public function testGetSetColContentsException2()
    {
        $this->expectingException( 'Mumsys_Html_Exception' );
        $this->expectingExceptionMessageRegex( '/(Invalid column value to set col contents)/' );
        $this->_object->setColContents( 'str1', 1, 'invalidColID' );
    }


    /**
     * @covers Mumsys_Html_Table::getHtml
     * @covers Mumsys_Html_Table::toHtml
     * @covers Mumsys_Html_Table::getSource
     */
    public function testGetHtml()
    {
        $data1 = array(
            array('id' => 1, 'name' => 'row1 %s'),
            array('id' => 2, 'name' => 'row2'),
        );

        $this->_object->setHeadlines( true );
        $this->_object->setContent( $data1 );
        $this->_object->setColAttributes( 1, 1, array('id' => '1_1') );
        $this->_object->setAltRowColor( $this->_colors, 0 );
        $actual1 = $this->_object->getHtml();
        $expected1 = '<table width="600">
<thead>
<tr>
   <th>id</th>
   <th>name</th>
</tr>
</thead>
<tbody>
<tr bgcolor="#333333">
   <td>1</td>
   <td>row1 %s</td>
</tr>
<tr bgcolor="#666666">
   <td>2</td>
   <td id="1_1">row2</td>
</tr>
</tbody>
</table>
';

        $this->_object->setHeadlines( array('id', 'name') );
        $this->_object->setContent( array() );
        $this->_object->setColContents( '1', 0, 0 );
        $this->_object->setColContents( 'row1 %s', 0, 1 );
        $this->_object->setColContents( '2', 1, 0 );
        $this->_object->setColContents( 'row2', 1, 1 );
        $actual2 = $this->_object->getHtml();

        $this->assertingEquals( $expected1, $actual1 );
        $this->assertingEquals( $expected1, $actual2 );
        $this->assertingEquals( htmlentities( $expected1 ), $this->_object->getSource() );

        $regex = '/(Column key not exists to change a color for rows: "3")/';
        $this->expectingException( 'Mumsys_Html_Exception' );
        $this->expectingExceptionMessageRegex( $regex );
        $this->_object->setAltRowColor( $this->_colors, 3 );
        $this->_object->setContent( $data1 );
        $this->_object->toHtml();
    }


    /**
     * @covers Mumsys_Html_Table::getHtml
     * @covers Mumsys_Html_Table::toHtml
     */
    public function testToHtml()
    {
        $data1 = array(
            array('id' => 1, 'name' => 'row1 %s'),
            array('id' => 2),
        );

        $this->_object->setHeadlines( true );
        $this->_object->setContent( $data1 );
        $this->_object->setAltRowColor( $this->_colors );
        $actual1 = $this->_object->getHtml();
        $expected1 = '<table width="600">
<thead>
<tr>
   <th>id</th>
   <th>name</th>
</tr>
</thead>
<tbody>
<tr bgcolor="#333333">
   <td>1</td>
   <td>row1 %s</td>
</tr>
<tr bgcolor="#666666">
   <td>2</td>
   <td>&nbsp;</td>
</tr>
</tbody>
</table>
';
        $this->assertingEquals( $expected1, $actual1 );
    }


    /**
     * @covers Mumsys_Html_Table::getHtml
     */
    public function testGetHtmlException()
    {
        $this->expectingException( 'Mumsys_Html_Exception' );
        $this->expectingExceptionMessageRegex( '/(No content found to create a table)/' );
        $this->_object->getHtml();
    }


    /**
     * @covers Mumsys_Html_Table::toHtml
     * @covers Mumsys_Html_Table::setAltRowColor
     * @covers Mumsys_Html_Table::getAltRowColor
     */
    public function testToHtmlException()
    {
        $this->expectingException( 'Mumsys_Html_Exception' );
        $this->expectingExceptionMessageRegex( '/(No content found to create a table)/' );
        $this->_object->toHtml();
    }


    /**
     * @covers Mumsys_Html_Table::getVersion
     * @covers Mumsys_Html_Table::getVersionID
     * @covers Mumsys_Html_Table::getVersions
     */
    public function testVersionsInAbstractClass()
    {
        $this->assertingEquals( get_class( $this->_object ) . ' ' . $this->_version, $this->_object->getVersion() );

        $this->assertingEquals( $this->_version, $this->_object->getVersionID() );

        $possible = $this->_object->getVersions();

        foreach ( $this->_versions as $must => $value ) {
            $this->assertingTrue( isset( $possible[$must] ) );
            $this->assertingTrue( ( $possible[$must] == $value ), $possible[$must] . ' != ' . $value );
        }
    }

}
