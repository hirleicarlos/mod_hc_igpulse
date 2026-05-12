<?php
/**
 * @package     Joomla.Module
 * @subpackage  mod_hc_igpulse
 *
 * @copyright   (C) 2026 Hirlei Carlos Pereira de Araújo
 * @license     MIT
 *
 * @since       1.0.0
 */

namespace Joomla\Module\HcIgpulse\Site\Helper;

use Joomla\CMS\Cache\CacheControllerFactoryInterface;
use Joomla\CMS\Cache\Controller\CallbackController;
use Joomla\CMS\Factory;
use Joomla\CMS\Http\HttpFactory;
use Joomla\CMS\Log\Log;
use Joomla\Registry\Registry;

use function defined;

// phpcs:disable PSR1.Files.SideEffects
defined('_JEXEC') or die;
// phpcs:enable PSR1.Files.SideEffects

/**
 * Helper do módulo mod_hc_igpulse.
 * Responsável por buscar e normalizar os dados da Instagram Graph API.
 *
 * @since 1.0.0
 */
class HcIgpulseHelper
{
    private const API_BASE   = 'https://graph.instagram.com';
    private const MAX_ITEMS  = 20;
    private const API_FIELDS = 'id,media_type,media_url,thumbnail_url,permalink,caption,timestamp,like_count';

    /**
     * Retorna os itens do feed processados e cacheados.
     *
     * @param Registry $params Parâmetros do módulo.
     *
     * @return array<int, array<string, mixed>>
     *
     * @since 1.0.0
     */
    public function getItems(Registry $params): array
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
            /** @var CallbackController $cache */
            $cache = Factory::getContainer()
                ->get(CacheControllerFactoryInterface::class)
                ->createCacheController('callback', [
                    'defaultgroup' => 'mod_hc_igpulse',
                    'lifetime'     => $cacheTime,
                    'caching'      => true,
                ]);

            return $cache->get(
                [$this, 'fetchFromApi'],
                [$accessToken, $userId, $mediaType, $countFetch],
                $cacheKey
            );
        } catch (\Exception $e) {
            Log::add('mod_hc_igpulse cache error: ' . $e->getMessage(), Log::WARNING, 'mod_hc_igpulse');

            return $this->fetchFromApi($accessToken, $userId, $mediaType, $countFetch);
        }
    }

    /**
     * Faz a chamada à Instagram Graph API.
     *
     * @param string $accessToken Token de acesso.
     * @param string $userId      ID do usuário Instagram.
     * @param string $mediaType   Filtro de tipo de mídia.
     * @param int    $countFetch  Quantidade de itens.
     *
     * @return array<int, array<string, mixed>>
     *
     * @since 1.0.0
     */
    public function fetchFromApi(string $accessToken, string $userId, string $mediaType, int $countFetch): array
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
                Log::add('mod_hc_igpulse API HTTP ' . $response->code, Log::WARNING, 'mod_hc_igpulse');

                return [];
            }

            $data = json_decode($response->body, true);
        } catch (\Exception $e) {
            Log::add('mod_hc_igpulse HTTP error: ' . $e->getMessage(), Log::WARNING, 'mod_hc_igpulse');

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

    /**
     * Retorna a URL da imagem/thumbnail do item.
     *
     * @param array<string, mixed> $item Item da API.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function getMediaSrc(array $item): string
    {
        if (($item['media_type'] ?? '') === 'VIDEO') {
            return $item['thumbnail_url'] ?? '';
        }

        return $item['media_url'] ?? '';
    }

    /**
     * Verifica se o item é um Reel.
     *
     * @param array<string, mixed> $item Item da API.
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function isReel(array $item): bool
    {
        return ($item['media_type'] ?? '') === 'VIDEO';
    }

    /**
     * Verifica se o item é um álbum/carrossel.
     *
     * @param array<string, mixed> $item Item da API.
     *
     * @return bool
     *
     * @since 1.0.0
     */
    public static function isCarouselAlbum(array $item): bool
    {
        return ($item['media_type'] ?? '') === 'CAROUSEL_ALBUM';
    }

    /**
     * Formata e trunca a legenda do post.
     *
     * @param string $caption   Texto original.
     * @param int    $maxLength Tamanho máximo.
     *
     * @return string
     *
     * @since 1.0.0
     */
    public static function formatCaption(string $caption, int $maxLength = 120): string
    {
        $caption = strip_tags($caption);

        if (mb_strlen($caption) <= $maxLength) {
            return htmlspecialchars($caption, ENT_QUOTES, 'UTF-8');
        }

        return htmlspecialchars(mb_substr($caption, 0, $maxLength), ENT_QUOTES, 'UTF-8') . '&hellip;';
    }
}
