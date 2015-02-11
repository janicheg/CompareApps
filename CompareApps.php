<?php
libxml_use_internal_errors(true);
class CompareApps
{
    protected $links = [];
    protected $itunes_descriptions = [];
    protected $google_descriptions = [];
    protected $google_links = [];
    protected $itunes_links = [];

    /**
     * Compare array of links and return array pairs of links
     *
     * @param array $links
     * @return array
     */
    public function compare(Array $links)
    {
        $clones = [];
        $this->links = $links;
        $this->getDescriptions();

        foreach ($this->itunes_descriptions as $itunes_key => $itunes) {
            foreach ($this->google_descriptions as $google_key => $google) {
                // it's not so fast, because O(max(n,m)**3 iterations
                similar_text($itunes, $google, $percent);
                if ($percent > 20) {
                    $clones[] = [
                        'links' => [
                            'itunes' => $this->itunes_links[$itunes_key],
                            'google' => $this->google_links[$google_key]],
                        'percent' => $percent
                    ];
                }
            }
        }
        return $clones;
    }

    /**
     * Sets for each key in links array description
     */
    protected function getDescriptions()
    {
        foreach ($this->links as $key => $link) {
            if (strlen($link) > 10) {
                $this->getDescription($link);
            }
        }
    }

    /**
     * Detect market source and getting apps description
     *
     * @param $link
     * @return null|string
     */
    public function getDescription($link)
    {
        $description = null;
        $link_array = parse_url($link);
        switch ($link_array['host']) {
            case 'itunes.apple.com':
                $this->itunes_links[] = $link;
                $this->itunes_descriptions[] = $this->getItunesDescription($link);
                break;
            case 'play.google.com':
                $this->google_links[] = $link;
                $this->google_descriptions[] = $this->getGoogleDescription($link);
                break;
        }
    }

    /**
     * Get apps description from itunes market
     *
     * @param $link
     * @return string
     */
    private function getItunesDescription($link)
    {
        return $this->parseHtml($link, './/div[@class="product-review"][@metrics-loc="Titledbox_Описание"]');
    }

    /**
     * Get apps description from itunes market
     *
     * @param $link
     * @return string
     */
    private function getGoogleDescription($link)
    {
        return $this->parseHtml($link, './/div[@class="id-app-orig-desc"]');
    }

    /**
     * Parsing html and by pattern an getting description for app
     *
     * @param $link
     * @param $xpath_pattern
     * @throws Exception
     */
    protected function parseHtml($link, $xpath_pattern)
    {
        try {
            $doc = new DOMDocument();
            $description = '';
            $html = file_get_contents($link);
            $doc->loadHTML($html);
            $xpath = new DOMXPath($doc);
            $items = $xpath->query($xpath_pattern);
            foreach ($items as $item) {
                $description .= $item->nodeValue;
            }
            return mb_convert_encoding($description, 'UTF-8');
        } catch (Exception $e) {
            throw new Exception('Cannot parse url: ' . $link);
        }
    }
}
