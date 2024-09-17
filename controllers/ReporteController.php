<?php

namespace Controllers;

use Mpdf\HTMLParserMode;
use Mpdf\Mpdf;
use MVC\Router;

class ReporteController
{
    public static function pdf(Router $router)
    {
        $mpdf = new Mpdf(
            [
                "default_font_size" => "12",
                "default_font" => "arial",
                "orientation" => "P",
                "margin_top" => "30",
                "format" => "Letter"
            ]
        );

        // Cargar HTML y CSS
        $html = $router->load('pdf/reporte');
        $css = file_get_contents(__DIR__ . '/../views/pdf/styles.css');
        $header = $router->load('pdf/header');
        $footer = $router->load('pdf/footer');

        // Configurar header y footer
        $mpdf->SetHTMLHeader($header);
        $mpdf->SetHTMLFooter($footer);

        // Escribir el contenido HTML y CSS
        $mpdf->WriteHTML($css, HTMLParserMode::HEADER_CSS);
        $mpdf->WriteHTML($html, HTMLParserMode::HTML_BODY);

        // Añadir una nueva página si es necesario
        $mpdf->AddPage("L");

        // Definir el directorio y nombre del archivo PDF
        $publicDir = __DIR__ . '/../../public/temp/'; // Ajusta según tu estructura de carpetas
        $fileName = 'reporte.pdf';
        $filePath = $publicDir . $fileName;

        // Asegúrate de que la carpeta 'temp' exista
        if (!is_dir($publicDir)) {
            mkdir($publicDir, 0777, true);
        }

        // Guardar el archivo PDF en la ruta especificada
        $mpdf->Output($filePath, 'F');

        // Confirmar que el archivo se guardó correctamente
        if (file_exists($filePath)) {
            echo 'PDF guardado correctamente en ' . $filePath;
        } else {
            echo 'Error al guardar el PDF.';
        }
    }
}
