<?php
    
    namespace App\Shared\Controller;
    
    use App\Domain\Budget\Manager\BudgetManager;
    use App\Domain\Budget\Model\Search\BudgetSearchCommand;
    use App\Domain\Entry\Manager\EntryManager;
    use DateTimeImmutable;
    use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
    use Symfony\Component\HttpFoundation\Request;
    use Symfony\Component\HttpFoundation\Response;
    use Symfony\Component\Routing\Annotation\Route;

    class HomeController extends AbstractController
    {
        #[Route('/', methods: Request::METHOD_GET)]
        public function home(
           EntryManager $entryManager
        ): Response
        {
            return $this->render('home.html.twig', [
                'entryBalance' => $entryManager->balance()
            ]);
        }
    }
