<?php

$scrap_url = 'https://easytoenroll.ymcadc.org/register/easytoenroll/branches/branches?nsSubmit=Y&Submit=Y&chkAdvancedSearch=Y';

$scripts = [
  'https://easytoenroll.ymcadc.org/register/common/js/jquery.min.js',
  'https://easytoenroll.ymcadc.org/register/common/js/Common.js',
  // 'https://easytoenroll.ymcadc.org/register/common/js/Ajax.js',
  'https://easytoenroll.ymcadc.org/register/common/js/calendar.js',
  'https://easytoenroll.ymcadc.org/register/common/js/calendar-en.js',
  'https://easytoenroll.ymcadc.org/register/common/js/calendar-setup.js',
  'script.js',
];

$styles = [
  'https://easytoenroll.ymcadc.org/register/common/css/blue-style.css',
  'https://easytoenroll.ymcadc.org/register/common/css/calendar-system.css',
];


$page = new easytoenroll($scrap_url, $scripts, $styles);

print $page->buildPage();

/**
 * A quick class to scrap and build form for easy to enroll
 */
class easytoenroll {
  private $scrap_url;
  private $dom; // The loaded DOM of the scraping page
  private $scripts = []; // URL of the javascripts needed
  private $styles = [];

  public function __construct($scrap_url, $scripts, $styles) {
    $this->scrap_url = $scrap_url;
    $this->scripts = $scripts;
    $this->styles = $styles;
    $this->dom = $this->getDom();
  }

  /**
   * Building our own HTML page based on scraped pieces
   **/
  public function buildPage() {
    $doc = new DOMDocument();
    $html = $doc->appendChild($doc->createElement('html'));
    $head = $html->appendChild($doc->createElement('head'));

    // Adding the javascripts
    $value = "function UnpdateSecCat(meh) {}";
    $head->appendChild($doc->createElement('script', $value));

    if (!empty($this->scripts)) {
      foreach ($this->scripts as $script_src) {
        $node = $head->appendChild($doc->createElement('script'));
        $node->setAttribute('type', 'text/javascript');
        $node->setAttribute('src', $script_src);
      }
    }

    // Adding styles
    if (!empty($this->styles)) {
      foreach ($this->styles as $style_href) {
        $node = $head->appendChild($doc->createElement('link'));
        $node->setAttribute('type', 'text/css');
        $node->setAttribute('rel', 'stylesheet');
        $node->setAttribute('media', 'screen');
        $node->setAttribute('href', $style_href);
      }
    }

    // Create metatags
    $meta = [
      ['charset' => 'utf-8'],
    ];
    foreach ($meta as $attributes) {
        $node = $head->appendChild($doc->createElement('meta'));
        foreach ($attributes as $key => $value) {
            $node->setAttribute($key, $value);
        }
    }

    // Working on the body section
    $body = $html->appendChild($doc->createElement('body'));
    $body->setAttribute('id', 'autclear');

    $body->appendChild($doc->createElement('form'));

    $body->appendChild($doc->createElement('h3', 'Copy + Paste this link'));

    // Creating the textfield for the URL Built
    $url_builder = $body->appendChild($doc->createElement('input'));
    $url_builder->setAttribute('type', 'text');
    $url_builder->setAttribute('id', 'url_builder');
    $url_builder->setAttribute('size', '150');

    $body->appendChild($doc->createElement('h3', 'Preview'));

    $iframe = $body->appendChild($doc->createElement('div'));
    $iframe->setAttribute('id', 'frame_container');


    $doc->formatOutput = TRUE;
    $html_template = $doc->saveHTML();

    // Find and Replace
    $form = $this->getForm();
    $html = str_replace('</form>', '', $html_template);
    $html = str_replace('<form>', $form, $html);
    $html = str_replace('/register/common/images', 'https://easytoenroll.ymcadc.org/register/common/images', $html);

    return $html;
  }

  /**
   * Load the parse URL page into a DOMDocument
   */
  private function getDom() {
    $content = file_get_contents($this->scrap_url);
    if (empty($content)) {
      return;
    }
    libxml_use_internal_errors(true);
    $dom = new DOMDocument();
    $dom->loadHTML($content);
    libxml_clear_errors();
    return $dom;
  }

  /**
   * Return the HTML of the form later
   */
  private function getForm() {
    if (!isset($this->dom)) {
      return;
    }
    $form = $this->dom->getElementById('frmbranches');
    if (isset($form)) {
      return $form->ownerDocument->saveHTML($form);
    }
  }
}
