<?php

/**
 * @author Moisés Dandico <moises.dandico@gmail.com>
 *
 * @version 1.0
 *
 *  classe realiza o scraping de uma tabela html
 */

namespace ParseTable;

require('../vendor/autoload.php');

use duzun\hQuery;

class ParseTable
{
    protected $guzzle;
    protected $parsedHtml;
    public $numColumns;
    public $titles;
    public $content;

    public function __construct()
    {
        $this->guzzle = new \GuzzleHttp\Client();
    }

    /**
     * getContent
     *
     * @param  string $url
     *
     * @return void
     */
    public function getContent(string $url)
    {
        try {
            $response = $this->guzzle->request('GET', $url);
            $this->content = $response->getBody();
            $this->parsedHtml = hQuery::fromHTML($this->content);
        } catch (\Throwable $th) {
            return false;
        }
        return true;
    }

    /**
     * getnumColums
     *
     * @return int number of columns
     */
    public function getnumColums()
    {
        if (!$this->parsedHtml) {
            return false;
        } else {
            $headerTable = $this->parsedHtml->find('tbody > tr')[0];
            $this->numColumns = substr_count($headerTable->outerHtml(), '<td');
        }
        return $this->numColumns;
    }

    /**
     * findTitles
     *
     * @return array titles of table
     */
    public function findTitles()
    {

        if (!$this->parsedHtml) {
            return false;
        } else {
            if (empty($this->numColumns)) {
                $this->getnumColums();
            }
            $count = 0;
            $this->titles = array();
            $table = $this->parsedHtml->find('tbody > tr > td');
            foreach ($table as $element) {
                if ($count < $this->numColumns) {
                    $this->titles[] = trim($element->text());
                } else {
                    break;
                }
                $count++;
            }
            return $this->titles;
        }
    }

    /**
     * getDataTable
     *
     * @return array parsed table from array
     */
    public function getDataTable()
    {
        if (!$this->parsedHtml) {
            return false;
        } else {
            if (empty($this->titles)) {
                $this->findTitles();
            }

            $count = 0;
            $cell = 0;
            $lines = array();
            $data = array();
            $table = $this->parsedHtml->find('tbody > tr > td');
            foreach ($table as $element) {
                if ($cell > $this->numColumns - 1) {
                    $lines[$this->titles[$count]] = trim($element->text());
                    $count++;
                    if ($count == $this->numColumns) {
                        $count = 0;
                        $data[] = $lines;
                        reset($lines);
                    }
                }
                $cell++;
            }
    
            return $data;
        }
    }
}

$example = new ParseTable();
$example->getContent('http://www.guiatrabalhista.com.br/guia/salario_minimo_1940a1999.htm');
$data = $example->getDataTable();
var_dump($data);
