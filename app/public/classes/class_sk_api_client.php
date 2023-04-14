<?php
/**
 * A class for interacting with the SK-API to retrieve posts, pages, search results, and images.
 */
class SK_API
{

    /**
     * The base URL of the SK-API.
     * @var string
     */
    private $base_url;

    /**
     * The absolute path to the cache directory. Must be writable.
     * @var string
     */
    private $cache_dir;

    /**
     * The standard cache time in seconds.
     * @var int
     */
    private $cache_time;

    /**
     * The cache time for images in seconds.
     * @var int
     */
    private $image_cache_time;

    /**
     * The cache time for profile images in seconds.
     * @var int
     */
    private $profile_image_cache_time;

    /**
     * The API key used for accessing the SK-API.
     * @var string
     */
    private $api_key;

    /**
     * A boolean flag indicating whether or not to use SSL.
     * @var bool
     */
    private $ssl;

    /**
     * Initializes a new instance of the SK_API class.
     */
    public function __construct()
    {
        $this->base_url = API_PATH;
        $this->api_key = API_KEY; // YOUR API KEY. GET ONE AT https://sk-api.org.space
        $this->cache_dir = CACHE_DIR; // ABSOLUTE PATH TO CACHE DIR. MUST BE WRITABLE!
        $this->cache_time = 600; // STANDARD CACHE TIME IN SECONDS
        $this->image_cache_time = 31536000; // CACHE TIME FOR IMAGES IN SECONDS
        $this->profile_image_cache_time = 86400; // CACHE TIME FOR PROFILE IMAGES IN SECONDS
        $this->ssl = true; // USE SSL?
    }
    
	/**
     * Generates a cache path for the given URL using its MD5 hash.
     *
     * @param string $url The URL to generate the cache path for.
     * @return string The cache path for the given URL.
     */
    private function generate_cache_path($url)
    {
        $url_hash = hash('md5', $url);
        return  $this->cache_dir . '/' . $url_hash;
    }

    /**
     * Checks whether or not the cache for the given URL is up-to-date based on its cache time.
     *
     * @param string $url The URL to check the cache for.
     * @return bool Whether or not the cache for the given URL is up-to-date.
     */
    private function is_cache_up2date($url)
    {

        $cache_time = $this->cache_time;

        if (strpos($url, 'get-image') !== false) {
            $cache_time = $this->image_cache_time;
        }

        if ((strpos($url, 'user_') !== false) and (strpos($url, 'get-image') !== false)) {
            $cache_time = $this->profile_image_cache_time;
        }

        $file = $this->generate_cache_path($url);

        if ((file_exists($file)) and (!(time() - filemtime($file) > 1 * $cache_time))) {
            return true;
        }
        return false;
    }

    /**
     * Writes the given content to a file at the cache path for the given URL.
     *
     * @param string $url The URL to write the cache for.
     * @param string $content The content to write to the cache.
     */
    private function write_cache($url, $content)
    {
        $file = $this->generate_cache_path($url);
        file_put_contents($file, $content);
    }

    /**
     * Sends a GET request to the given URL with the Authorization header set to the API key.
     *
     * @param string $url The URL to send the request to.
     * @return string The response content.
     */
    private function get($url,$user_auth_header = false)
    {

        if ($this->is_cache_up2date($url)) {
            $file = $this->generate_cache_path($url);
            return file_get_contents($file);
        }

        $options = array(
            'http' => array(
                'method'  => 'GET',
                'header' => 'Authorization: Bearer ' . $this->api_key,
                'ignore_errors' => true 
            ),
            "ssl" => array(
                "verify_peer" => $this->ssl,
                "verify_peer_name" => $this->ssl,
            )
        );

        if($user_auth_header == True){
            $options['http']['header'] .= "\r\n" . 'UserAuthData:' . base64_encode(json_encode($_SESSION['auth_data']));
        }


        $context  = stream_context_create($options);

        $response = file_get_contents($url, false, $context);
        

        if ($http_response_header[0] == "HTTP/1.1 200 OK") {
            $this->write_cache($url, $response);
            return $response;
        } else {
            $file = $this->generate_cache_path($url);
            return file_get_contents($file);
        }

    }

    /**
     * Retrieves a list of all posts from the SK-API.
     *
     * @return string The response content.
     */
    public function get_posts()
    {
        return $this->get($this->base_url . 'get-posts');
    }

    /**
     * Retrieves a single post from the SK-API with the given ID.
     *
     * @param int $id The ID of the post to retrieve.
     * @return string The response content.
     */
    public function get_post($id)
    {
        return $this->get($this->base_url . 'get-post/' . $id);
    }

