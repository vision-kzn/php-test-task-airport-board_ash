<?php

namespace App\Command;

use App\Entity\Airport;
use App\Entity\Flight;
use App\Repository\FlightRepository;
use App\Service\DurationHumanFormatter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class ShowAirportBoardCommand extends Command
{
    protected static $defaultName = 'app:show-airport-board';

    private SymfonyStyle $io;
    private OutputInterface $output;

    private FlightRepository $flightRepository;

    public function __construct(FlightRepository $flightRepository)
    {
        parent::__construct();

        $this->flightRepository = $flightRepository;
    }

    protected function configure(): void
    {
        $this->setDescription('Show airport board with flight and total information.');
    }

    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        parent::initialize($input, $output);
        $this->io = new SymfonyStyle($input, $output);
        $this->output = $output;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->showMessageAboutDocker();

        $flights = $this->flightRepository->getAll();

        $this->showTotalInformation($flights);
        $this->showFlightInformation($flights);

        return 0;
    }

    private function showMessageAboutDocker(): void
    {
        $message = $_ENV['MESSAGE'] ?? null;
        if($message) {
            $this->io->success($message);
        }
    }

    /**
     * @param Flight[] $flights
     * @return void
     */
    private function showTotalInformation(array $flights): void
    {
        if(!count($flights)) {
            return;
        }

        $totalDuration = array_sum(
            array_map(
                function(Flight $flight): int {
                    return $flight->calculateDurationMinutes();
                },
                $flights
            )
        );

        $avgDuration = round($totalDuration / count($flights));

        $this->io->info(
            sprintf(
                'Avg. flight duration: %s.',
                new DurationHumanFormatter($avgDuration)
            )
        );
    }

    /**
     * @param Flight[] $flights
     * @return void
     */
    private function showFlightInformation(array $flights): void
    {
        $rowIndex = 1;
        $data = array_map(
            function(Flight $flight) use (&$rowIndex): array {
                return [
                    '#' => $rowIndex++,
                    'from' => $this->buildAirportTitle($flight->getFromAirport()),
                    'from time' => $flight->getFromTime(),
                    'to' => $this->buildAirportTitle($flight->getToAirport()),
                    'to time' => $flight->getToTime(),
                    'duration' => new DurationHumanFormatter($flight->calculateDurationMinutes()),
                ];
            },
            $flights
        );

        $this->showDataAsTable($data);
    }

    private function buildAirportTitle(Airport $airport): string
    {
        return sprintf(
            '%s (%s, %s, %s)',
            $airport->getCity(),
            $airport->getName(),
            $airport->getCode(),
            $airport->getTimeZone()
        );
    }

    private function showDataAsTable(array $data): void
    {
        if(!count($data)) {
            return;
        }

        $table = new Table($this->output);
        $table
            ->setHeaders(array_keys(current($data)))
            ->setRows($data)
        ;
        $table->render();
    }
}