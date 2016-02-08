<?




require_once __DIR__.'/vendor/symfony/class-loader/ClassLoader.php';


use Symfony\Component\ClassLoader\ClassLoader;

use Zs\Model\ClientDigitalLibrary;
use Zs\Model\EprintsAdapter;
use Zs\Model\Eprints;
$loader = new ClassLoader();
$loader->setUseIncludePath(true);
$loader->register();

//var_dump($loader);


new ClientDigitalLibrary();

//$twitter = new twitterAdapter(new Twitter());
//$twitter->send('Posting to Twitter');

$eprints= new EprintsAdapter (new Eprints());


var_dump($eprints);

$eprints->LoadMetadata();