    /**
     * Gets the number of all pages from the SK API.
     *
     * @return string The response content.
     */
    public function get_pages()
    {
        return $this->get($this->base_url . 'get-pages');
    }

    /**
     * Retrieves a single page from the SK-API with the given page number.
     *
     * @param int $page_number The page number of the page to retrieve.
     * @return string The response content.
     */
    public function get_page($page_number)
    {
        return $this->get($this->base_url . 'get-page/' . $page_number);
    }

    /**
     * Gets the number of all pages from the SK API with posts containing the search string.
     *
     * @return string The response content.
     */
    public function get_page_search($page_number, $search_term)
    {
        return $this->get($this->base_url . 'get-pages-search/' . $page_number . '/' . urlencode($search_term));
    }

    /**
     * Gets the number of all pages from the SK API with posts containing the tag string.
     *
     * @return string The response content.
     */
    public function get_pages_tag($tag)
    {
        return $this->get($this->base_url . 'get-pages-tag/' .  urlencode($tag));
    }


    /**
     * Gets the number of all pages from the SK API with posts containing the tag string.
     *
     * @return string The response content.
     */
    public function get_page_tag($page_number, $tag)
    {
        return $this->get($this->base_url . 'get-pages-tag/' . $page_number . '/' . urlencode($tag));
    }

    /**
     * Gets the number of all pages from the SK API with posts containing the search string.
     *
     * @return string The response content.
     */
    public function get_pages_search($search_term)
    {
        return $this->get($this->base_url . 'get-pages-search/' .  urlencode($search_term));
    }

    /**
     * Retrieves a list of search results (posts) from the SK-API for the given search term.
     *
     * @param string $search_term The search term to retrieve results for.
     * @return string The response content.
     */
    public function get_search_results($search_term)
    {
        return $this->get($this->base_url . 'get-search-results/' .  urlencode($search_term));
    }
	
    /**
     * Retrieves a list of tags from the SK-API.
     *
     * @return string The response content.
     */
    public function get_tag_list()
    {
        return $this->get($this->base_url . 'get-tag-list');
    }
    
    /**
     * Retrieves a list of tags from the SK-API.
     *
     * @return string The response content.
     */
    public function get_tag_list_numbered()
    {
        return $this->get($this->base_url . 'get-tag-list-numbered');
    }
	
     /**
     * Retrieves a list of results (posts) from the SK-API for the given tag term.
     *
     * @param string $tag The search tag to retrieve results for.
     * @return string The response content.
     */   
    public function get_posts_by_tag($tag)
    {
        return $this->get($this->base_url . 'get-posts-with-tag/' . urlencode($tag));
    }
	
     /**
     * Retrieves a image file from the SK-API for the given tag term.
     *
     * @param string $file_unique_id The filename of the image.
     * @return binary The response image.
     */ 
    public function get_image($file_unique_id)
    {
        return $this->get($this->base_url . 'get-image/' . $file_unique_id);
    }

     /**
     * Retrieves a current Satoshi Values for EUR.
     *
     * @param integer $sats The amount of sats to convert.
     * @return string The response content.
     */ 
    public function get_sats_to_fiat($sats)
    {
        return $this->get($this->base_url . 'sats-to-fiat/' . $sats);
    }

     /**
     * Retrieves a current BTC price in EUR.
     *
     * @return string The response content.
     */ 
    public function get_btc_in_eur()
    {
        return $this->get($this->base_url . 'btc-price/eur');
    }
    /**
     * Retrieves a current BTC price in EUR.
     *
     * @return string The response content.
     */ 
    public function check_tg_user_auth($auth_data)
    {
        $url = $this->base_url . 'tg_auth';

        $postdata = http_build_query($auth_data);

        $opts = array('http' =>
            array(
                "method"  => "POST",
                "header"  => "Content-type: application/x-www-form-urlencoded\r\n" .
                             "Authorization: Bearer " . $this->api_key,
                'content' => $postdata
            ),
            "ssl" => array(
                "verify_peer" => $this->ssl,
                "verify_peer_name" => $this->ssl,
            )
        );
        
    
        $context = stream_context_create($opts);
        return file_get_contents($url, false, $context);

    }
    /**
     * Retrieves a list of Items belong to a loged in user.
     *
     * @return string The response content with published items.
     */ 
    public function get_user_items_by_tgid()
    {
        return $this->get($this->base_url . 'user/items');
    }

    /**
     * Retrieves a list of Items belong to a user by id.
     *
     * @return string The response content with published and unpublished items.
     */ 
    public function get_user_items()
    {
        
        return $this->get($this->base_url . 'user/private/'.$_SESSION['id'].'/items',$user_auth_header=True);
    }
}
?>