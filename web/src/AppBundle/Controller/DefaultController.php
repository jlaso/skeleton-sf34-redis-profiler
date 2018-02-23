<?php

namespace AppBundle\Controller;

use AppBundle\Services\Performance\ProfileLoggerInterface;
use AppBundle\Services\Performance\RedisProfileLoggerService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends Controller
{
    use ContainerAwareTrait;

    /**
     * @Route("/", name="homepage")
     */
    public function indexAction(Request $request)
    {
        // replace this example code with whatever you need
        return $this->render('default/index.html.twig', [
            'base_dir' => realpath($this->getParameter('kernel.project_dir')).DIRECTORY_SEPARATOR,
        ]);
    }


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
}
