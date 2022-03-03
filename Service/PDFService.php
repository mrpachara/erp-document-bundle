<?php

namespace Erp\Bundle\DocumentBundle\Service;

use Mpdf\Mpdf;
use Mpdf\Output\Destination;
use Symfony\Component\OptionsResolver\OptionsResolver;

class PDFService {

    /**
     * @var array
     */
    private $options;

    /**
     * MpdfService constructor.
     * @param array $options
     */
    public function __construct(array $options = null)
    {
        $options = (array)$options;
        if(!isset($options['fontDir'])) $options['fontDir'] = [];
        if(!isset($options['fontdata'])) $options['fontdata'] = [];

        $defaultConfig = (new \Mpdf\Config\ConfigVariables())->getDefaults();
        $fontDirs = $defaultConfig['fontDir'];

        $defaultFontConfig = (new \Mpdf\Config\FontVariables())->getDefaults();
        $fontdata = $defaultFontConfig['fontdata'];

        $options['fontDir'] = array_merge($fontDirs, $options['fontDir']);
        $options['fontdata'] = $fontdata + $options['fontdata'];

        $this->options = $options;
    }

    /**
     * @param string $html
     * @param array $options
     * @return string
     * @throws \Mpdf\MpdfException
     */
    public function generatePdf($html, $options = array(), $setting = null)
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            'mode' => 'utf-8',
            'format' => 'A4',
        ]);
        $options = $resolver->resolve($options);
        $mpdf = new Mpdf($options + $this->options);
        if($setting !== null) {
            $setting($mpdf);
        }

        @$mpdf->WriteHTML($html);

        return $mpdf->Output(null, Destination::STRING_RETURN);
    }
}
