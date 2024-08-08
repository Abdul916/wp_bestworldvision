<?php

namespace Nextend\SmartSlider3Pro\Generator\Common\YouTube\Sources;

use Exception;
use Nextend\Framework\Form\Container\ContainerTable;
use Nextend\Framework\Form\Element\Select;
use Nextend\Framework\Form\Element\Text;
use Nextend\Framework\Notification\Notification;
use Nextend\GoogleApi\Google_Service_YouTube_SearchListResponse;
use Nextend\GoogleApi\Google_Service_YouTube_SearchResult;
use Nextend\SmartSlider3\Generator\AbstractGenerator;
use Nextend\SmartSlider3Pro\Generator\Common\YouTube\Elements\YouTubePlaylistByUser;
use Nextend\SmartSlider3Pro\Generator\Common\YouTube\googleclient\Service\Google_Service_YouTube;

class YouTubeByPlaylist extends AbstractGenerator {

    private $resultPerPage = 50;
    private $pages;
    private $youtubeClient;

    protected $layout = 'youtube';

    public function getDescription() {
        return sprintf(n2_('Creates slides from %1$s.'), 'YouTube ' . n2_('Playlist'));
    }

    public function renderFields($container) {

        $filterGroup = new ContainerTable($container, 'filter-group', n2_('Filter'));

        $filter = $filterGroup->createRow('filter');

        new Text($filter, 'channel-id', 'Channel id - ' . n2_('optional'), '', array(
            'style' => "width:400px;"
        ));

        new YouTubePlaylistByUser($filter, 'playlist-id', 'Playlist', '', array(
            'config' => $this->group->getConfiguration()
        ));

        new Select($filter, 'privacy', n2_('Privacy status'), 'public||private||unlisted', array(
            'isMultiple' => true,
            'size'       => 3,
            'options'    => array(
                'public'   => 'public',
                'private'  => 'private',
                'unlisted' => 'unlisted'
            )
        ));
    }

    protected function resetState() {
        $this->pages = array();

        if (!$this->youtubeClient) {
            $client              = $this->group->getConfiguration()
                                               ->getApi();
            $this->youtubeClient = new Google_Service_YouTube($client);
        }
    }

    protected function _getData($count, $startIndex) {

        $data = array();
        try {
            $offset  = $startIndex;
            $limit   = $count;
            $privacy = explode('||', $this->data->get('privacy', 'public||private||unlisted'));
            for ($i = 0, $j = $offset; $j < $offset + $limit; $i++, $j++) {

                $items = $this->getPage(intval($j / $this->resultPerPage))
                              ->getItems();

                /** @var Google_Service_YouTube_SearchResult $item */
                $item = @$items[$j % $this->resultPerPage];
                if (empty($item)) {
                    // There is no more item in the list
                    break;
                }

                if (in_array($item['status']['privacyStatus'], $privacy)) {
                    $snippet               = $item['snippet'];
                    $record                = array();
                    $record['video_id']    = $snippet['resourceId']['videoId'];
                    $record['video_url']   = 'http://www.youtube.com/watch?v=' . $snippet['resourceId']['videoId'];
                    $record['title']       = $snippet['title'];
                    $record['description'] = $snippet['description'];
                    if (isset($snippet['thumbnails']) && isset($snippet['thumbnails']['default']) && isset($snippet['thumbnails']['default']['url'])) {
                        $record['thumbnail'] = $snippet['thumbnails']['default']['url'];
                    }
                    if (isset($snippet['thumbnails']) && isset($snippet['thumbnails']['medium']) && isset($snippet['thumbnails']['medium']['url'])) {
                        $record['thumbnail_medium'] = $snippet['thumbnails']['medium']['url'];
                    }
                    if (isset($snippet['thumbnails']) && isset($snippet['thumbnails']['high']) && isset($snippet['thumbnails']['high']['url'])) {
                        $record['thumbnail_high'] = $snippet['thumbnails']['high']['url'];
                    }
                    if (isset($snippet['thumbnails']) && isset($snippet['thumbnails']['standard']) && isset($snippet['thumbnails']['standard']['url'])) {
                        $record['thumbnail_standard'] = $snippet['thumbnails']['standard']['url'];
                    }
                    if (isset($snippet['thumbnails']) && isset($snippet['thumbnails']['maxres']) && isset($snippet['thumbnails']['maxres']['url'])) {
                        $record['thumbnail_maxres'] = $snippet['thumbnails']['maxres']['url'];
                    }
                    $record['channel_title'] = $snippet['channelTitle'];
                    $record['channel_url']   = 'http://www.youtube.com/channel/' . $snippet['channelId'];

                    $data[] = &$record;
                    unset($record);
                }
            }
        } catch (Exception $e) {
            Notification::error($e->getMessage());
        }

        return $data;
    }

    private function getPage($page) {
        if (!isset($this->pages[$page])) {
            $request = array(
                'maxResults' => $this->resultPerPage,
                'playlistId' => $this->data->get('playlist-id', '')
            );
            if ($page != 0) {
                $request['pageToken'] = $this->getPage($page - 1)
                                             ->getNextPageToken();
            }
            /** @var Google_Service_YouTube_SearchListResponse $searchResponse */
            $this->pages[$page] = $this->youtubeClient->playlistItems->listPlaylistItems('id,snippet,status', $request);
        }

        return $this->pages[$page];
    }
}