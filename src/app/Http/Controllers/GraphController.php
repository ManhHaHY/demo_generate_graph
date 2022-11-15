<?php

namespace App\Http\Controllers;

use App\Http\Requests\GraphGenerateRequest;
use App\Interfaces\GraphInterface;
use App\Interfaces\WikiUrlInterface;
use Goutte\Client;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class GraphController extends Controller
{
    public function __construct(
        protected GraphInterface   $graph,
        protected WikiUrlInterface $wikiUrl
    )
    {
    }

    /**
     * @return View
     */
    public function index(): View
    {
        return view('graph.index');
    }

    /**
     * @param GraphGenerateRequest $request
     * @return mixed
     * @throws ValidationException
     */
    public function generate(GraphGenerateRequest $request)
    {
        $data = $request->all();

        if ($this->wikiUrl->isWikiUrl($data['wiki_url'])) {
            $client = new Client();
            $crawler = $client->request('GET', $data['wiki_url']);
            $title = $crawler->filter('h1.firstHeading')->text();
            $chartData = $this->graph->getDataFromUrl($data['wiki_url']);

            if (count($chartData) > 0) {
                $this->graph->generateChart($title, $chartData);
                $fileName = str()->slug($title) . '-chart.png';

                return response()->download(public_path('pictures/' . $fileName));
            }
        }

        throw ValidationException::withMessages([
            'wiki_url' => 'Your url insert is not wiki url.',
        ]);
    }
}
