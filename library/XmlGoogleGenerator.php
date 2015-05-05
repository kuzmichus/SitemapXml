<?php


namespace SitemapXml;


use SitemapXml\Helper\Formatter;
use SitemapXml\Validators\Date;
use SitemapXml\Validators\Rating;

/**
 * Class XmlGoogleGenerator
 * @package SitemapXml
 */
class XmlGoogleGenerator extends XmlGenerator
{
    /**
     * @param $url
     * @param array $params
     * @return array
     */
    protected function buildRecord($url, $params = array())
    {
        $lines = array();
        $lines[] = Formatter::format('<url>', 2);
        $lines[] = Formatter::format('<loc>' . $url . '</loc>', 3);

        if (isset($params['video:video'])) {
            $this->addXmlns('video', 'http://www.google.com/schemas/sitemap-video/1.1');
            $lines = array_merge($lines, $this->buildVideoRecord($params['video:video']));
        }

        if (isset($params['image:image'])) {
            $this->addXmlns('image', 'http://www.google.com/schemas/sitemap-image/1.1');
        }

        $lines[] = Formatter::format('</url>', 2);
        return $lines;
    }

    /**
     * @param $video
     * @return array
     */
    private function buildVideoRecord($video)
    {
        $lines = array();

        $lines[] = Formatter::format('<video:video>', 3);

        if (isset($video['thumbnail_loc'])) {
            $lines[]= Formatter::format('<video:thumbnail_loc>' . str_replace('&', '&amp;', $video['thumbnail_loc']) . '</video:thumbnail_loc>', 4);
        }

        if (isset($video['title'])) {
            $lines[]= Formatter::format('<video:title>' . $video['title'] . '</video:title>', 4);
        }

        if (isset($video['description'])) {
            $lines[]= Formatter::format('<video:description>' . $video['description'] . '</video:description>', 4);
        }

        if (isset($video['content_loc'])) {
            $lines[]= Formatter::format('<video:player_loc>' . str_replace('&', '&amp;', $video['content_loc']) . '</video:player_loc>', 4);
        }

        if (isset($video['player_loc'])) {
            $options = isset($video['player_loc']['allow_embed']) ? ' allow_embed="' . $video['player_loc']['allow_embed'] . '"' : '';
            $options .= isset($video['player_loc']['autoplay']) ? ' autoplay="' . $video['player_loc']['autoplay'] . '"' : '';

            $lines[]= Formatter::format('<video:player_loc' . $options . '>' . str_replace('&', '&amp;', $video['player_loc']['url']) . '</video:player_loc>', 4);
        }

        if (isset($video['duration'])) {
            $lines[]= Formatter::format('<video:duration>' . $video['duration'] . '</video:duration>', 4);
        }

        if (isset($video['expiration_date'])) {
            Date::Validation($video['expiration_date']);
            $lines[]= Formatter::format('<video:expiration_date>' . $video['expiration_date'] . '</video:expiration_date>', 4);
        }

        if (isset($video['rating'])) {
            Rating::Validation($video['rating']);
            $lines[]= Formatter::format('<video:rating>' . $video['rating'] . '</video:rating>', 4);
        }

        if (isset($video['view_count'])) {
            $lines[]= Formatter::format('<video:view_count>' . intval($video['view_count']) . '</video:view_count>', 4);
        }

        if (isset($video['publication_date'])) {
            Date::Validation($video['publication_date']);
            $lines[]= Formatter::format('<video:publication_date>' . $video['publication_date'] . '</video:publication_date>', 4);
        }

        if (isset($video['family_friendly'])) {
            $lines[]= Formatter::format('<video:family_friendly>' . $video['family_friendly'] . '</video:family_friendly>', 4);
        }

        if (isset($video['tags'])) {
            foreach ((array)$video['tags'] as $tag) {
                $lines[]= Formatter::format('<video:tag>' . $tag . '</video:tag>', 4);
            }
        }

        if (isset($video['category'])) {
            $lines[]= Formatter::format('<video:category>' . $video['category'] . '</video:category>', 4);
        }

        if (isset($video['restriction_allow'])) {
            $lines[]= Formatter::format('<video:restriction relationship="allow">' . $video['restriction'] . '</video:restriction>', 4);
        }

        if (isset($video['restriction_deny'])) {
            $lines[]= Formatter::format('<video:restriction relationship="deny">' . $video['restriction'] . '</video:restriction>', 4);
        }

        if (isset($video['gallery_loc'])) {
            $title = isset($video['gallery_loc']['title'])? ' title="' . $video['gallery_loc']['title'] . '"':'';
            $lines[]= Formatter::format('<video:gallery_loc' . $title . '>' . $video['gallery_loc']['url'] . '</video:gallery_loc>', 4);
        }

        if (isset($video['price'])) {
            if (isset($video['price']['price'])) {
                $prices[] = $video['price'];
            } else {
                $prices = $video['price'];
            }
            foreach ($prices as $price) {
                $options = isset($price['currency']) ? ' currency="' . $price['currency'] . '"' : '';
                $options .= isset($price['type']) ? ' type="' . $price['type'] . '"' : '';
                $options .= isset($price['resolution']) ? ' resolution="' . $price['resolution'] . '"' : '';
                $lines[]= Formatter::format('<video:price' . $options . '>' . $price['price'] . '</video:price>', 4);
            }
        }

        if (isset($video['requires_subscription'])) {
            $lines[]= Formatter::format('<video:requires_subscription>' . $video['requires_subscription'] . '</video:requires_subscription>', 4);
        }

        if (isset($video['uploader'])) {
            $lines[]= Formatter::format('<video:uploader>' . $video['uploader'] . '</video:uploader>', 4);
        }

        if (isset($video['platform'])) {
            $lines[]= Formatter::format('<video:platform>' . $video['platform'] . '</video:platform>', 4);
        }

        if (isset($video['live'])) {
            $lines[]= Formatter::format('<video:live>' . $video['live'] . '</video:live>', 4);
        }


        $lines[] = Formatter::format('</video:video>', 3);

        return $lines;
    }

}
