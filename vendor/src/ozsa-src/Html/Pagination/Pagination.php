<?php

namespace Html;
/**
 * Class Pagination
 * @package Html
 */
    class Pagination
    {
        public $homeClass;
        public $linkClass;
        public $max = false;
        public $min = false;
        public $records = false;
        public $activePage;
        public $url;
        public $file;
        public $endUrl;
        public $configs;
        public $standartLink;
        public $standartUrl;

        /**
         * @param null $page
         * @param null $records
         *
         */

        public function __construct( $records = null )
        {
            $configs = require APP_PATH.'Configs/paginationConfigs.php';
            $standart = require APP_PATH.'Configs/Configs.php';
            $this->standartUrl = $standart['URL'];

            extract($configs);
            $this->configs = $configs;
            $this->homeClass = $homeClass;
            $this->linkClass = $linkClass;
            $this->min = $min;
            $this->max = $max;
            $this->records = $records;
            $this->url = \App\App::urlParse();
            $sonurl = array_pop($this->url);
            $this->endUrl = $sonurl;
            $this->urlParser();
            $this->standartLinkCreator();
            return $this;
        }

        public static function boot( $records = null )
        {

            return new static( $records );

        }

        /**
         * @param bool $return
         * @return string
         */

        public function execute( $return = false )
        {
           $msg = '';

            $msg .= $this->homeCreator();

            $msg .= $this->linkCreator();

            $msg .= $this->homeEndCreator();

            if($return) return $msg;else echo $msg;
        }

        /**
         * @return string
         */
        public function homeCreator()
        {
           $return = '<div class = "'.$this->homeClass.'">'."\n";
            return $return;
        }

        /**
         * @return string
         */

        public function homeEndCreator()
        {
            return '</div>';
        }


        /**
         * ****
         */
        public function linkCreator()
        {

            $msg = '';
            $page = $this->activePage;
            $min = $this->min;
            $max = $this->max;
            $records = $this->records;
            ////////////////////////////
            // Buraya ayar dosyasından veriler çekilecek
            ////////////////////////////
            if (!$min) $min = $this->configs['min'];
            if (!$max) $max = $this->configs['max'];
            ///////////////////////////////
            // 1den küçük,büyük kontrolu
            ///////////////////////////////

            $minpage = ($page - $min);
            $minpage = ($minpage < 1) ? 1 : $minpage;
            $maxpage = ($minpage + $max);
            $maxpage = ($maxpage > $records) ? $records : $maxpage;

            //////////////////////////

            $end = $this->endUrl;

            /////////////////////////



            for($i = $minpage;$i<=$maxpage;$i++)
            {

                 $end = $end.'/'.$i;

                 $msg .=  $this->linkAndMessageCreator($this->standartUrl,$i,$end);

            }


            return $msg;
        }



        /**
         * @return string
         */

       public function standartLinkCreator()
        {
            $msg = '';
            foreach($this->url as $key )
            {
                $msg .= $key.'/';

            }
            $this->standartLink = $msg;
            return $msg;
        }

        /**
         * @param $i
         * @param $link
         * @return string
         */

        public function linkAndMessageCreator($standart,$i,$link)
        {

                $msg = '<a class="'.$this->linkClass.'" href="'.$standart.$this->standartLink.$i.'">'.$i.'</a>';
                return $msg;
        }

        /**
         * @return null
         */

        public function urlParser()
        {
            $url = $this->url;
            if(count($url) == 1 && $url[0] == '')
            {
                $url[0] = "index";
            }
            $this->url = $url;
            return null;

        }

        /**
         * @param bool $records
         * @param bool $page
         * @return $this
         */

        public function setRecords($records = false,$page = false)
        {
            if(!isset($this->records)) $this->records = $records;
            if(!isset($this->activePage)) $this->activePage = $page;
            return $this;
        }

        /**
         * @param $max
         * @return $this
         */

        public function setMax($max)
        {
            if(!isset($this->max)) $this->max = $max;
            return $this;
        }

        /**
         * @param $min
         * @return $this
         */

        public function setMin($min)
        {
            if(!isset($this->min)) $this->min = $min;
            return $this;
        }

        /**
         * @param $min
         * @param $max
         * @return $this
         */

        public function setMaxAndMin($min,$max)
        {
            if(!isset($this->min))
            {
                $this->min = $min;
            }
            if(!isset($this->max))
            {
                $this->max = $max;
            }
            return $this;
        }

    }
