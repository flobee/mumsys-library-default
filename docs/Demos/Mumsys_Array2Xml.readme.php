<?php

/**
 * Mumsys_Array2Xml_Default
 * for MUMSYS Library for Multi User Management System
 *
 * @license LGPL Version 3 http://www.gnu.org/licenses/lgpl-3.0.txt
 * @copyright (c) 2005 by Florian Blasel
 * @author Florian Blasel <flobee.code@gmail.com>
 *
 * @category    Mumsys
 * @package     Library
 * @subpackage  Array2Xml
 * @version 0.1 - Created on 2005-01-15
 * $Id: testArray2XmlDemo.php 3254 2016-02-09 20:57:53Z flobee $
 */

// +---------------------------------------------------------------------------+
// |                               DEMO                                        |
// | WARNING: this tests/demo not ready to run. just read and enable the parts |
// | to find out how this works. Otherwise you may check the test file.        |
// +---------------------------------------------------------------------------+


/** @var Mumsys_Array2Xml_Default */
$oXml = new Mumsys_Array2Xml_Default();

//
// The data-tree contains at least the root element, attributes and a value/s
// Values containing a string or empty for a node/element-value
// array as nodeValues
// e.g.:
//      array(
//			array(
//              'nodeName'=>'tracks',
//				'nodeAttr'=>array('tracksid'=>'1234'),
//	 		    'nodeValues'=>array(
//	 		        array('elementKey'=>'elementVALUE1', array('tracksid'=>'123')),
//					array('elementKey'=>'elementVALUE2', array('tracksid'=>'456')),
//					array('elementKey'=>'elementVALUE3', array('tracksid'=>'789')),
//              )
//			),
// creates:
//		<tracks>
//			<elementKey tracksid="123">elementVALUE1</elementKey>
//			<elementKey tracksid="456">elementVALUE2</elementKey>
//			<elementKey tracksid="789">elementVALUE3</elementKey>
//		</tracks>
//
// these nodevalues will create the same tree:
//		array(
//            array(
//            'nodeName' => 'tracks',
//             'nodeAttr' => array('tracksid' => '1234'),
//             'nodeValues' => array(
//            array('nodeName' => 'elementKey',
//             'nodeAttr' => array('tracksid' => '789'),
//             'nodeValues' => 'elementVALUE1',
//             ),
//             array('nodeName' => 'elementKey',
//             'nodeAttr' => array('tracksid' => '789'),
//             'nodeValues' => 'elementVALUE2',
//             ),
//             array('nodeName' => 'elementKey',
//             'nodeAttr' => array('tracksid' => '789'),
//             'nodeValues' => 'elementVALUE3',
//             ),
//        )
// and you can mix those
//
// if the nodeValues will be a single array:
// eg: array('data')
// 'data' will be insert as element value
// eg: array('keyname'=>'data')
// 'keyname' will be ignored!!!
//
//
// Identifier:
// by default the values will be:
// * nodeName
// * nodeValues
// * nodeAttr
// if you want to change it eg. your data array should get other key-names,
// defaults: NN = nodeName , NV = nodeValues, NA = nodeAttr
// you can change it by calling:
// $obj->setIdentifier( array('NN'=>'nodeName','NA'=>'nodeAttr','NV'=>'nodeValues') );
// to:
// $obj->setIdentifier( array('NN'=>'myKEY','NA'=>'myAttr','NV'=>'myValues')


$arrayAttr = array('an_id'=>123,'a_name'=>'attributes Name');

