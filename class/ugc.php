<?php

/**
 * Copyright (c) 2018, Justin Back <jb@justinback.com>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Steam User Generated Content (UGC) managing
 *
 * @author Justin Back <jb@justinback.com>
 */
class ugc {
    /**
    * Steamworks API Key
    *
    */
    private $key = null;
    
    /**
    * Steamworks App ID
    *
    */
    private $game = null;
    
    /**
    * SteamID of user
    *
    */
    private $steamid = null;
    
    /**
    * ID of Published File
    *
    */
    private $fileid = null;
    
    /**
    * Construction of the variables steamid, key and game
    *
    *
    * @param string $publishedfileid UGC File ID
    * @param string $apikey Steamworks Developer API Key
    * @param string $game Your Appid
    * @param string $steamid The SteamID of the user 
    *
    * @return void
    */
    public function __construct($publishedfileid = null, $apikey = null, $game = null, $steamid = null)
    {
        $this->set_key($apikey);
        $this->set_game((int)$game);
        $this->set_steamid($steamid);
        $this->set_fileid($publishedfileid);
        
    }
    
    /**
    * Setting API Key from the construct
    *
    *
    * @param string $apikey Steamworks Developer API Key
    *
    * @return void
    */
    private function set_key($apikey)
    {
        $this->key = $apikey;
    }
    
    
    /**
    * Setting AppID from the construct
    *
    *
    * @param string $game Your AppID
    *
    * @return void
    */
    private function set_game($game)
    {
        $this->game = $game;
    }
    
    
    /**
    * Setting SteamID from the construct
    *
    *
    * @param string $steamid The Players SteamID
    *
    * @return void
    */
    private function set_steamid($steamid)
    {
        $this->steamid = $steamid;
    }
    
    
    /**
    * Setting File ID from the construct
    *
    *
    * @param string $publishedfileid The File's ID
    *
    * @return void
    */
    private function set_fileid($publishedfileid)
    {
        $this->fileid = $publishedfileid;
    }
    
   
    
