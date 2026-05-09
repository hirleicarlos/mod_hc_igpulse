<?php
/**
 * @package     mod_igpulse
 * @subpackage  mod_igpulse
 * @copyright   (C) 2026 hirleicarlos
 * @license     MIT
 * @link        https://github.com/hirleicarlos/mod_igpulse
 */

\defined('_JEXEC') or die;

use Joomla\CMS\Cache\CacheControllerFactoryInterface;
use Joomla\CMS\Factory;
use Joomla\CMS\Http\HttpFactory;
use Joomla\CMS\Log\Log;

class ModIgPulseHelper
{
    private const API_BASE   = 'https://graph.instagram.com';
    private const MAX_ITEMS  = 20;
    private const API_FIELDS = 'id,media_type,media_url,thumbnail_url,permalink,caption,timestamp,like_count';

    public static function getItems($params): array
    {
        $accessToken = trim((string) $params->get('access_token', ''));
        $userId      = trim((string) $params->get('user_id', ''));
        $mediaType   = (string) $params->get('media_type', 'ALL');
        $countFetch  = min((int) $params->get('count_fetch', 12), self::MAX_ITEMS);
        $cacheTime   = (int) $params->get('cache_time', 60);

        if ($accessToken === '' || $userId === '') {
            return [];
        }

        $cacheKey = md5($userId . $mediaType . $countFetch);

        try {
            /** @var \Joomla\CMS\Cache\Controller\CallbackController $cache */
            $cache = Factory::getContainer()
                ->get(CacheControllerFactoryInterface::class)
                ->createCacheController('callback', [
                    'defaultgroup' => 'mod_igpulse',
                    'lifetime'     => $cacheTime,
                    'caching'      => true,
                ]);

            return $cache->get(
                [__CLASS__, 'fetchFromApi'],
                [$accessToken, $userId, $mediaType, $countFetch],
                $cacheKey
            );
        } catch (\Exception $e) {
            Log::add('mod_igpulse cache error: ' . $e->getMessage(), Log::WARNING, 'mod_igpulse');
            return self::fetchFromApi($accessToken, $userId, $mediaType, $countFetch);
        }
    }

    public static function fetchFromApi(string $accessToken, string $userId, string $mediaType, int $countFetch): array
    {
        $url = sprintf(
            '%s/%s/media?fields=%s&limit=%d&access_token=%s',
            self::API_BASE,
            rawurlencode($userId),
            self::API_FIELDS,
            $countFetch,
            rawurlencode($accessToken)
        );

        try {
            $http     = HttpFactory::getHttp();
            $response = $http->get($url, [], 10);

            if ($response->code !== 200) {
                Log::add('mod_igpulse API error HTTP ' . $response->code, Log::WARNING, 'mod_igpulse');
                return [];
            }

            $data = json_decode($response->body, true);
        } catch (\Exception $e) {
            Log::add('mod_igpulse HTTP error: ' . $e->getMessage(), Log::WARNING, 'mod_igpulse');
            return [];
        }

        if (empty($data['data']) || !is_array($data['data'])) {
            return [];
        }

        $items = $data['data'];

        if ($mediaType !== 'ALL') {
            $items = array_values(array_filter($items, static function (array $item) use ($mediaType): bool {
                return isset($item['media_type']) && $item['media_type'] === $mediaType;
            }));
        }

        return array_slice($items, 0, $countFetch);
    }

    public static function getMediaSrc(array $item): string
    {
        if (($item['media_type'] ?? '') === 'VIDEO') {
            return $item['thumbnail_url'] ?? '';
        }

        return $item['media_url'] ?? '';
    }

    public static function isReel(array $item): bool
    {
        return ($item['media_type'] ?? '') === 'VIDEO';
    }

    public static function isCarouselAlbum(array $item): bool
    {
        return ($item['media_type'] ?? '') === 'CAROUSEL_ALBUM';
    }

    public static function formatCaption(string $caption, int $maxLength = 120): string
    {
        $caption = strip_tags($caption);
        if (mb_strlen($caption) <= $maxLength) {
            return htmlspecialchars($caption, ENT_QUOTES, 'UTF-8');
        }

        return htmlspecialchars(mb_substr($caption, 0, $maxLength), ENT_QUOTES, 'UTF-8') . '&hellip;';
    }
}