$data = array(
    'nodeName' => 'myroot',
    'nodeAttr' => array(
        'name' => 'Array2Xml Creator',
        'copyright' => 'Copyright (C) 2005 by flobee'
    ),
    'nodeValues' => array(
        array(
            'nodeName' => 'music',
            'nodeAttr' => array('id' => 199, 'name' => 'FirstName'),
            'nodeValues' => array(
                // album 1
                array(
                    'nodeName' => 'album',
                    'nodeAttr' => array('albumid' => '1234',
                        'albumname' => 'The King'),
                    'nodeValues' => array(
                        array(
                            'nodeName' => 'tracks',
                            // 'nodeAttr'=>array('tracksid'=>'1234'),
                            'nodeValues' => array(
                                array('track' => '[elementVALUE1]', $arrayAttr),
                                array('track' => 'elementVALUE2', $arrayAttr),
                                array('track' => 'elementVALUE3', $arrayAttr),
                            ),
                        ),
                        array(
                            'nodeName' => 'information',
                            'nodeAttr' => array('albumid' => '1234'),
                            'nodeValues' => array(
                                array('artist' => 'an artist val', $arrayAttr),
                                array('autor' => 'an autor val', $arrayAttr),
                                array('lenght' => 'an lenght val', $arrayAttr),
                                array('size' => 'an size val', $arrayAttr)
                            ),
                        ),
                        array(
                            'nodeName' => 'test',
                            'nodeAttr' => array('test' => '1234'),
                            'nodeValues' => array(
                                // is element
                                array(
                                    'nodeName' => 'lastnode',
                                    'nodeAttr' => array('test' => '1234'),
                                    'nodeValues' => '[lastnode & value]',
                                ),
                                array(
                                    'nodeName' => 'lastnode2',
                                    'nodeAttr' => array('test2' => '1234'),
                                    'nodeValues' => 'lastnode2 value',
                                ),
                            ),
                        ),
                        array(
                            'nodeName' => 'nodefalsch',
                            'nodeAttr' => array('test' => '1234'),
                            'nodeValues' => array(
                                // is element
                                array(
                                    'nodeName' => 'nodefalsch',
                                    'nodeAttr' => false,
                                    'nodeValues' => 'falsch'
                                ),
                                array(
                                    'nodeName' => 'richtig',
                                    'nodeAttr' => array('id' => '0815'),
                                    'nodeValues' => array(
                                        array(
                                            'nodeName' => 'wichtig',
                                            'nodeAttr' => array('id' => '0815'),
                                            'nodeValues' => 'falsch',
                                        ),
                                        array('artist' => 'an artist val', $arrayAttr),
                                    ),
                                ),
                            ),
                        ),
                    ),
                )
            )
        )
    )
);
//test
//$options = array(
//    'encoding_from' => 'iso-8859-1',
//    'encoding_to' => 'iso-8859-1',
//    'cdata_escape' => true,
//    'debug' => true,
//    'data' => $data
//);
// $obj = new Mumsys_Array2Xml_Default($options);


// different identifier
//$data = array(
//    'NN' => 'myroot',
//    'AT' => array(
//        'name' => 'Array2Xml Creator',
//        'copyright' => 'Copyright (C) 2005 by flobee'
//    ),
//    'VV' => array(array('NN' => 'music',
//            'AT' => array('id' => 199, 'name' => 'FirstName'),
//            'VV' => array(array('NN' => 'tracks',
//                    'AT' => array('tracksid' => '1234'),
//                    'VV' => array(array('keyname' => 'data')),
//                ),
//                // test node with mixed value structue
//                array(
//                    'NN' => 'test',
//                    'AT' => array('test' => '1234'),
//                    'VV' => array(
//                        // is element
//                        array('NN' => 'lastnode',
//                            'AT' => array('test' => '1234'),
//                            'VV' => '[lastnode & value]'),
//                        array('track' => 'elementVALUE3', $arrayAttr),
//                    ),
//                ),
//            )
//        )
//    )
//);

