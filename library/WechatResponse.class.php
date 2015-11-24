<?php
abstract class WechatResponse
{
    protected $fromUserName;
    protected $toUserName;
    protected $template;

    public function __construct($fromUserName, $toUserName)
    {
        $this->fromUserName = $fromUserName;
        $this->toUserName = $toUserName;
    }

    abstract public function __toString();
}

class TextResponse extends WechatResponse
{
    protected $content;

    public function __construct($fromUserName, $toUserName, $content)
    {
        parent::__construct($fromUserName, $toUserName);
        $this->content = $content;
        $this->template =<<<XML
<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%d</CreateTime>
<MsgType><![CDATA[text]]></MsgType>
<Content><![CDATA[%s]]></Content>
</xml>
XML;
    }

    public function setContent($content)
    {
        $this->content = $content;
    }

    public function __toString()
    {
        return sprintf($this->template, $this->toUserName, $this->fromUserName, time(), $this->content);
    }
}

class ImageResponse extends WechatResponse
{
    protected $mediaId;

    public function __construct($fromUserName, $toUserName, $mediaId)
    {
        parent::__construct($fromUserName, $toUserName);
        $this->mediaId = $mediaId;
        $this->template =<<<XML
<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%d</CreateTime>
<MsgType><![CDATA[image]]></MsgType>
<Image>
<MediaId><![CDATA[%d]]></MediaId>
</Image>
</xml>
XML;
    }

    public function __toString()
    {
        return sprintf($this->template, $this->toUserName, $this->fromUserName, time(), $this->mediaId);
    }
}

class VoiceResponse extends WechatResponse
{
    protected $mediaId;

    public function __construct($fromUserName, $toUserName, $mediaId)
    {
        parent::__construct($fromUserName, $toUserName);
        $this->mediaId = $mediaId;
        $this->template = <<<XML
<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%d</CreateTime>
<MsgType><![CDATA[voice]]></MsgType>
<Voice>
<MediaId><![CDATA[%d]]></MediaId>
</Voice>
</xml>
XML;
    }

    public function __toString()
    {
        return sprintf($this->template, $this->toUserName, $this->fromUserName, time(), $this->mediaId);
    }
}

class VideoResponse extends WechatResponse
{
    protected $mediaId;
    protected $title;
    protected $description;

    public function __construct($fromUserName, $toUserName, $mediaId, $title, $description)
    {
        parent::__construct($fromUserName, $toUserName);
        $this->mediaId = $mediaId;
        $this->title = $title;
        $this->description = $this->description;
        $this->template = <<<XML
<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%d</CreateTime>
<MsgType><![CDATA[video]]></MsgType>
<Video>
<MediaId><![CDATA[%d]]></MediaId>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
</Video> 
</xml>
XML;
    }

    public function __toString()
    {
        return sprintf($this->template, $this->toUserName, $this->fromUserName, time(), 
                        $this->mediaId, $this->title, $this->description);
    }
}

class MusicResponse extends WechatResponse
{
    protected $title;
    protected $description;
    protected $musicUrl;
    protected $HQMusicUrl;
    protected $thumbMediaId;

    public function __construct($fromUserName, $toUserName, $title, $description, $musicUrl, $HQMusicUrl, $thumbMediaId)
    {
        parent::__construct($fromUserName, $toUserName);
        $this->title = $title;
        $this->description = $description;
        $this->musicUrl = $musicurl;
        $this->HQMusicUrl = $HQMusicUrl;
        $this->thumbMediaId = $thumbMediaId;

        $this->template = <<<XML
<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%d</CreateTime>
<MsgType><![CDATA[music]]></MsgType>
<Music>
<Title><![CDATA[%s]]></Title>
<Description><![CDATA[%s]]></Description>
<MusicUrl><![CDATA[%s]]></MusicUrl>
<HQMusicUrl><![CDATA[%s]]></HQMusicUrl>
<ThumbMediaId><![CDATA[%d]]></ThumbMediaId>
</Music>
</xml>
XML;
    }

    public function __toString()
    {
        return sprintf($this->template, $this->toUserName, $this->fromUserName, time(), 
                       $this->title, $this->description, $this->musicUrl, $this->HQMusicUrl, $this->thumbMediaId);
    }
}

class NewsResponse extends WechatResponse
{
    protected $items;
    protected $itemTemplate;
    protected $append;

