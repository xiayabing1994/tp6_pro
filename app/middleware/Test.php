<?php
namespace app\middleware;

class Test{

	 public function handle($request, \Closure $next)
    {
        echo 'testmiddleware';

        return $next($request);
    }
}