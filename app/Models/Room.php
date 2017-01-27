<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Sofa\Eloquence\Eloquence;
use Sofa\Eloquence\Mappable;
use Sofa\Eloquence\Metable\InvalidMutatorException;

/**
 * Class Room
 * @package App\Models
 */
class Room extends Model
{
    use Eloquence, Mappable;

    /**
     * Disable Timestamps
     *
     * @var bool
     */
    public $timestamps = false;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'rooms';

    /**
     * The attributes that will be mapped
     *
     * @var array
     */
    protected $maps = [
        'uniqueId' => 'id',
        'ownerName' => 'owner_name',
        'ownerUniqueId' => 'owner_id',
        'doorMode' => 'state',
        'leaderboardValue' => 'score',
        'maximumVisitors' => 'users_max',
        'habboGroupId' => 'guild_id',
        'rating' => 'score',
    ];

    /**
     * The Appender(s) of the Model
     *
     * @var array
     */
    protected $appends = [
        'uniqueId',
        'leaderboardRank',
        'thumbnailUrl',
        'imageUrl',
        'leaderboardValue',
        'doorMode',
        'maximumVisitors',
        'publicRoom',
        'ownerUniqueId',
        'ownerName',
        'showOwnerName',
        'categories',
        'rating'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'owner_name',
        'owner_id',
        'is_public',
        'state',
        'password',
        'model',
        'users',
        'users_max',
        'guild_id',
        'category',
        'score',
        'paper_floor',
        'paper_wall',
        'paper_landscape',
        'thickness_wall',
        'wall_height',
        'thickness_floor',
        'moodlight_data',
        'is_staff_picked',
        'allow_other_pets',
        'allow_other_pets_eat',
        'allow_walkthrough',
        'allow_hidewall',
        'chat_mode',
        'chat_weight',
        'chat_speed',
        'chat_hearing_distance',
        'chat_protection',
        'override_model',
        'who_can_mute',
        'who_can_kick',
        'who_can_ban',
        'poll_id',
        'roller_speed',
        'promoted',
        'trade_mode',
        'move_diagonally'
    ];

    /**
     * Get Room Tags
     *
     * @return array
     */
    public function getTagsAttribute()
    {
        return !empty($this->attributes['tags']) ? explode(';', $this->attributes['tags']) : [];
    }

    /**
     * Get Image Url
     *
     * @TODO: Need Configure for Arcturus Imaging Server
     *
     * @return string
     */
    public function getImageUrlAttribute()
    {
        return 'https://habbo-stories-content.s3.amazonaws.com/fullroom-photo/hhus/' . $this->attributes['id'];
    }

    /**
     * Get Thumbnail Url
     *
     * @TODO: Need Configure for Arcturus Imaging Server
     *
     * @return string
     */
    public function getThumbnailUrlAttribute()
    {
        return 'https://habbo-stories-content.s3.amazonaws.com/navigator-thumbnail/hhus/' . $this->attributes['id'];
    }

    /**
     * Return if need show Owner Name
     *
     * @TODO: What this really does?
     *
     * @return bool
     */
    public function getShowOwnerNameAttribute()
    {
        return true;
    }

    /**
     * Set a Leader Board Position
     *
     * @param int $roomPosition
     */
    public function setLeaderBoardRankAttribute($roomPosition)
    {
        $this->attributes['leaderboardRank'] = $roomPosition;
    }

    /**
     * Get Leader Board Rank
     *
     * @return mixed
     */
    public function getLeaderBoardRankAttribute()
    {
        return $this->attributes['leaderboardRank'];
    }

    /**
     * Get if the Room is Public
     *
     * @return bool
     */
    public function getPublicRoomAttribute()
    {
        return $this->attributes['is_public'] == 1;
    }

    /**
     * Get Room Category
     *
     * @return array
     */
    public function getCategoriesAttribute()
    {
        $roomCategory = DB::table('navigator_flatcats')->where('id', $this->attributes['category'])->first();

        $roomCategory = str_replace('}', '', str_replace('${', '', $roomCategory->caption));

        return [$roomCategory];
    }

    /**
     * Store Function
     *
     * A Room can't be inserted by the CMS.
     * Only by the Emulator
     */
    public function store()
    {
        throw new InvalidMutatorException("You cannot store a Room by AzureWEB. Rooms need be created from the Server.");
    }
}