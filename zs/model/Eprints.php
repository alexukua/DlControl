<?php
namespace Zs\Model;

class Eprints extends IObject
{

    /**
     * @var array ID
     */
    public $id;
    /**
     * @var array
     */
    public $fileds;
    public $key;
    public $title;
    /**
     * Type of eprints
     * Allowed value inbox, buffer, archive, deletion
     * @var string
     */
    public $typeIo;
    public $creators_name;
    public $date;
    public $abstract;
    public $url_file;

    public $data_range = "1980-";
    public $order = "-date";

    public $debug = false;

    public function __construct()
    {
        $this->debug = true;
    }


    public function LoadMetadata()
    {


        $this->fileds = array('fileds' => 'title');
        $this->key = '*';
//data range in form yyyy- or -yyyy or yyyy-zzzz
        $this->data_range = '2000-2016';

        $this->typeIo = 'buffer';
        $ids = $this->search();

        $result_search = $this->getMetadata($ids);
        var_dump($result_search);


    }


    private function search()
    {

        /* TODO create dynamic link for wsdl file
         */
//        $client = new SoapClient($this->ePrintsHost."/wsdl/SearchServ.wsdl", array("trace" => 1, "exception" => 0, 'cache_wsdl' => WSDL_CACHE_NONE));
//        new \SoapClient();

        try {
            ini_set('soap.wsdl_cache_enable', 0);
            ini_set('soap.wsdl_cache_ttl', 0);
            $client = new LocalSoapClient("http://192.168.99.100/wsdl/lib.iita.gov.ua/SearchServ.wsdl",
                array("trace" => 1, "exception" => 0, 'cache_wsdl' => WSDL_CACHE_NONE));

            if (isset($this->data_range)) {
                $result = $client->searchEprint($this->key, $this->fileds, $this->data_range, $this->order,
                    $this->typeIo);

            } else {
                $result = $client->searchEprint($this->key, $this->fileds, $this->order);
            }
//            var_dump($client->__getLastRequest());
//            var_dump($client->__getLastResponse());
//            var_dump($client->__getLastRequest());
        } catch (SoapFault $e) {
            var_dump($e);


        }
        //debug
//        var_dump($client->__getLastResponse());

        return $result;
    }


    public function getMetadata($ids)
    {
        ini_set('soap.wsdl_cache_enable', 0);
        ini_set('soap.wsdl_cache_ttl', 0);
        $options = array(
            'classmap' => array(
                'campaign' => 'MY_Campaign',
            )
        );
        $client = new LocalSoapClient("http://192.168.99.100/wsdl/lib.iita.gov.ua/MetaDataServ2.wsdl",
            array(
                "trace" => 1,
                "exception" => 0,
                'cache_wsdl' => WSDL_CACHE_NONE,

            ));


        $ObjectXML = '<listId>';
        foreach ($ids as $id) {
            $ObjectXML .= '<item>' . $id . '</item>';
        }
        $ObjectXML .= '</listId>';
        $ItemObject = new \SoapVar($ObjectXML, XSD_ANYXML);

        $result = $client->getEprint($ItemObject);
//        var_dump($client->__getLastRequest());


       // var_dump($client->__getLastResponse());

        $obj = simplexml_load_string($client->__getLastResponse());
//var_dump($result);

        $xml=$obj->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->children('urn:MetaDataServ');

var_dump($xml);

foreach ($xml->getEprintResponse->items->ResourcesList as $item)
{
//    var_dump($item->TitleList->someArray->item);
    foreach($item->TitleList->someArray->item as $title){
        var_dump( base64_decode($title));
        var_dump( $title->attributes()->lang);

}
}


     //   var_dump($xml->getEprintResponse->items->ResourcesList->TitleList);
//        var_dump($xml);

//        foreach($obj->children('http://schemas.xmlsoap.org/soap/envelope/')->Body->children('urn:MetaDataServ')->items->ResourcesList as $rate)
//        {
//          var_dump($rate);
//        }
//        var_dump($result->ArrayOfItems());
        //var_dump($result->ResourcesList[0]->TitleList);
        die('eeeeeeeee');
        return $result;
    }


}



class LocalSoapClient extends \SoapClient
{

    function __construct($wsdl, $options)
    {
        parent::__construct($wsdl, $options);

    }

    function __doRequest($request, $location, $action, $version, $one_way = 0)
    {
//        $request=preg_replace('/SOAP-ENV:Envelope/', 'soapenv:Envelope', $request);

        //   if ($this->debug) {
        var_dump($request);

        // }
        /**
         * delete BOM from request
         */


        $xml = explode("\r\n", parent::__doRequest($request, $location, $action, $version));

        var_dump($xml);
        $response = preg_replace('/^(\x00\x00\xFE\xFF|\xFF\xFE\x00\x00|\xFE\xFF|\xFF\xFE|\xEF\xBB\xBF)/', "", $xml[5]);
        return $response;
    }

}

