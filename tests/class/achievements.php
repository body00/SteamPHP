<?php

/**
 * Copyright (c) 2018, Justin Back <jb@justinback.com>
 * All rights reserved.
 */

namespace justinback\steam;

/**
 * Manage Steam Achievements.
 * 
 * @todo UnlockAchievement();
 * @todo LockAchievement();
 *
 * @author Justin Back <jb@justinback.com>
 */
class achievements extends PHPUnit_Framework_TestCase {
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
    * @param string $apikey Steamworks Developer API Key
    * @param string $game Your Appid
    * @param string $steamid The SteamID of the user 
    * 
    * @example
    * <code>
    * $achievements = $steam->game()->achievements();
    * </code>
    *
    * @return void
    */
    public function __construct($apikey = null, $game = null, $steamid = null)
    {
        $this->set_key($apikey);
        $this->set_game((int)$game);
        $this->set_steamid($steamid);
        
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
    * Retrieve unlocked achievements by the player
    *
    *
    * 
    * 
    * @example
    * <code>
    * $achievements = $steam->game()->achievements();
    * $array = $achievements->GetPlayerAchievements();
    * </code> 
    *
    * @return array
    */
    public function GetPlayerAchievements()
    {
        $req_achievement = file_get_contents("https://api.steampowered.com/ISteamUserStats/GetPlayerAchievements/v1?key=".$this->key."&steamid=".$this->steamid."&appid=".(int)$this->game);
        $playerstats = json_decode($req_achievement);
        
        
        if($this->game()->CheckAppOwnership()){
            $achievements = array_filter($playerstats->playerstats->achievements, function($item) {
                    return $item->achieved == 1;
            });
            return $achievements;
        }
        return false;
    }
    
    
    /**
    * Return only locked achievements by the player
    *
    * @example
    * <code>
    * $achievements = $steam->game()->achievements();
    * $array = $achievements->GetPlayerAchievementsLocked();
    * </code> 
    *
    * @return array
    */
    public function GetPlayerAchievementsLocked()
    {
        $req_achievement = file_get_contents("https://api.steampowered.com/ISteamUserStats/GetPlayerAchievements/v1?key=".$this->key."&steamid=".$this->steamid."&appid=".(int)$this->game);
        $playerstats = json_decode($req_achievement);
        
        
        if($this->game()->ownership()){
            $achievements = array_filter($playerstats->playerstats->achievements, function($item) {
                    return $item->achieved == 0;
            });
            return $achievements;
        }
        return false;
    }
    
    
    /**
    * Setting SteamID from the construct
    *
    *
    * @param string $apiname APIName of the achievement (not visible name)
    *
    * 
    * @example
    * <code>
    * $achievements = $steam->game()->achievements();
    * $object = $achievements->GetAchievementDetails();
    * </code> 
    * 
    * @return object
    */
    public function GetAchievementDetails($apiname){
        $req_achievement = file_get_contents("https://api.steampowered.com/ISteamUserStats/GetSchemaForGame/v2?key=".$this->key."&appid=".(int)$this->game);
        $availableGameStats = json_decode($req_achievement);
        foreach($availableGameStats->game->availableGameStats->achievements as $achievement){
            if($achievement->name == $apiname){
                return $achievement;
            }
        }
    }
    
    /*public function UnlockAchievement($apiname){
        $req_achievement = file_get_contents("https://api.steampowered.com/ISteamUserStats/SetUserStatsForGame/v1?key=".$this->key."&appid=".(int)$this->game);
        $availableGameStats = json_decode($req_achievement);
        foreach($availableGameStats->game->availableGameStats->achievements as $achievement){
            if($achievement->name == $apiname){
                return $achievement;
            }
        }
    }*/
    
    /**
    * Check if player has unlocked the specified achievement
    *
    *
    * @param string $apiname APIName of the achievement (not visible name)
    *
    * @example
    * <code>
    * $achievements = $steam->game()->achievements();
    * $bool = $achievements->HasPlayerUnlockedAchievement();
    * </code> 
    * 
    * 
    * @return bool
    */
    public function HasPlayerUnlockedAchievement($apiname){
            foreach($this->GetPlayerAchievements() as $userachievement){
                if($userachievement->apiname == $apiname){
                    return true;
                }
                return false;
            }
    }
    
    
    
    /**
    * game object.
    *
    * @param string $apikey (optional) set a different apikey than the construct
    * @param string $game (optional) set a different appid than the construct
    * @param string $steamid (optional) set a different steamid than the construct
    * 
    * @return game
    */
    public function game($apikey = null, $game = null, $steamid = null)
    {
        if($apikey === null){
            $apikey = $this->key;
        }
        if($game === null){
            $game = $this->game;
        }
        if($steamid === null){
            $steamid = $this->steamid;
        }
        return new \justinback\steam\game($apikey,$game,$steamid);
    }
}