sf34
====

A Symfony project created on February 22, 2018, 10:38 am.


### Steps:

1) Install docker in your laptop

2) create .env from .env.sample and personalize it

3) start containers ```docker-compose up -d --build```

4) check if containers are running ```docker-compose ps```

5) ssh into container ```docker-compose exec php_fpm /bin/bash``` and run composer install

6) look result in browser, go to [http://localhost:8018/app_dev.php](http://localhost:8018/app_dev.php)  you should see debug bar with a gauge icon, click on it and you can see a cool graph


### data logger

Example is in DefaultController::ajaxAction

```php

    /**
     * @Route("/ajax", name="ajax")
     */
    public function ajaxAction(Request $request)
    {
        $channel = 'AJAX';
        $seconds = $request->get('seconds', 1);
        if ($index = intval($request->get('channel'))) {
            $channel .= '-' . $index;
        }

        /** @var ProfileLoggerInterface $profileLogger */
        $profileLogger = $this->container->get('profile_logger');

        $profileLogger->clean($channel);

        $profileLogger->logStartTime($channel);

        // simulate some calculations

        sleep(1);

        $profileLogger->logString($channel,'fetching data form DB');
        $profileLogger->incAndLogTime($channel);

        sleep(1);

        $profileLogger->logString($channel,'resolving dependencies');
        $profileLogger->incAndLogTime($channel);

        sleep($seconds);

        $profileLogger->logEndTime($channel);

        return new JsonResponse([
            'index' => $index,
            'result' => 'OK',
            'seconds' => $seconds,
        ]);
    }
```

### NOTE:
magic is done in app/config/services.yml


## references:

* https://symfony.com/doc/3.4/profiler.html
* https://github.com/ludofleury/GuzzleBundle/blob/master/Resources/views/Collector/guzzle.html.twig