//class EpClient
//{
//
//    public $id;
//    public $fileds;
//    public $key;
//    public $title;
//    public $creators_name;
//    public $date;
//    public $abstract;
//    public $url_file;
//    public $type;
//    public $data_range = "1980-";
//    public $order = "-date";
//
//
//    private $ePrintsHost = 'http://eprints.zu.edu.ua';
//
//
//    public function search()
//    {
////        $client = new SoapClient($this->ePrintsHost."/wsdl/SearchServ.wsdl", array("trace" => 1, "exception" => 0, 'cache_wsdl' => WSDL_CACHE_NONE));
//        $client = new SoapClient("SearchServ2.wsdl",
//            array("trace" => 1, "exception" => 0, 'cache_wsdl' => WSDL_CACHE_NONE));
//
//        if (isset($this->data_range)) {
//            $result = $client->searchEprint($this->key, $this->fileds, $this->data_range, $this->order);
//        } else {
//            $result = $client->searchEprint($this->key, $this->fileds, $this->order);
//        }
//        //debug
//        //var_dump($client->__getLastRequest());
//        return $result;
//    }
//
//
//    public function getMetadata()
//    {
//        $client = new SoapClient($this->ePrintsHost . "/wsdl/MetaDataServ.wsdl",
//            array("trace" => 1, "exception" => 0, 'cache_wsdl' => WSDL_CACHE_NONE));
//
//
//        $ObjectXML = '<listId>';
//        foreach ($this->id as $id) {
//            $ObjectXML .= '<item>' . $id . '</item>';
//        }
//        $ObjectXML .= '</listId>';
//        $ItemObject = new SoapVar($ObjectXML, XSD_ANYXML);
//
//        $result = $client->getEprint($ItemObject);
////        var_dump($client->__getLastRequest());
//        return $result;
//    }
//
//
//    public function put()
//    {
//
//        $ObjectXML = '<creators_name>';
//        foreach ($this->creators_name as $creators) {
//            $ObjectXML .= '<item>
//                        <given xsi:type="xsd:string">' . $creators['given'] . '</given>
//                <family xsi:type="xsd:string">' . $creators['family'] . '</family>
//            </item>';
//        }
//        $ObjectXML .= '</creators_name>';
//
//        $ItemObject = new SoapVar($ObjectXML, XSD_ANYXML);
//        $client = new SoapClient($this->ePrintsHost . "/wsdl/putEprints_string.wsdl",
//            array("trace" => 1, "exception" => 0, 'cache_wsdl' => WSDL_CACHE_NONE));
//        //$client = new SoapClient("putEprints2_string.wsdl", array("trace" => 1, "exception" => 0, 'cache_wsdl' => WSDL_CACHE_NONE));
//        $result = $client->putEprint($this->title, $ItemObject, $this->date, $this->abstract, $this->url_file,
//            $this->type);
//        var_dump($client->__getLastRequest());
//        return $result;
//    }
//
//
//}
//
//
//$search = new EpClient();
//
////  search data, return list id
//
//$search->fileds = array('fileds' => 'title');
//$search->key = 'test';
////data range in form yyyy- or -yyyy or yyyy-zzzz
////$search->data_range = '2009-2010';
//$result_search = $search->search();
//
//
//$search->fileds = array('fileds' => 'title');
//$search->key = 'and';
//$result_search1 = $search->search();
//
////search by author
//$search2->fileds = array('fileds' => 'author');
//$search2->creators_name = array(array('family' => 'Gajos'));
//$result_search2 = $search->search();
//
////search by year
//$search3->fileds = array('fileds' => 'date');
//$search3->data_range = '2012-';
//$result_search3 = $search->search();
//
//var_dump($result_search);
//
//
//// get medatada by id item, return list metadata
//
////
////$search4->id = $result_search; //array(115,116,118);
////$result_metadata = $search4->getMetadata();
//
//
//// put metadata
//
////$search->title = 'Test soap client php';
////$search->abstract = 'testing soap client php';
////$search->creators_name = array(array('family' => 'test family1', 'given' =>'test given1'), array('family' => 'test family2', 'given' =>'test given2'));
////$search->date = '2012-09-30';
////$search->type = 'article';
////$search->url_file = '/var/moodledata233/filedir/01/a5/01a59d0e6774ef7b83dc18da2d97db535f252f46';
////$result_put = $search->put();
//
//var_dump($result_search);
//var_dump($result_metadata);
//var_dump($result_put);
//

