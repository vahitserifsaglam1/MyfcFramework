<?php
class apiSdk{
	
    public  $oauth;
    public  $url;
    public  $type = "SELECT";
    public  $select = "*";
    public  $where;
    public  $order;
    public  $orderType;
    public  $post;
    public  $adress;
    public  $from;
    public  $limit;
    public  $like;
    protected $referer = "http://www.ozsabilisim.org";
    public function __construct($url,$consumer_key,$consumer_secret,$ouath_token,$ouath_token_secret)
    {
        $this->consumerkey = $consumer_key;
        $this->consumersecret = $consumer_secret;
        $this->ouathtoken  = $ouath_token;
        $this->oauthtokensecret = $ouath_token_secret;
        $this->adress = $url;
        $this->type = "SELECT";
        $this->select = "*";
        $this->order = "id";
        $this->orderType = "DESC";
    }
    public function setType($type="SELECT")
    {
        $this->type = mb_convert_case($type,MB_CASE_UPPER,'UTF-8');
        return $this;
    }
    public function setSelect($select = "*")
    {
        $this->select = $select;
        return $this;
    }

    public function setReferer($referer = "http://www.ozsabilisim.org")
    {
         $this->referer = $referer;
    }
    public function setArray( array $array = array() )
    {
        foreach( $array as $key => $value )
        {
            $upper = mb_convert_case($key,MB_CASE_TITLE, 'utf-8');
            $call = "set$upper";

            $this->$call($value);
        }


    }
    public  function setFrom($from)
    {
        $this->from = $from;
         return $this;
    }
    public function setWhere($array = array())
    {
       $d = "";
        foreach($array as $key => $value)
        {
             $d .= "|$key/$value|";
        }
        $this->where = $d;
        return $this;
    }
    public function setOrder($order,$type = "DESC")
    {
      $this->order = $order;
        $this->orderType = $type;
         return $this;
    }
    public function addPost($post)
    {
        $this->post = $post;
         return $this;
    }
    public function setLimit($limit)
    {
         $this->limit = $limit;
          return $this;
    }
    public  function  setLike($id,$name)
    {
        $this->like = "$id LIKE '%$name'";
        return $this;
    }
    public function returnUrl()
    {
        return $this->url;
    }
    public function execute()
    {
        $this->url = $this->adress."?consumer_key=".$this->consumerkey."&consumer_secret=".$this->consumersecret."&oauth_token=".$this->ouathtoken."&oauth_token_secret=".$this->oauthtokensecret."&type=".$this->type."&from=".$this->from;
        if($this->select){$this->url .= "&select=".$this->select; }
        if($this->where){$this->url .= "&where=".$this->where; }
        if($this ->order && $this->orderType){$this->url .= "&order=".$this->order."&ordertype=".$this->orderType;}
        if($this->limit){$this->url .= "&limit=".$this->limit;}
        if($this->like ){$this->url .= "&like=".$this->like;}
        $url = $this->url;
        $post = $this->post;
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_REFERER, $this->referer);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        if($post)
        {
            $fields_string = "";
            foreach($post as $key=>$value) { $fields_string .= $key.'='.$value.'&'; }
            $fields_string = rtrim($fields_string, '&');
            curl_setopt($ch,CURLOPT_POST, count($post));
            curl_setopt($ch,CURLOPT_POSTFIELDS, $fields_string);
        }
        $site = curl_exec($ch);
        curl_close($ch);
        return  $site;
     }

}

 $api = new myApi("http://localhost/php/cmv/lib/api.php","5f38ceb9352b22fb2949f52e71c2a35f","5f38ceb9352b22fb2949f52e71c2a35f","adsasdasdasdads","adsasdasdasdads");
 $sorgu = $api->setType("UPDATE")->setFrom("user")->addPost(array('real_name' => 'saglam'))->setWhere(array('user_id' => 1))->execute();

 print_r($sorgu);
