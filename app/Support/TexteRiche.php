<?php

namespace App\Support;

use HTMLPurifier;
use HTMLPurifier_Config;
use Illuminate\Support\Facades\File;

/**
 * Texte mis en forme saisi avec l'éditeur de l'administration (TinyMCE).
 * Le HTML est nettoyé avant enregistrement : seules les mises en forme
 * proposées par l'éditeur sont conservées (pas de script, pas d'iframe…).
 */
class TexteRiche
{
    private const BALISES = 'p[style],br,strong,b,em,i,u,s,sub,sup,span[style],'
        . 'h2[style],h3[style],h4[style],h5[style],h6[style],'
        . 'ul[style],ol[style],li[style],blockquote,hr,pre,code,'
        . 'a[href|title|target],img[src|alt|width|height|style],'
        . 'table[style|border|cellpadding|cellspacing],caption,thead,tbody,tfoot,tr[style],'
        . 'th[style|colspan|rowspan],td[style|colspan|rowspan],div[style]';

    private const STYLES = 'color,background-color,text-align,text-decoration,font-weight,font-style,'
        . 'font-size,font-family,line-height,margin-left,padding-left,width,height,'
        . 'border,border-collapse,border-color,border-width,border-style,vertical-align,float,margin,list-style-type';

    public static function nettoyer(?string $html): string
    {
        return trim(self::purificateur()->purify((string) $html));
    }

    /** Version texte, un élément par paragraphe (extraits, durée de lecture, recherche). */
    public static function paragraphes(?string $html): array
    {
        $texte = preg_replace('#</(p|h[1-6]|li|blockquote|div|tr|pre|figcaption|caption)>|<br\s*/?>#i', "\n", (string) $html);
        $texte = html_entity_decode(strip_tags($texte), ENT_QUOTES | ENT_HTML5, 'UTF-8');
        $texte = str_replace("\u{00A0}", ' ', $texte);

        return collect(preg_split('/\R/', $texte))
            ->map(fn ($ligne) => trim(preg_replace('/[ \t]+/', ' ', $ligne)))
            ->filter()
            ->values()
            ->all();
    }

    /** HTML simple à partir de paragraphes (contenus d'origine du site). */
    public static function depuisParagraphes(array $paragraphes): string
    {
        return collect($paragraphes)->map(fn ($p) => '<p>' . e($p) . '</p>')->implode("\n");
    }

    private static function purificateur(): HTMLPurifier
    {
        return once(function () {
            $cache = storage_path('framework/cache/htmlpurifier');
            File::ensureDirectoryExists($cache);

            $config = HTMLPurifier_Config::createDefault();
            $config->set('Cache.SerializerPath', $cache);
            $config->set('HTML.Allowed', self::BALISES);
            $config->set('CSS.AllowedProperties', self::STYLES);
            $config->set('Attr.AllowedFrameTargets', ['_blank']);
            $config->set('URI.AllowedSchemes', ['http' => true, 'https' => true, 'mailto' => true, 'tel' => true]);
            $config->set('AutoFormat.RemoveEmpty', true);

            return new HTMLPurifier($config);
        });
    }
}
