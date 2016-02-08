<?php
namespace Zs\Model;

class Eprints extends IObject
{


    public $id;
    public $fileds;
    public $key;
    public $title;
    public $creators_name;
    public $date;
    public $abstract;
    public $url_file;
    public $type;
    public $data_range = "1980-";
    public $order = "-date";


    public function LoadMetadata()
    {


        $this->fileds = array('fileds' => 'title');
        $this->key = 'test';
//data range in form yyyy- or -yyyy or yyyy-zzzz
        $this->data_range = '2000-2016';
$result_search = $this->search();

        var_dump($result_search);


    }


    private function search(){

        /* TODO create dynamic link for wsdl file
         */
//        $client = new SoapClient($this->ePrintsHost."/wsdl/SearchServ.wsdl", array("trace" => 1, "exception" => 0, 'cache_wsdl' => WSDL_CACHE_NONE));
//        new \SoapClient();

        try {
            $client = new LocalSoapClient(__DIR__ . "/lib.iita.gov.ua/SearchServ.wsdl",
                array("trace" => 1, "exception" => 0, 'cache_wsdl' => WSDL_CACHE_NONE));



//        $client = new SoapClient("SearchServ2.wsdl",
//            array("trace" => 1, "exception" => 0, 'cache_wsdl' => WSDL_CACHE_NONE));

            if (isset($this->data_range)) {
                $result = $client->searchEprint($this->key, $this->fileds, $this->data_range, $this->order);


            } else {
                $result = $client->searchEprint($this->key, $this->fileds, $this->order);
            }
//            var_dump($client->__getLastRequest());
//            var_dump($client->__getLastResponse());
//            var_dump($client->__getLastRequest());
        }
        catch(SoapFault $e){
            var_dump($e);
//            var_dump($client->__getLastRequest());
//            var_dump($client->__getLastResponse());

        }
        //debug
//        var_dump($client->__getLastResponse());
        die;
        return $result;
    }


}



class LocalSoapClient extends \SoapClient {

    function __construct($wsdl, $options) {
        parent::__construct($wsdl, $options);

    }

    function __doRequest($request, $location, $action, $version, $one_way = 0) {


        $request=preg_replace('/SOAP-ENV:Envelope/', 'soapenv:Envelope', $request);
        $request=preg_replace('/xmlns:SOAP-ENV/', 'xmlns:soapenv', $request);
        $request=preg_replace('/SOAP-ENV:Body/', 'soapenv:Body', $request);
        $request=preg_replace('/xmlns:SOAP-ENC/', 'soapenv:Body', $request);

        var_dump($request);
        die;
        $ret = parent::__doRequest($request, $location, $action, $version);
//        ob_start();
//      echo  $request;
//        $response = ob_get_contents();
//        ob_end_clean();
        return $ret;
    }

}

class EpClient
{

    public $id;
    public $fileds;
    public $key;
    public $title;
    public $creators_name;
    public $date;
    public $abstract;
    public $url_file;
    public $type;
    public $data_range = "1980-";
    public $order = "-date";


    private $ePrintsHost = 'http://eprints.zu.edu.ua';


    public function search()
    {
//        $client = new SoapClient($this->ePrintsHost."/wsdl/SearchServ.wsdl", array("trace" => 1, "exception" => 0, 'cache_wsdl' => WSDL_CACHE_NONE));
        $client = new SoapClient("SearchServ2.wsdl",
            array("trace" => 1, "exception" => 0, 'cache_wsdl' => WSDL_CACHE_NONE));

        if (isset($this->data_range)) {
            $result = $client->searchEprint($this->key, $this->fileds, $this->data_range, $this->order);
        } else {
            $result = $client->searchEprint($this->key, $this->fileds, $this->order);
        }
        //debug
        //var_dump($client->__getLastRequest());
        return $result;
    }


    public function getMetadata()
    {
        $client = new SoapClient($this->ePrintsHost . "/wsdl/MetaDataServ.wsdl",
            array("trace" => 1, "exception" => 0, 'cache_wsdl' => WSDL_CACHE_NONE));


        $ObjectXML = '<listId>';
        foreach ($this->id as $id) {
            $ObjectXML .= '<item>' . $id . '</item>';
        }
        $ObjectXML .= '</listId>';
        $ItemObject = new SoapVar($ObjectXML, XSD_ANYXML);

        $result = $client->getEprint($ItemObject);
//        var_dump($client->__getLastRequest());
        return $result;
    }


    public function put()
    {

        $ObjectXML = '<creators_name>';
        foreach ($this->creators_name as $creators) {
            $ObjectXML .= '<item>
                        <given xsi:type="xsd:string">' . $creators['given'] . '</given>
                <family xsi:type="xsd:string">' . $creators['family'] . '</family>
            </item>';
        }
        $ObjectXML .= '</creators_name>';

        $ItemObject = new SoapVar($ObjectXML, XSD_ANYXML);
        $client = new SoapClient($this->ePrintsHost . "/wsdl/putEprints_string.wsdl",
            array("trace" => 1, "exception" => 0, 'cache_wsdl' => WSDL_CACHE_NONE));
        //$client = new SoapClient("putEprints2_string.wsdl", array("trace" => 1, "exception" => 0, 'cache_wsdl' => WSDL_CACHE_NONE));
        $result = $client->putEprint($this->title, $ItemObject, $this->date, $this->abstract, $this->url_file,
            $this->type);
        var_dump($client->__getLastRequest());
        return $result;
    }


}
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