    /**
    * Update Ban Status of a UGC
    *
    * @param bool $banned Is banned or not
    * @param string $reason Reason why the item was banned. Only visible to admins.
    * @param string $publishedfileid (optional) numeric ID of the target file. 
    * 
    * 
    * @return bool
    */
    public function UpdateBanStatus($banned, $reason, $publishedfileid = null){
        if($publishedfileid == null){
            $publishedfileid = $this->fileid;
        }
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'publishedfileid' => $publishedfileid, 'banned' => $banned, 'reason' => $reason))
            )
        );
        $context  = stream_context_create($options);
        $req_players = file_get_contents("https://partner.steam-api.com/IPublishedFileService/UpdateBanStatus/v1/", false, $context);
        //$response = json_decode($req_players);
       
        
        return true;
    }
    
    
    /**
    * Updates the incompatible status on the published file. "Incompatible" items are hidden from community hubs and user profiles, but can still be accessed by a direct link.
    *
    *
    * @param bool $incompatible Is banned or not
    * @param string $publishedfileid (optional) numeric ID of the target file. 
    *
    * 
    * @return bool
    */
    public function UpdateIncompatibleStatus($incompatible, $publishedfileid = null){
        if($publishedfileid == null){
            $publishedfileid = $this->fileid;
        }
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'publishedfileid' => $publishedfileid, 'incompatible' => $incompatible))
            )
        );
        $context  = stream_context_create($options);
        $req_players = file_get_contents("https://partner.steam-api.com/IPublishedFileService/UpdateIncompatibleStatus/v1/", false, $context);
        //$response = json_decode($req_players);
       
        
        return true;
    }
    
    
    /**
    * Updates tags on the published file
    *
    *
    * 
    * @param array $add_tags Tags array("Maps", "Another Tag")
    * @param array $remove_tags Tags array("Tag", "Another Tag")
    * @param string $publishedfileid (optional) numeric ID of the target file.  
    * 
    * @return bool
    */
    public function UpdateTags($add_tags, $remove_tags, $publishedfileid = null){
        if($publishedfileid == null){
            $publishedfileid = $this->fileid;
        }
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'publishedfileid' => $publishedfileid, 'add_tags' => $add_tags, 'remove_tags' => $remove_tags))
            )
        );
        $context  = stream_context_create($options);
        $req_players = file_get_contents("https://partner.steam-api.com/IPublishedFileService/UpdateTags/v1/", false, $context);
        //$response = json_decode($req_players);
       
        
        return true;
    }
    
    
    
    /**
    * Get File Info
    *
    *
    * @param string $publishedfileids (optional) numeric ID of the target file. 
    * 
    * @return object
    */
    public function GetPublishedFileDetails($publishedfileids = null){
        if($publishedfileids == null){
            $publishedfileids = $this->fileid;
        }
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'publishedfileids[0]' => $publishedfileids, "itemcount" => 1))
            )
        );
        $context  = stream_context_create($options);
        $req_players = file_get_contents("https://partner.steam-api.com/ISteamRemoteStorage/GetPublishedFileDetails/v1/", false, $context);
        $response = json_decode($req_players);
       
        foreach($response->response->publishedfiledetails as $ugc){
         return $ugc;   
        }
    }
    
    
    /**
    * Subscribe to the published file as the user
    *
    *
    * @param string $publishedfileid (optional) numeric ID of the target file.
    * @param string $steamid (optional) numeric ID of the user.  
    * 
    * @return bool
    */
    public function SubscribePublishedFile($publishedfileid = null, $steamid = null){
        if($publishedfileid == null){
            $publishedfileid = $this->fileid;
        }
        if($steamid == null){
            $steamid = $this->steamid;
        }
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'publishedfileid' => $publishedfileid, "steamid" => $steamid))
            )
        );
        $context  = stream_context_create($options);
        $req_players = file_get_contents("https://partner.steam-api.com/IPublishedFileService/SubscribePublishedFile/v1/", false, $context);
        //$response = json_decode($req_players);
       
        
        return true;
    }
    
    
    /**
    * Unsubscribe from the published file as the user
    *
    *
    * @param string $publishedfileid (optional) numeric ID of the target file.
    * @param string $steamid (optional) numeric ID of the user. 
    * 
    * @return bool
    */
    public function UnsubscribePublishedFile($publishedfileid = null, $steamid = null){
        if($publishedfileid == null){
            $publishedfileid = $this->fileid;
        }
        if($steamid == null){
            $steamid = $this->steamid;
        }
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'publishedfileid' => $publishedfileid, "steamid" => $steamid))
            )
        );
        $context  = stream_context_create($options);
        $req_players = file_get_contents("https://partner.steam-api.com/IPublishedFileService/UnsubscribePublishedFile/v1/", false, $context);
        //$response = json_decode($req_players);
       
        
        return true;
    }
    
    /**
    * Get Creator by UGC
    *
    *
    * @param string $publishedfileids (optional) numeric ID of the target file. 
    * 
    * @return player
    */
    public function player($publishedfileids = null){
        if($publishedfileids == null){
            $publishedfileids = $this->fileid;
        }
        $options = array(
            'http' => array(
                'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
                'method'  => 'POST',
                'content' => http_build_query(array('key' => $this->key, 'appid' => (int)$this->game, 'publishedfileids[0]' => $publishedfileids, "itemcount" => 1))
            )
        );
        $context  = stream_context_create($options);
        $req_players = file_get_contents("https://partner.steam-api.com/ISteamRemoteStorage/GetPublishedFileDetails/v1/", false, $context);
        $response = json_decode($req_players);
       
        foreach($response->response->publishedfiledetails as $ugc){
         return new \justinback\steam\player($this->key, $this->game, $ugc->creator);   
        }
    }
    
    
    
}