// different identifier: using indexes
// warning: if using arrays for "nodeValues"
// eg: array('track'=>'elementVALUE3',$arrayAttr) may result in ERRORS or wrong result!!!
$data = array(
    0 => 'myroot',
    2 => array(
        'version' => Mumsys_Array2Xml_Default::VERSION,
        'name' => 'Array2Xml Creator',
        'copyright' => 'Copyright (C) 2005 by flobee'
    ),
    1 => array(
        array(
            0 => 'music',
            2 => array(
                'id' => 199,
                'name' => 'FirstName'
            ),
            1 => array(
                array(
                    0 => 'tracks',
                    2 => array('tracksid' => '1234'),
                    1 => array(array('KEYNAME' => 'data')),
                ),
                // test node with mixed value structue
                array(
                    0 => 'test',
                    2 => array('test' => '1234'),
                    1 => array(
                        // is element
                        array(
                            0 => 'lastnode',
                            2 => array('test' => '1234'),
                            1 => '[lastnode & value]',
                        ),
                        array(
                            0 => 'track',
                            1 => 'elementVALUE3',
                            2 => $arrayAttr
                        ),
                    ),
                ),
            )
        )
    )
);
// test
//$options = array(
//    'encoding_from' => 'iso-8859-1',
//    'encoding_to' => 'iso-8859-1',
//    'version' => Mumsys_Array2Xml_Default::VERSION,
//    'cdata_escape' => true,
//    'debug' => true,
//    'tag_case' => -1,
//    'spacer' => "\t",
//    'data' => $data
//);
//$obj = new Mumsys_Array2Xml_Default( $options );
//		 setIdentifier
//		 NN means nodeName
//		 NA means nodeAttr (Node attribute)
//		 NV means nodeValues (it can be a string or a place holder for children
//$obj->setIdentifier( array('NN' => 0, 'NV' => 1, 'NA' => 2) );
//$obj->echoXML();


//
// --- demo --------------------------------------------------------------------
//

// array in other view:
// All elements including root
$arr = array();
$arr['nodeName'] = 'root';
$arr['nodeAttr']['version'] = 'flobee v 0.1';
$arr['nodeAttr']['name'] = 'Array2Xml Creator';
// first main tree
$arr['nodeValues'][0]['nodeName'] = 'music';
$arr['nodeValues'][0]['nodeAttr']['id'] = 199;
$arr['nodeValues'][0]['nodeAttr']['name'] = 'FirstName';
$arr['nodeValues'][0]['nodeValues'][0]['nodeName'] = 'tracks';
$arr['nodeValues'][0]['nodeValues'][0]['nodeAttr']['tracksid'] = '1234';
//	value as string without attr (including attr see message above)
$arr['nodeValues'][0]['nodeValues'][0]['nodeValues'] = 'data';

$arr['nodeValues'][0]['nodeValues'][1]['nodeName'] = 'test';
$arr['nodeValues'][0]['nodeValues'][1]['nodeAttr']['test'] = '1234';
$arr['nodeValues'][0]['nodeValues'][1]['nodeValues'][0]['nodeName'] = 'lastnode'; // is element name
$arr['nodeValues'][0]['nodeValues'][1]['nodeValues'][0]['nodeAttr']['test'] = '1234';
$arr['nodeValues'][0]['nodeValues'][1]['nodeValues'][0]['nodeValues'] = '[lastnode amp:&amp; und: & 38: &#38; quo:\' "]';
$arr['nodeValues'][0]['nodeValues'][1]['nodeValues'][1]['nodeName'] = 'track'; // is element name
$arr['nodeValues'][0]['nodeValues'][1]['nodeValues'][1]['nodeValues'] = 'elementVALUE3';
$arr['nodeValues'][0]['nodeValues'][1]['nodeValues'][1]['nodeAttr']['test'] = '1234';
// ...
// ...

// second main tree
$arr['nodeValues'][1]['nodeName'] = 'music_two';
$arr['nodeValues'][1]['nodeAttr']['id'] = 199;
$arr['nodeValues'][1]['nodeAttr']['name'] = 'FirstName';
$arr['nodeValues'][1]['nodeValues'] = 'Media value';
// ...
// ...

// test
// $options = array('encoding_from'=>'iso-8859-1','encoding_to'=>'iso-8859-1','version'=>'1.0','cdata_escape'=>true,'debug'=>true,'tag_case'=>-1,'spacer'=>"\t", 'data'=>$arr);
// $obj = new Array2Xml($options);
// $obj->echoXML();


$options = array(
    'encoding_from' => 'iso-8859-1',
    'encoding_to' => 'iso-8859-1',
    'cdata_escape' => true,
    'debug' => true,
    'tag_case' => -1,
    'spacer' => "\t"
);
$obj = new Mumsys_Array2Xml_Default( $options );

$obj->setData( $arr['nodeValues'] );

$opts = array();
$opts['nodeName'] = 'root';
$opts['nodeAttr']['name'] = 'Array2Xml Creator';
//$opts['nodeValues'] = array();

$obj->setRoot( $opts );



$obj->echoXML();
