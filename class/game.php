<?php

/**
 * Copyright (c) 2018, Justin Back <jb@justinback.com>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Steam game managing.
 * HUB for interacting with other classes and supplying variables automaticly.
 *
 * @author Justin Back <jb@justinback.com>
 */
class game {
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
    * Construction of the variables steamid, key and game
    *
    *
    * @param string $sApiKey Steamworks Developer API Key
    * @param string $iGame Your Appid
    * @param string $sSteamid The SteamID of the user 
    *
    * @return void
    */
    public function __construct($sApiKey = null, $iGame = null, $sSteamid = null)
    {
        $this->set_key($sApiKey);
        $this->set_game((int)$iGame);
        $this->set_steamid($sSteamid);
        
    }
    
    /**
    * Setting API Key from the construct
    *
    *
    * @param string $sApiKey Steamworks Developer API Key
    *
    * @return void
    */
    private function set_key($sApiKey)
    {
        $this->key = $sApiKey;
    }
    
    
    /**
    * Setting AppID from the construct
    *
    *
    * @param string $iGame Your AppID
    *
    * @return void
    */
    private function set_game($iGame)
    {
        $this->game = $iGame;
    }
    
    
    /**
    * Setting SteamID from the construct
    *
    *
    * @param string $sSteamid The Players SteamID
    *
    * @return void
    */
    private function set_steamid($sSteamid)
    {
        $this->steamid = $sSteamid;
    }
    
    
    
    /**
    * Check wether the user owns your game or not
    *
    *
    *
    * @return bool
    */
    public function CheckAppOwnership()
    {
        $req_owner = file_get_contents("https://api.steampowered.com/ISteamUser/GetPublisherAppOwnership/v2?key=".$this->key."&steamid=".$this->steamid);
        $appownership = json_decode($req_owner);
        
        foreach ($appownership->appownership->apps as $app)
            {
                if($app->appid == $this->game){
                    return (bool)$app->ownsapp;
                }
            }
    }
    
    /**
    * Get the current number of players of your app
    *
    *
    *
    * @return int
    */
    public function GetNumberOfCurrentPlayers(){
        $req_players = file_get_contents("https://api.steampowered.com/ISteamUserStats/GetNumberOfCurrentPlayers/v1?key=".$this->key."&appid=".(int)$this->game);
        $GetNumberOfCurrentPlayers = json_decode($req_players);
        return $GetNumberOfCurrentPlayers->response->player_count;
    }
    
    /**
    * achievements object.
    *
    * @param string $sApiKey (optional) set a different apikey than the construct
    * @param string $iGame (optional) set a different appid than the construct
    * @param string $sSteamid (optional) set a different steamid than the construct
    * 
    * @return achievements
    */
    public function achievements($sApiKey = null, $iGame = null, $sSteamid = null)
    {
        if($sApiKey === null){
            $sApiKey = $this->key;
        }
        if($iGame === null){
            $iGame = $this->game;
        }
        if($sSteamid === null){
            $sSteamid = $this->steamid;
        }
        return new \justinback\steam\achievements($sApiKey,$iGame,$sSteamid);
    }
    
    
    /**
    * leaderboards object.
    *
    * @param string $sApiKey (optional) set a different apikey than the construct
    * @param string $iGame (optional) set a different appid than the construct
    * @param string $sSteamid (optional) set a different steamid than the construct
    * 
    * @return leaderboards
    */
    public function leaderboards($sApiKey = null, $iGame = null, $sSteamid = null)
    {
        if($sApiKey === null){
            $sApiKey = $this->key;
        }
        if($iGame === null){
            $iGame = $this->game;
        }
        if($sSteamid === null){
            $sSteamid = $this->steamid;
        }
        return new \justinback\steam\leaderboards($sApiKey,$iGame,$sSteamid);
    }
    
    
    /**
    * ugc object.
    *
    * @param string $publishedfileid (optional) set a different publishedfileid than the construct
    * @param string $sApiKey (optional) set a different apikey than the construct
    * @param string $iGame (optional) set a different appid than the construct
    * @param string $sSteamid (optional) set a different steamid than the construct
    * 
    * @return ugc
    */
    public function ugc($publishedfileid, $sApiKey = null, $iGame = null, $sSteamid = null)
    {
        if($sApiKey === null){
            $sApiKey = $this->key;
        }
        if($iGame === null){
            $iGame = $this->game;
        }
        if($sSteamid === null){
            $sSteamid = $this->steamid;
        }
        return new \justinback\steam\ugc($publishedfileid, $sApiKey,$iGame,$sSteamid);
    }
    
    
    /**
    * gameserver object.
    *
    * @param string $sApiKey (optional) set a different apikey than the construct
    * @param string $iGame (optional) set a different appid than the construct
    * @param string $sSteamid (optional) set a different steamid than the construct
    * 
    * @return gameserver
    */
    public function gameserver($sApiKey = null, $iGame = null, $sSteamid = null)
    {
        if($sApiKey === null){
            $sApiKey = $this->key;
        }
        if($iGame === null){
            $iGame = $this->game;
        }
        if($sSteamid === null){
            $sSteamid = $this->steamid;
        }
        return new \justinback\steam\gameserver($sApiKey,$iGame,$sSteamid);
    }
   
    
    /**
    * store object.
    *
    * @param string $iGame (optional) set a different appid than the construct
    * 
    * @return store
    */
    public function store($iGame = null)
    {
        if($iGame === null){
            $iGame = $this->game;
        }
        
        $req_players = file_get_contents("https://store.steampowered.com/api/appdetails?appids=".(int)$iGame);
        $GetNumberOfCurrentPlayers = json_decode($req_players);
        $store = $GetNumberOfCurrentPlayers->$iGame->data;
        
        if($GetNumberOfCurrentPlayers->$iGame->success){
        return new \justinback\steam\store($iGame, $store->name, $store->type, $store->required_age, $store->is_free, $store->detailed_description, $store->about_the_game, $store->short_description, $store->developers, $store->publishers, $store->dlc);
        }
        return false;
    }
    
    
}
