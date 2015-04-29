<?php



namespace Myfc\Mail\Instance;
use Myfc\Mail\Collection;

/**
 *
 * @author vahitşerif
 */
interface MailDriverInstance {
    
    public function getName();
    public function boot();
    public function set(Collection $collection);
    
}
