<?php

namespace App\Repositories;

use App\Interfaces\GraphInterface;
use App\Models\Graph;
use Carbon\Carbon;
use CpChart\Data;
use CpChart\Image;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;

class GraphRepository extends Graph implements GraphInterface
{
    /**
     * @param string $url
     * @return array
     */
    public function getDataFromUrl(string $url): array
    {
        $client = new Client();
        $crawler = $client->request('GET', $url);
        // Regex find data for parse numeric and type of it
        $regex = '/(\d+\.?\d*).?(k?m|cm|mm|k?g|mg|lbs|oz)?/m';

        $headers = $this->getHeaders($crawler);

        $tableData = [];

        for ($i = 1; $i <= count($headers); $i++) {
            $crawler->filter('table.wikitable')->first()
                ->filter('td:nth-child(' . $i . ')')->each(
                    function (Crawler $node) use ($regex, $i, $headers, &$tableData) {
                        $data = json_decode(preg_replace(
                                '/\\\\u00a0/',
                                ' ',
                                json_encode($node->text()))
                        );
                        if (
                            $i === 1
                            && preg_match($regex, $data, $matches, PREG_OFFSET_CAPTURE)
                            && strtolower($headers[0]) === 'mark'
                        ) {
                            $tableData[0][] = $matches[1][0] ?? 0;
                        } elseif ($i === 1 && strtolower($headers[0]) === 'time' &&
                            preg_match_all(
                                '/(\d)(:)(\d{0,2})(.)(\d)/m',
                                $data,
                                $matches,
                                PREG_SET_ORDER,
                                0
                            )
                        ) {
                            $timeMilliseconds = $matches[0][1] + ($matches[0][3] / 60) + ($matches[0][5] / 60000);
                            $tableData[0][] = $timeMilliseconds;
                        } elseif ($i === 1 && strtolower($headers[0]) === 'time' &&
                            preg_match(
                                '/\d{0,2}.\d/m',
                                $data
                            )
                        ) {
                            $tableData[0][] = $node->text();
                        } elseif (array_search('Date', $headers) + 1 === $i) {
                            $tableData[1][] = $node->text();
                        }
                        $tableData['type'] = $headers[0];
                    }
                );
        }

        return $tableData;
    }

    /**
     * @param string $title
     * @param array $data
     * @return string
     * @throws \Exception
     */
    public function generateChart(string $title, array $data): string
    {
        $type = $data['type'];

        /* Create and populate the pData object */
        $chartData = new Data();
        $chartData->addPoints($data[0], $title);
        $chartData->setAxisName(0, $type);
        $chartData->addPoints($data[1], $type);
        $chartData->setSerieDescription($type, $type);
        $chartData->setAbscissa($type);

        /* Create the pChart object */
        $myPicture = new Image(1920, 1080, $chartData);

        /* Turn of Antialiasing */
        $myPicture->Antialias = FALSE;

        /* Add a border to the picture */
        $myPicture->drawRectangle(0, 0, 1919, 1079, ["R" => 0, "G" => 0, "B" => 0]);

        /* Set the default font */
        $myPicture->setFontProperties(["FontName" => public_path('fonts/pf_arma_five.ttf'), "FontSize" => 14]);

        /* Define the chart area */
        $myPicture->setGraphArea(60, 40, 1870, 980);

        /* Draw the scale */
        $scaleSettings = [
            "GridR" => 200,
            "GridG" => 200,
            "GridB" => 200,
            "DrawSubTicks" => TRUE,
            "CycleBackground" => TRUE
        ];
        $myPicture->drawScale($scaleSettings);

        /* Write the chart legend */
        $myPicture->drawLegend(580, 12, ["Style" => LEGEND_NOBORDER, "Mode" => LEGEND_HORIZONTAL]);

        /* Turn on shadow computing */
        $myPicture->setShadow(TRUE, ["X" => 1, "Y" => 1, "R" => 0, "G" => 0, "B" => 0, "Alpha" => 10]);

        /* Draw the chart */
        $myPicture->setShadow(TRUE, ["X" => 1, "Y" => 1, "R" => 0, "G" => 0, "B" => 0, "Alpha" => 10]);
        $settings = [
            "Gradient" => TRUE,
            "GradientMode" => GRADIENT_EFFECT_CAN,
            "DisplayPos" => LABEL_POS_INSIDE,
            "DisplayValues" => TRUE,
            "DisplayR" => 255,
            "DisplayG" => 255,
            "DisplayB" => 255,
            "DisplayShadow" => TRUE,
            "Surrounding" => 10
        ];
        $myPicture->drawBarChart($settings);

        /* Render the picture (choose the best way) */
        ob_start();
        $myPicture->autoOutput("pictures/$title-chart.png");
        $image = ob_get_contents();
        ob_end_clean();
        $fileName = str()->slug($title);
        $file = fopen(public_path("pictures/$fileName-chart.png"), 'wb');
        fputs($file, $image);
        fclose($file);

        return $fileName;
    }

    /**
     * @param Crawler $crawler
     * @return array
     */
    public function getHeaders(Crawler $crawler): array
    {
        $headers = [];
        $crawler->filter('table.wikitable th')->each(function (Crawler $node) use (&$headers) {
            $headers[] = $node->text();
        });

        return $headers;
    }
}
