<?php

namespace Sem\FlindersCase;

class Html
{
    public function __construct(array $tables)
    {
        $this->startDoc();
        $this->setHead();
        $this->startBody();

        foreach ($tables as $table) {
            $this->showTable($table);
        }

        $this->endBody();
        $this->endDoc();
    }

    public function startDoc($render = true)
    {
        if ($render) : ?>
            <!DOCTYPE html>
            <html>
        <?php endif;
    }

    public function setHead($render = true)
    {
        if ($render) : ?>
            <head>
                <title>Flinders Case</title>
                <meta name="viewport" content="device-width, initial-scale=1.0">
                <meta charset="UTF-8">
            </head>
        <?php endif;
    }

    public function startBody($render = true)
    {
        if ($render) : ?>
            <body>
        <?php endif;
    }

    public function showTable($data, $render = true)
    {
        if ($render) : ?>
            <table style="border: 1px solid black">
                <thead>
                    <tr>
                        <?php
                        $headers = array_keys($data[0]);
                        foreach ($headers as $header) {
                            echo '<th style="border: 2px solid white; background-color: #778da9">' . $header . '</th>';
                        }
                        ?>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($data as $entry) {
                        echo '<tr>';
                        foreach ($entry as $field) {
                            echo '<td>' . $field . '</td>';
                        }
                        echo '</tr>';
                    }
                    ?>
                </tbody>
            </table>
        <?php endif;
    }

    public function endBody($render = true)
    {
        if ($render) : ?>
            </body>
        <?php endif;
    }

    public function endDoc($render = true)
    {
        if ($render) : ?>
            </html>
        <?php endif;
    }
}
