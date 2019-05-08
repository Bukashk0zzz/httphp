<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Person;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class DefaultController
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {

        $this->entityManager = $entityManager;
    }
    /**
     * @Route(methods={"GET", "POST"}, name="default_route")
     */
    public function get(Request $request)
    {
        $content = $request->getContent() . '<br/> --- <br/>';
        $persons = $this->entityManager->getRepository(Person::class)->findRandomPersons();

        foreach ($persons as $person) {
            /** @var Person $person */
            $content .= sprintf("%s %s - %s<br>", $person->getFirstName(), $person->getLastName(), $person->getEmail());
        }

        $content .= '<br>';
        $content .= memory_get_peak_usage(true) / 1024 / 1024 . 'MB';


        $response = new Response($content);

        return $response;
    }
}
