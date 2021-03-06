<?php
declare (strict_types=1);

namespace Smalls\VideoTools\Logic;

use Smalls\VideoTools\Enumerates\UserGentType;
use Smalls\VideoTools\Exception\ErrorVideoException;
use Smalls\VideoTools\Utils\CommonUtil;

/**
 * Created By 1
 * Author：smalls
 * Email：smalls0098@gmail.com
 * Date：2020/6/10 - 18:22
 **/
class ShuaBaoLogic extends Base
{

    private $contents;
    private $showId;


    public function setShowId()
    {
        preg_match('/show_id=(.*?)&/i', $this->url, $itemMatches);

        if (CommonUtil::checkEmptyMatch($itemMatches)) {
            throw new ErrorVideoException("获取不到show_id参数信息");
        }
        $this->showId = $itemMatches[1];
    }

    public function setContents()
    {
        $contents = $this->get('http://h5.shua8cn.com/api/video/detail', [
            'show_id' => $this->showId,
            'provider' => 'weixin',
        ], [
            'User-Agent' => UserGentType::ANDROID_USER_AGENT,
        ]);
        if (isset($contents['code']) && $contents['code'] != "0") {
            throw new ErrorVideoException("获取不到指定的内容信息");
        }

        $this->contents = $contents;
    }

    /**
     * @return mixed
     */
    public function getContents()
    {
        return $this->contents;
    }

    /**
     * @return mixed
     */
    public function getUrl()
    {
        return $this->url;
    }

    public function getVideoUrl()
    {
        return CommonUtil::getData($this->contents['data']['video_url']);
    }


    public function getVideoImage()
    {
        return CommonUtil::getData($this->contents['data']['cover_pic']['720']);
    }

    public function getVideoDesc()
    {
        return CommonUtil::getData($this->contents['data']['description']);
    }

    public function getUserPic()
    {
        return CommonUtil::getData($this->contents['data']['user_info']['avatar']['720']);
    }

    public function getUsername()
    {
        return CommonUtil::getData($this->contents['data']['user_info']['nickname']);
    }


}