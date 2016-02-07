<?
echo 2;


require_once __DIR__.'/vendor/symfony/class-loader/ClassLoader.php';


use Symfony\Component\ClassLoader\ClassLoader;

use Zs\Model\ClientDigitalLibrary;
$loader = new ClassLoader();
$loader->setUseIncludePath(true);
$loader->register();

//var_dump($loader);


new ClientDigitalLibrary();