    public function __construct($fromUserName, $toUserName, $items, $append = true)
    {
        parent::__construct($fromUserName, $toUserName);
        $this->items = $items;
        $this->append = $append;
        $this->template = <<<XML
<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%d</CreateTime>
<MsgType><![CDATA[news]]></MsgType>
<ArticleCount>%d</ArticleCount>
<Articles>
%s
</Articles>
</xml>
XML;
        $this->itemTemplate = <<<XML
<item>
<Title><![CDATA[%s]]></Title> 
<Description><![CDATA[%s]]></Description>
<PicUrl><![CDATA[%s]]></PicUrl>
<Url><![CDATA[%s]]></Url>
</item>
XML;
    }

    public function __toString()
    {
        $count = count($this->items);

        $content = '';
        foreach($this->items as $item)
        {
            if($this->append)
            {
                $item['url'] = $item['url'].'?uid='.base64_encode($this->toUserName);
            } else {
                $item['url'] = $item['url'];
            }
            $content .= sprintf($this->itemTemplate, $item['title'], $item['description'], $item['picUrl'], $item['url']);
        }

        return sprintf($this->template, $this->toUserName, $this->fromUserName, time(),
                       $count, $content);
    }
}

class MultiServerTransfer extends WechatResponse
{
    protected $kfAccount;

    public function __construct($fromUserName, $toUserName, $kfAccount)
    {
        parent::__construct($fromUserName, $toUserName);
        $this->kfAccount = $kfAccount;

        $this->template =<<<XML
<xml>
<ToUserName><![CDATA[%s]]></ToUserName>
<FromUserName><![CDATA[%s]]></FromUserName>
<CreateTime>%d</CreateTime>
<MsgType><![CDATA[transfer_customer_service]]></MsgType>
<TransInfo>
	<KfAccount>%s</KfAccount>
</TransInfo>
</xml>
XML;
    }

    public function __toString()
    {
        return sprintf($this->template, $this->toUserName, $this->fromUserName, time(), $this->kfAccount);
    }
}

abstract class KF_Message
{
    protected $touser;
    protected $msgtype;

    public function __construct($touser, $msgtype)
    {
        $this->touser = $touser;
        $this->msgtype = $msgtype;
    }

    abstract public function __toString();
}

class TextMessage extends KF_Message
{
    protected $content;

    public function __construct($touser, $content)
    {
        parent::__construct($touser, 'text');

        $this->content = $content;
    }

    public function __toString()
    {
        $message = array(
            'touser' => $this->touser,
            'msgtype' => $this->msgtype,
            'text' => array(
                'content' => urlencode($this->content)
            )
        );

        return urldecode(json_encode($message));
    }
}

class NewsMessage extends  KF_Message
{
    protected $articles;
    protected $oauthor;

    public function __construct($touser, $articles, $oauthor = false)
    {
        parent::__construct($touser, 'news');

        $this->oauthor = $oauthor;

        foreach($articles as $key=>$article)
        {
            $article['title'] = urlencode($article['title']);
            $article['description'] = urlencode($article['description']);

            if($this->oauthor)
            {
                global $config;

                $oauthor_url = 'https://open.weixin.qq.com/connect/oauth2/authorize?appid=%s&redirect_uri=%s&response_type=code&scope=snsapi_userinfo&state=2048#wechat_redirect';
                $oauthor_url = sprintf($oauthor_url, $config['appid'], urlencode($article['url']));
                $article['url'] = $oauthor_url;
            }

            $articles[$key] = $article;
        }

        $this->articles = $articles;
    }

    public function  __toString()
    {
        if(!$this->oauthor)
        {
            $message = array(
                'touser' => $this->touser,
                'msgtype' => $this->msgtype,
                'news' => array(
                    'articles' => $this->articles
                )
            );

            return urldecode(json_encode($message));
        } else {
            $message_template =<<<JSON
            {"touser":"%s","msgtype":"%s","news":{"articles":[%s]}}
JSON;
            $article_template =<<<JSON
            {"title":"%s","description":"%s","url":"%s","picurl":"%s"}
JSON;
            $articles = '';
            foreach($this->articles as $article)
            {
                $articles .= sprintf($article_template, urldecode($article['title']), urldecode($article['description']), $article['url'], '');
                $articles .= ',';
            }
            $articles = substr($articles, 0, strlen($articles)-1);

            $message = sprintf($message_template, $this->touser, $this->msgtype, $articles);

            return $message;
        }
    }
}
