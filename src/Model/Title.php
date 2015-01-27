<?php

/**
 * Deep
 *
 * @package      rsanchez\Deep
 * @author       Rob Sanchez <info@robsanchez.com>
 */

namespace rsanchez\Deep\Model;

use Illuminate\Database\Eloquent\Builder;
use rsanchez\Deep\Hydrator\HydratorCollection;
use rsanchez\Deep\Hydrator\DehydratorCollection;
use rsanchez\Deep\Repository\ChannelRepository;
use rsanchez\Deep\Repository\SiteRepository;
use rsanchez\Deep\Collection\TitleCollection;
use rsanchez\Deep\Collection\AbstractTitleCollection;
use rsanchez\Deep\Collection\AbstractModelCollection;
use rsanchez\Deep\Collection\PropertyCollection;
use rsanchez\Deep\Hydrator\HydratorFactory;
use rsanchez\Deep\Hydrator\DehydratorInterface;
use rsanchez\Deep\Relations\HasOneFromRepository;
use rsanchez\Deep\Validation\ValidatableInterface;
use rsanchez\Deep\Validation\Factory as ValidatorFactory;
use rsanchez\Deep\Model\StringableInterface;
use rsanchez\Deep\Model\PropertyInterface;
use Carbon\Carbon;
use Closure;
use DateTime;

/**
 * Model for the channel_titles table
 */
class Title extends AbstractEntity
{
    use JoinableTrait, GlobalAttributeVisibilityTrait, HasChannelRepositoryTrait, HasSiteRepositoryTrait;

    /**
     * {@inheritdoc}
     *
     * @var string
     */
    protected $table = 'channel_titles';

    /**
     * {@inheritdoc}
     *
     * @var string
     */
    protected $primaryKey = 'entry_id';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected static $globalHidden = [
        'chan',
        'site_id',
        'forum_topic_id',
        'ip_address',
        'versioning_enabled',
        'comments',
    ];

    /**
     * @var \rsanchez\Deep\Model\ChannelData
     */
    protected $channelData;

    /**
     * The attributes that should be visible in arrays.
     *
     * @var array
     */
    protected static $globalVisible = [];

    /**
     * The class used when creating a new Collection
     * @var string
     */
    protected $collectionClass = '\\rsanchez\\Deep\\Collection\\TitleCollection';

    /**
     * List of extra hydrators to load (e.g. parents or siblings)
     * @var array
     */
    protected $extraHydrators = array();

    /**
     * When extending this class, set this property to automatically
     * load from the specified channel
     * @var string|null
     */
    protected $defaultChannelName;

    /**
     * {@inheritdoc}
     */
    protected $customFieldAttributesRegex = '/^field_(id|dt|ft)_\d+$/';

    /**
     * {@inheritdoc}
     */
    protected $hiddenAttributesRegex = '/^field_(id|dt|ft)_\d+$/';

    /**
     * {@inheritdoc}
     */
    const CREATED_AT = 'entry_date';

    /**
     * {@inheritdoc}
     */
    const UPDATED_AT = 'edit_date';

    /**
     * {@inheritdoc}
     */
    public $timestamps = true;

    /**
     * {@inheritdoc}
     */
    protected $attributes = [
        'view_count_one' => 0,
        'view_count_two' => 0,
        'view_count_three' => 0,
        'view_count_four' => 0,
        'site_id' => 1,
        'versioning_enabled' => 'n',
        'allow_comments' => 'y',
        'sticky' => 'n',
        'comment_total' => 0,
    ];

    /**
     * {@inheritdoc}
     */
    protected $rules = [
        'site_id' => 'required|exists:sites,site_id',
        'channel_id' => 'required|exists:channels,channel_id',
        'author_id' => 'required|exists:members,member_id',
        'forum_topic_id' => 'exists:forum_topics,forum_topic_id',
        'ip_address' => 'ip',
        'title' => 'required',
        'url_title' => 'required|alpha_dash|unique:channel_titles,url_title',
        'status' => 'required',
        'versioning_enabled' => 'required|yes_or_no',
        'view_count_one' => 'required|integer',
        'view_count_two' => 'required|integer',
        'view_count_three' => 'required|integer',
        'view_count_four' => 'required|integer',
        'allow_comments' => 'required|yes_or_no',
        'sticky' => 'required|yes_or_no',
        'entry_date' => 'date_format:U',
        'year' => 'integer',
        'month' => 'digits:2',
        'day' => 'digits:2',
        'expiration_date' => 'date_format:U',
        'comment_expiration_date' => 'date_format:U',
        'edit_date' => 'date_format:YmdHis',
        'recent_comment_date' => 'date_format:U',
        'comment_total' => 'required|integer',
    ];

    /**
     * {@inheritdoc}
     */
    protected $attributeNames = [
        'site_id' => 'Site ID',
        'channel_id' => 'Channel ID',
        'author_id' => 'Author ID',
        'forum_topic_id' => 'Forum Topic ID',
        'ip_address' => 'IP Address',
        'title' => 'Title',
        'url_title' => 'URL Title',
        'status' => 'Status',
        'versioning_enabled' => 'Versioning Enabled',
        'view_count_one' => 'View Count One',
        'view_count_two' => 'View Count Two',
        'view_count_three' => 'View Count Three',
        'view_count_four' => 'View Count Four',
        'allow_comments' => 'Allow Comments',
        'sticky' => 'Sticky',
        'entry_date' => 'Entry Date',
        'year' => 'Year',
        'month' => 'Month',
        'day' => 'Day',
        'expiration_date' => 'Expiration Date',
        'comment_expiration_date' => 'Comment Expiration Date',
        'edit_date' => 'Edit Date',
        'recent_comment_date' => 'Recent Comment Date',
        'comment_total' => 'Comment Total',
    ];

    /**
     * Define the Author Eloquent relationship
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function author()
    {
        return $this->hasOne('\\rsanchez\\Deep\\Model\\Member', 'member_id', 'author_id');
    }

    /**
     * Define the Categories Eloquent relationship
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function categories()
    {
        return $this->hasManyThrough('\\rsanchez\\Deep\\Model\\Category', '\\rsanchez\\Deep\\Model\\CategoryPosts', 'entry_id', 'cat_id');
    }

    /**
     * Define the Channel Eloquent relationship
     * @return \rsanchez\Deep\Relations\HasOneFromRepository
     */
    public function chan()
    {
        return new HasOneFromRepository(
            static::getChannelRepository()->getModel()->newQuery(),
            $this,
            'channels.channel_id',
            'channel_id',
            static::getChannelRepository()
        );
    }

    /**
     * Define the Comments Eloquent relationship
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function comments()
    {
        return $this->hasMany('\\rsanchez\\Deep\\Model\\Comment', 'entry_id', 'entry_id');
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->entry_id;
    }

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'entry';
    }

    /**
     * Alias chan to channel
     * @var \rsanchez\Deep\Model\Channel
     */
    public function getChannelAttribute()
    {
        return $this->chan;
    }

    /**
     * {@inheritdoc}
     */
    public function getProperties()
    {
        return new PropertyCollection();
    }

    /**
     * Set the channel_id attribute for this entry
     * @param $channelId
     */
    public function setChannelIdAttribute($channelId)
    {
        $this->setChannel(static::getChannelRepository()->find($channelId));
    }

    /**
     * {@inheritdoc}
     */
    public function setRawAttributes(array $attributes, $sync = false)
    {
        $this->channelData = $this->newChannelData();

        $channelDataAttributes = [];

        if ($this->customFieldAttributesRegex) {
            foreach ($attributes as $key => $value) {
                if (preg_match($this->customFieldAttributesRegex, $key)) {
                    $channelDataAttributes[$key] = $value;

                    unset($attributes[$key]);
                } elseif ($key === 'entry_id' || $key === 'site_id' || $key === 'channel_id') {
                    $channelDataAttributes[$key] = $value;
                }
            }
        }

        $this->attributes = $attributes;

        if ($sync) {
            $this->syncOriginal();
        }

        $this->channelData->setRawAttributes($channelDataAttributes, $sync);
    }

    /**
     * {@inheritdoc}
     */
    public function getDateFormat()
    {
        return 'U';
    }

    /**
     * Set the Channel model for this entry
     * @param Channel $channel
     */
    public function setChannel(Channel $channel)
    {
        $this->relations['chan'] = $channel;

        $this->attributes['channel_id'] = $channel->channel_id;

        $this->setDehydrators($this->getHydratorFactory()->getDehydrators($channel->fields));

        $this->hydrateDefaultProperties();
    }

    /**
     * {@inheritdoc}
     */
    protected static function joinTables()
    {
        return array(
            'members' => function ($query) {
                $query->join('members', 'members.member_id', '=', 'channel_titles.author_id');
            },
            'category_posts' => function ($query) {
                $query->join('category_posts', 'category_posts.entry_id', '=', 'channel_titles.entry_id');
            },
        );
    }

    /**
     * {@inheritdoc}
     *
     * Joins with the channel data table, and eager load channels, fields and fieldtypes
     *
     * @param  boolean                               $excludeDeleted
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function newQuery($excludeDeleted = true)
    {
        $query = parent::newQuery($excludeDeleted);

        if ($this->defaultChannelName) {
            $this->scopeChannel($query, $this->defaultChannelName);
        }

        return $query;
    }

    /**
     * {@inheritdoc}
     *
     * Hydrate the collection after creation
     *
     * @param  array                                     $models
     * @return \rsanchez\Deep\Collection\TitleCollection
     */
    public function newCollection(array $models = array())
    {
        $method = "{$this->collectionClass}::create";

        $collection = call_user_func($method, $models, static::getChannelRepository());

        if ($models) {
            $this->hydrateCollection($collection);
        }

        return $collection;
    }

    /**
     * Loop through all the hydrators to set Entry custom field attributes
     * @param  \rsanchez\Deep\Collection\TitleCollection $collection
     * @return void
     */
    public function hydrateCollection(AbstractTitleCollection $collection)
    {
        $hydrators = static::getHydratorFactory()->getHydratorsForCollection($collection, $this->extraHydrators);
        $dehydrators = static::getHydratorFactory()->getDehydratorsForCollection($collection);

        // loop through the hydrators for preloading
        foreach ($hydrators as $hydrator) {
            $hydrator->preload($collection);
        }

        // loop again to actually hydrate
        foreach ($collection as $entry) {
            $entry->setHydrators($hydrators);

            $entry->setDehydrators($dehydrators);

            foreach ($entry->channel->fields as $field) {
                $hydrator = $hydrators->get($field->getType());

                if ($hydrator) {
                    $value = $hydrator->hydrate($entry, $field);
                } else {
                    $value = $entry->{$field->getIdentifier()};
                }

                $entry->setCustomField($field->getName(), $value);
            }

            foreach ($this->extraHydrators as $name) {
                $entry->setCustomField($name, $hydrators[$name]->hydrate($entry, new NullProperty()));
            }
        }
    }

    /**
     * {@inheritdoc}
     */
    public function attributesToArray()
    {
        $attributes = parent::attributesToArray();

        $dateAttributes = ['edit_date', 'expiration_date', 'comment_expiration_date', 'recent_comment_date'];

        foreach ($dateAttributes as $key) {
            if (isset($attributes[$key]) && $attributes[$key] instanceof Carbon) {
                $attributes[$key] = (string) $attributes[$key];
            }
        }

        return $attributes;
    }

    /**
     * Set the entry_date column
     * @param  DateTime|string|int $date
     * @return void
     */
    public function setEntryDateAttribute($date)
    {
        $this->attributes['entry_date'] = $date instanceof DateTime ? $date->format('U') : $date;

        if (! $date instanceof DateTime) {
            $date = Carbon::createFromFormat('U', $date);
        }

        $this->attributes['year'] = $date->format('Y');
        $this->attributes['month'] = $date->format('m');
        $this->attributes['day'] = $date->format('d');
    }

    /**
     * Get the expiration_date column as a Carbon object, or null if there is no expiration date
     *
     * @param  int                 $value unix time
     * @return \Carbon\Carbon|null
     */
    public function getExpirationDateAttribute($value)
    {
        return $value ? Carbon::createFromFormat('U', $value) : null;
    }

    /**
     * Set the expiration_date column
     * @param  DateTime|string|int|null $date
     * @return void
     */
    public function setExpirationDateAttribute($date)
    {
        $this->attributes['expiration_date'] = $date instanceof DateTime ? $date->format('U') : $date;
    }

    /**
     * Get the comment_expiration_date column as a Carbon object, or null if there is no expiration date
     *
     * @param  int                 $value unix time
     * @return \Carbon\Carbon|null
     */
    public function getCommentExpirationDateAttribute($value)
    {
        return $value ? Carbon::createFromFormat('U', $value) : null;
    }

    /**
     * Set the comment_expiration_date column
     * @param  DateTime|string|int|null $date
     * @return void
     */
    public function setCommentExpirationDateAttribute($date)
    {
        $this->attributes['comment_expiration_date'] = $date instanceof DateTime ? $date->format('U') : $date;
    }

    /**
     * Get the recent_comment_date column as a Carbon object, or null if there is no expiration date
     *
     * @param  int                 $value unix time
     * @return \Carbon\Carbon|null
     */
    public function getRecentCommentDateAttribute($value)
    {
        return $value ? Carbon::createFromFormat('U', $value) : null;
    }

    /**
     * Set the recent_comment_date column
     * @param  DateTime|string|int|null $date
     * @return void
     */
    public function setRecentCommentDateAttribute($date)
    {
        $this->attributes['recent_comment_date'] = $date instanceof DateTime ? $date->format('U') : $date;
    }

    /**
     * Get the edit_date column as a Carbon object
     *
     * @param  int            $value unix time
     * @return \Carbon\Carbon
     */
    public function getEditDateAttribute($value)
    {
        return Carbon::createFromFormat('YmdHis', $this->attributes['edit_date']);
    }

    /**
     * {@inheritdoc}
     */
    public function freshTimestampString()
    {
        return $this->freshTimestamp()->format('YmdHis');
    }

    /**
     * Set the edit_date column
     * @param  DateTime|string|int $date
     * @return void
     */
    public function setEditDateAttribute($date)
    {
        $this->attributes['edit_date'] = $date instanceof DateTime ? $date->format('YmdHis') : $date;
    }

    /**
     * Get the page_uri of the entry
     *
     * @return string|null
     */
    public function getPageUriAttribute()
    {
        return static::getSiteRepository()->getPageUri($this->entry_id);
    }

    /**
     * Get the channel_name of the entry's channel
     *
     * @return string
     */
    public function getChannelNameAttribute()
    {
        return $this->channel_id ? $this->channel->channel_name : '';
    }

    /**
     * Get the channel_name of the entry's channel
     *
     * @return string
     */
    public function getChannelShortNameAttribute()
    {
        return $this->channel_id ? $this->channel->channel_name : '';
    }

    /**
     * Get the username of the entry's author
     *
     * @return string
     */
    public function getUsernameAttribute()
    {
        return $this->author_id ? $this->author->username : '';
    }

    /**
     * {@inheritdoc}
     */
    public function getUpdateValidationRules(ValidatorFactory $validatorFactory, PropertyInterface $property = null)
    {
        $rules = $this->getDefaultValidationRules($validatorFactory, $property);

        $rules['entry_date'] = 'required|'.$rules['entry_date'];
        $rules['year'] = 'required|'.$rules['year'];
        $rules['month'] = 'required|'.$rules['month'];
        $rules['day'] = 'required|'.$rules['day'];

        $rules['url_title'] .= sprintf(',%s,entry_id', $this->entry_id);

        return $rules;
    }

    /**
     * {@inheritdoc}
     */
    public function getDefaultValidationRules(ValidatorFactory $validatorFactory, PropertyInterface $property = null)
    {
        $rules = $this->rules;

        if ($this->channel_id && $this->channel->status_group) {
            $rules['status'] .= sprintf('|exists:statuses,status,group_id,%s', $this->channel->status_group);
        } else {
            $rules['status'] .= '|in:open,closed';
        }

        return $rules;
    }

    /**
     * Filter by Category ID
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  dynamic  string                       $categoryId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCategory(Builder $query, $categoryId)
    {
        $categoryIds = is_array($categoryId) ? $categoryId : array_slice(func_get_args(), 1);

        return $query->whereHas('categories', function ($q) use ($categoryIds) {
            $q->whereIn('categories.cat_id', $categoryIds);
        });
    }

    /**
     * Get entries that are share one or more categories with the specified entry ID
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  int                                   $entryId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRelatedCategories(Builder $query, $entryId)
    {
        $connection = $query->getQuery()->getConnection();
        $tablePrefix = $connection->getTablePrefix();

        return $this->requireTable($query, 'category_posts')
            ->join($connection->raw("`{$tablePrefix}category_posts` AS `{$tablePrefix}category_posts_2`"), 'category_posts_2.cat_id', '=', 'category_posts.cat_id')
            ->where('category_posts_2.entry_id', $entryId)
            ->where('channel_titles.entry_id', '!=', $entryId)
            ->groupBy('channel_titles.entry_id');
    }

    /**
     * Get entries that are share one or more categories with the specified entry url title
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string                                $urlTitle
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRelatedCategoriesUrlTitle(Builder $query, $urlTitle)
    {
        $connection = $query->getQuery()->getConnection();
        $tablePrefix = $connection->getTablePrefix();

        return $this->requireTable($query, 'category_posts')
            ->join($connection->raw("`{$tablePrefix}category_posts` AS `{$tablePrefix}category_posts_2`"), 'category_posts_2.cat_id', '=', 'category_posts.cat_id')
            ->join($connection->raw("`{$tablePrefix}channel_titles` AS `{$tablePrefix}channel_titles_2`"), 'channel_titles_2.entry_id', '=', 'category_posts_2.entry_id')
            ->where('channel_titles_2.url_title', $urlTitle)
            ->where('channel_titles.url_title', '!=', $urlTitle)
            ->groupBy('channel_titles.entry_id');
    }

    /**
     * Filter out entries without all Category IDs
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  dynamic  string                       $categoryId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAllCategories(Builder $query, $categoryId)
    {
        $categoryIds = is_array($categoryId) ? $categoryId : array_slice(func_get_args(), 1);

        return $query->whereHas('categories', function ($q) use ($categoryIds) {

            $q->where(function ($qq) use ($categoryIds) {
                foreach ($categoryIds as $categoryId) {
                    $qq->orWhere('categories.cat_id', $categoryId);
                }
            });

        }, '>=', count($categoryIds));
    }

    /**
     * Filter out entries without all Category IDs
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  dynamic  string                       $categoryId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotAllCategories(Builder $query, $categoryId)
    {
        $categoryIds = is_array($categoryId) ? $categoryId : array_slice(func_get_args(), 1);

        return $query->whereHas('categories', function ($q) use ($categoryIds) {

            $q->where(function ($qq) use ($categoryIds) {
                foreach ($categoryIds as $categoryId) {
                    $qq->orWhere('categories.cat_id', $categoryId);
                }
            });

        }, '<', count($categoryIds));
    }

    /**
     * Filter by not Category ID
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  dynamic  string                       $categoryId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotCategory(Builder $query, $categoryId)
    {
        $categoryIds = is_array($categoryId) ? $categoryId : array_slice(func_get_args(), 1);

        return $query->whereHas('categories', function ($q) use ($categoryIds) {
            $q->whereIn('categories.cat_id', $categoryIds);
        }, '=', 0);
    }

    /**
     * Filter by Category Name
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  dynamic  string                       $categoryName
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCategoryName(Builder $query, $categoryName)
    {
        $categoryNames = is_array($categoryName) ? $categoryName : array_slice(func_get_args(), 1);

        return $query->whereHas('categories', function ($q) use ($categoryNames) {
            $q->whereIn('categories.cat_url_title', $categoryNames);
        });
    }

    /**
     * Filter by not Category Name
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  dynamic  string                       $categoryName
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotCategoryName(Builder $query, $categoryName)
    {
        $categoryNames = is_array($categoryName) ? $categoryName : array_slice(func_get_args(), 1);

        return $query->whereHas('categories', function ($q) use ($categoryNames) {
            $q->whereIn('categories.cat_url_title', $categoryNames);
        }, '=', 0);
    }

    /**
     * Filter by Category Group
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  dynamic  string                       $groupId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCategoryGroup(Builder $query, $groupId)
    {
        $groupIds = is_array($groupId) ? $groupId : array_slice(func_get_args(), 1);

        return $query->whereHas('categories', function ($q) use ($groupIds) {
            $q->whereIn('categories.group_id', $groupIds);
        });
    }

    /**
     * Filter by Not Category Group
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  dynamic  string                       $groupId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotCategoryGroup(Builder $query, $groupId)
    {
        $groupIds = is_array($groupId) ? $groupId : array_slice(func_get_args(), 1);

        return $query->whereHas('categories', function ($q) use ($groupIds) {
            $q->whereIn('categories.group_id', $groupIds);
        }, '=', 0);
    }

    /**
     * Filter by Channel Name
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  dynamic  string                       $channelName
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeChannel(Builder $query, $channelName)
    {
        $channelNames = is_array($channelName) ? $channelName : array_slice(func_get_args(), 1);

        $channels = static::getChannelRepository()->getChannelsByName($channelNames);

        $channelIds = array();

        $channels->each(function ($channel) use (&$channelIds) {
            $channelIds[] = $channel->channel_id;
        });

        if ($channelIds) {
            array_unshift($channelIds, $query);

            call_user_func_array(array($this, 'scopeChannelId'), $channelIds);
        }

        return $query;
    }

    /**
     * Filter by not Channel Name
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  dynamic  string                       $channelName
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotChannel(Builder $query, $channelName)
    {
        $channelNames = is_array($channelName) ? $channelName : array_slice(func_get_args(), 1);

        $channels = static::getChannelRepository()->getChannelsByName($channelNames);

        $channelIds = array();

        $channels->each(function ($channel) use (&$channelIds) {
            $channelIds[] = $channel->channel_id;
        });

        if ($channelIds) {
            array_unshift($channelIds, $query);

            call_user_func_array(array($this, 'scopeNotChannelId'), $channelIds);
        }

        return $query;
    }

    /**
     * Filter by Channel ID
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  dynamic  int                          $channelId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeChannelId(Builder $query, $channelId)
    {
        $channelIds = is_array($channelId) ? $channelId : array_slice(func_get_args(), 1);

        return $query->whereIn('channel_titles.channel_id', $channelIds);
    }

    /**
     * Filter by not Channel ID
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  dynamic  int                          $channelId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotChannelId(Builder $query, $channelId)
    {
        $channelIds = is_array($channelId) ? $channelId : array_slice(func_get_args(), 1);

        return $query->whereNotIn('channel_titles.channel_id', $channelIds);
    }

    /**
     * Filter by Author ID
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  dynamic  int                          $authorId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAuthorId(Builder $query, $authorId)
    {
        $authorIds = is_array($authorId) ? $authorId : array_slice(func_get_args(), 1);

        return $query->whereIn('channel_titles.author_id', $authorIds);
    }

    /**
     * Filter by not Author ID
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  dynamic  int                          $authorId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotAuthorId(Builder $query, $authorId)
    {
        $authorIds = is_array($authorId) ? $authorId : array_slice(func_get_args(), 1);

        return $query->whereNotIn('channel_titles.author_id', $authorIds);
    }

    /**
     * Filter out Expired Entries
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  bool                                  $showExpired
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeShowExpired(Builder $query, $showExpired = true)
    {
        if (! $showExpired) {
            $query->where(function ($query) {
                return $query->where('expiration_date', '')
                    ->orWhere('expiration_date', '>', time());
            });
        }

        return $query;
    }

    /**
     * Filter out Future Entries
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  bool                                  $showFutureEntries
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeShowFutureEntries(Builder $query, $showFutureEntries = true)
    {
        if (! $showFutureEntries) {
            $query->where('channel_titles.entry_date', '<=', time());
        }

        return $query;
    }

    /**
     * Filter by site ID
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  int                                   $siteId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSiteId(Builder $query, $siteId)
    {
        $siteIds = is_array($siteId) ? $siteId : array_slice(func_get_args(), 1);

        return $query->whereIn('channel_titles.site_id', $siteIds);
    }

    /**
     * Set a Fixed Order
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  dynamic  int                          $fixedOrder
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFixedOrder(Builder $query, $fixedOrder)
    {
        $fixedOrder = is_array($fixedOrder) ? $fixedOrder : array_slice(func_get_args(), 1);

        call_user_func_array(array($this, 'scopeEntryId'), func_get_args());

        return $query->orderBy('FIELD('.implode(', ', $fixedOrder).')', 'asc');
    }

    /**
     * Set Sticky Entries to appear first
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  bool                                  $sticky
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSticky(Builder $query, $sticky = true)
    {
        if ($sticky) {
            $orders = & $query->getQuery()->orders;

            $order = array(
                'column' => 'channel_titles.sticky',
                'direction' => 'desc',
            );

            if ($orders) {
                array_unshift($orders, $order);
            } else {
                $orders = array($order);
            }
        }

        return $query;
    }

    /**
     * Filter by Entry ID
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  dynamic  string                       $entryId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEntryId(Builder $query, $entryId)
    {
        $entryIds = is_array($entryId) ? $entryId : array_slice(func_get_args(), 1);

        return $query->whereIn('channel_titles.entry_id', $entryIds);
    }

    /**
     * Filter by Not Entry ID
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  dynamic  string                       $notEntryId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotEntryId(Builder $query, $notEntryId)
    {
        $notEntryIds = is_array($notEntryId) ? $notEntryId : array_slice(func_get_args(), 1);

        return $query->whereNotIn('channel_titles.entry_id', $notEntryIds);
    }

    /**
     * Filter out entries before the specified Entry ID
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  int                                   $entryIdFrom
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEntryIdFrom(Builder $query, $entryIdFrom)
    {
        return $query->where('channel_titles.entry_id', '>=', $entryIdFrom);
    }

    /**
     * Filter out entries after the specified Entry ID
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  int                                   $entryIdTo
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEntryIdTo(Builder $query, $entryIdTo)
    {
        return $query->where('channel_titles.entry_id', '<=', $entryIdTo);
    }

    /**
     * Filter by Member Group ID
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  dynamic  int                          $groupId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGroupId(Builder $query, $groupId)
    {
        $groupIds = is_array($groupId) ? $groupId : array_slice(func_get_args(), 1);

        return $this->requireTable($query, 'members')->whereIn('members.group_id', $groupIds);
    }

    /**
     * Filter by Not Member Group ID
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  dynamic  int                          $notGroupId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotGroupId(Builder $query, $notGroupId)
    {
        $notGroupIds = is_array($notGroupId) ? $notGroupId : array_slice(func_get_args(), 1);

        return $this->requireTable($query, 'members')->whereNotIn('members.group_id', $notGroupIds);
    }

    /**
     * Limit the number of results
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  int                                   $limit
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeLimit(Builder $query, $limit)
    {
        return $query->take($limit);
    }

    /**
     * Offset the results
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  int                                   $offset
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeOffset(Builder $query, $offset)
    {
        return $query->skip($offset);
    }

    /**
     * Filter by Page
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  bool|string                           $showPages
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeShowPages(Builder $query, $showPages = true)
    {
        if (! $showPages) {
            $args = static::getSiteRepository()->getPageEntryIds();

            array_unshift($args, $query);

            call_user_func_array(array($this, 'scopeNotEntryId'), $args);
        }

        return $query;
    }

    /**
     * Filter by Pages Only
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  bool|string                           $showPagesOnly
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeShowPagesOnly(Builder $query, $showPagesOnly = true)
    {
        if ($showPagesOnly) {
            $args = static::getSiteRepository()->getPageEntryIds();

            array_unshift($args, $query);

            call_user_func_array(array($this, 'scopeEntryId'), $args);
        }

        return $query;
    }

    /**
     * Filter out entries before the specified date
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  int|DateTime                          $startOn unix time
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStartOn(Builder $query, $startOn)
    {
        if ($startOn instanceof DateTime) {
            $startOn = $startOn->format('U');
        }

        return $query->where('channel_titles.entry_date', '>=', $startOn);
    }

    /**
     * Filter by Entry Status
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  dynamic  string                       $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatus(Builder $query, $status)
    {
        $statuses = is_array($status) ? $status : array_slice(func_get_args(), 1);

        return $query->whereIn('channel_titles.status', $statuses);
    }

    /**
     * Filter by Entry Status
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  dynamic  string                       $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeNotStatus(Builder $query, $status)
    {
        $statuses = is_array($status) ? $status : array_slice(func_get_args(), 1);

        return $query->whereNotIn('channel_titles.status', $statuses);
    }

    /**
     * Filter out entries after the specified date
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  int|DateTime                          $stopBefore unix time
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStopBefore(Builder $query, $stopBefore)
    {
        if ($stopBefore instanceof DateTime) {
            $stopBefore = $stopBefore->format('U');
        }

        return $query->where('channel_titles.entry_date', '<', $stopBefore);
    }

    /**
     * Filter by URL Title
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  dynamic  string                       $urlTitle
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUrlTitle(Builder $query, $urlTitle)
    {
        $urlTitles = is_array($urlTitle) ? $urlTitle : array_slice(func_get_args(), 1);

        return $query->whereIn('channel_titles.url_title', $urlTitles);
    }

    /**
     * Filter by Member Username
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  dynamic  string                       $username
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUsername(Builder $query, $username)
    {
        $usernames = is_array($username) ? $username : array_slice(func_get_args(), 1);

        return $this->requireTable($query, 'members')->whereIn('members.username', $usernames);
    }

    /**
     * Filter by Year
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  int                                   $year
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeYear(Builder $query, $year)
    {
        return $query->where('channel_titles.year', $year);
    }

    /**
     * Filter by Month
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string|int                            $month
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMonth(Builder $query, $month)
    {
        return $query->where('channel_titles.month', str_pad($month, 2, '0', STR_PAD_LEFT));
    }

    /**
     * Filter by Day
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string|int                            $day
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDay(Builder $query, $day)
    {
        return $query->where('channel_titles.day', str_pad($day, 2, '0', STR_PAD_LEFT));
    }

    /**
     * Call the specified scope, exploding a pipe-delimited string into an array
     * Calls the not version of the scope if the string begins with not
     * eg  'not 4|5|6'
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string                                $string ex '4|5|6' 'not 4|5|6'
     * @param string                                $scope  the name of the scope, ex. AuthorId
     */
    protected function scopeArrayFromString(Builder $query, $string, $scope)
    {
        if ($not = strncmp($string, 'not ', 4) === 0) {
            $string = substr($string, 4);
        }

        $args = explode('|', $string);

        $method = 'scope'.$scope;

        if ($not && method_exists($this, 'scopeNot'.$scope)) {
            $method = 'scopeNot'.$scope;
        }

        array_unshift($args, $query);

        return call_user_func_array(array($this, $method), $args);
    }

    /**
     * Filter by Author ID string parameter
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string                                $string
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeAuthorIdString(Builder $query, $string)
    {
        return $this->scopeArrayFromString($query, $string, 'AuthorId');
    }

    /**
     * Filter by Category string parameter
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string                                $string
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCategoryString(Builder $query, $string)
    {
        if ($not = strncmp($string, 'not ', 4) === 0) {
            $string = substr($string, 4);
        }

        $type = strpos($string, '&') !== false ? '&' : '|';

        $args = explode($type, $string);

        if ($type === '&') {
            $method = $not ? 'scopeNotAllCategories' : 'scopeAllCategories';
        } else {
            $method = $not ? 'scopeNotCategory' : 'scopeCategory';
        }

        array_unshift($args, $query);

        return call_user_func_array(array($this, $method), $args);
    }

    /**
     * Filter by Category Group string parameter
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string                                $string
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCategoryGroupString(Builder $query, $string)
    {
        return $this->scopeArrayFromString($query, $string, 'CategoryGroup');
    }

    /**
     * Filter by Category Name string parameter
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string                                $string
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeCategoryNameString(Builder $query, $string)
    {
        return $this->scopeArrayFromString($query, $string, 'CategoryName');
    }

    /**
     * Filter by Channel string parameter
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string                                $string
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeChannelString(Builder $query, $string)
    {
        return $this->scopeArrayFromString($query, $string, 'Channel');
    }

    /**
     * Filter by Entry ID string parameter
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string                                $string
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeEntryIdString(Builder $query, $string)
    {
        return $this->scopeArrayFromString($query, $string, 'EntryId');
    }

    /**
     * Filter by Fixed Order string parameter
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string                                $string
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeFixedOrderString(Builder $query, $string)
    {
        return $this->scopeArrayFromString($query, $string, 'FixedOrder');
    }

    /**
     * Filter by Member Group ID string parameter
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string                                $string
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeGroupIdString(Builder $query, $string)
    {
        return $this->scopeArrayFromString($query, $string, 'GroupId');
    }

    /**
     * Filter by Show Expired string parameter
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string                                $string
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeShowExpiredString(Builder $query, $string)
    {
        return $this->scopeShowExpired($query, $string === 'yes');
    }

    /**
     * Filter by Show Future Entries string parameter
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string                                $string
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeShowFutureEntriesString(Builder $query, $string)
    {
        return $this->scopeShowFutureEntries($query, $string === 'yes');
    }

    /**
     * Filter by Show Pages string parameter
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string                                $string
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeShowPagesString(Builder $query, $string)
    {
        if ($string === 'only') {
            return $this->scopeShowPagesOnly($query);
        }

        return $this->scopeShowPages($query, $string === 'yes');
    }

    /**
     * Filter by Status string parameter
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string                                $string
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStatusString(Builder $query, $string)
    {
        return $this->scopeArrayFromString($query, $string, 'Status');
    }

    /**
     * Filter by Sticky string parameter
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string                                $string
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeStickyString(Builder $query, $string)
    {
        return $this->scopeSticky($query, $string === 'yes');
    }

    /**
     * Filter by URL Title string parameter
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string                                $string
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUrlTitleString(Builder $query, $string)
    {
        return $this->scopeArrayFromString($query, $string, 'UrlTitle');
    }

    /**
     * Filter by Username string parameter
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string                                $string
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeUsernameString(Builder $query, $string)
    {
        return $this->scopeArrayFromString($query, $string, 'Username');
    }

    /**
     * Eager load categories
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Closure|null                          $callback eager load constraint
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithCategories(Builder $query, Closure $callback = null)
    {
        $with = $callback ? array('categories' => $callback) : 'categories';

        return $query->with($with);
    }

    /**
     * Eager load categories with custom fields
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Closure|null                          $callback eager load constraint
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithCategoryFields(Builder $query, Closure $callback = null)
    {
        return $this->scopeWithCategories($query, function ($query) use ($callback) {
            if ($callback) {
                $callback($query);
            }

            return $query->withFields();
        });
    }

    /**
     * Eager load author
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Closure|null                          $callback eager load constraint
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAuthor(Builder $query, Closure $callback = null)
    {
        $with = $callback ? array('author' => $callback) : 'author';

        return $query->with($with);
    }

    /**
     * Eager load author with custom fields
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Closure|null                          $callback eager load constraint
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithAuthorFields(Builder $query, Closure $callback = null)
    {
        return $this->scopeWithAuthor($query, function ($query) use ($callback) {
            if ($callback) {
                $callback($query);
            }

            return $query->withFields();
        });
    }

    /**
     * Eager load author
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  Closure|null                          $callback eager load constraint
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithComments(Builder $query, Closure $callback = null)
    {
        return $query->with(array('comments' => function ($query) use ($callback) {
            if ($callback) {
                $callback($query);
            }

            return $query->with('author');
        }));
    }

    /**
     * Dynamically apply scopes
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  array                                 $allowedParameters list of keys to pull from $request
     * @param  array                                 $request           array of request variables, for instance $_REQUEST
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeDynamicParameters(Builder $query, array $allowedParameters, array $request)
    {
        foreach ($allowedParameters as $key) {
            if (isset($request[$key])) {
                $this->scopeTagparam($query, $key, $request[$key]);
            }
        }

        return $query;
    }

    /**
     * Hydrate the parents property
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithParents(Builder $query)
    {
        $this->extraHydrators[] = 'parents';

        return $query;
    }

    /**
     * Hydrate the siblings property
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithSiblings(Builder $query)
    {
        $this->extraHydrators[] = 'siblings';

        return $query;
    }

    /**
     * Apply a single parameter
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  string                                $key   snake_cased parameter name
     * @param  string                                $value scope parameters in string form, eg. 1|2|3
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTagparam(Builder $query, $key, $value)
    {
        /**
         * A map of parameter names => model scopes
         * @var array
         */
        static $parameterMap = array(
            'author_id' => 'authorIdString',
            'cat_limit' => 'catLimit',
            'category' => 'categoryString',
            'category_name' => 'categoryNameString',
            'category_group' => 'categoryGroupString',
            'channel' => 'channelString',
            'entry_id' => 'entryIdString',
            'entry_id_from' => 'entryIdFrom',
            'entry_id_fo' => 'entryIdTo',
            'fixed_order' => 'fixedOrderString',
            'group_id' => 'groupIdString',
            'limit' => 'limit',
            'offset' => 'offset',
            'show_expired' => 'showExpiredString',
            'show_future_entries' => 'showFutureEntriesString',
            'show_pages' => 'showPagesString',
            'start_day' => 'startDay',
            'start_on' => 'startOn',
            'status' => 'statusString',
            'sticky' => 'stickyString',
            'stop_before' => 'stopBefore',
            //'uncategorized_entries' => 'uncategorizedEntries',//bool
            'url_title' => 'urlTitleString',
            'username' => 'usernameString',
            'year' => 'year',
            'month' => 'month',
            'day' => 'day',
        );

        if (! array_key_exists($key, $parameterMap)) {
            return $query;
        }

        $method = 'scope'.ucfirst($parameterMap[$key]);

        return $this->$method($query, $value);
    }

    /**
     * Apply an array of parameters
     *
     * @param  \Illuminate\Database\Eloquent\Builder $query
     * @param  array                                 $parameters
     * @param  array                                 $request    array of request variables, for instance $_REQUEST
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeTagparams(Builder $query, array $parameters, array $request = array())
    {
        // because you're so special
        if (! empty($parameters['orderby'])) {
            $directions = isset($parameters['sort']) ? explode('|', $parameters['sort']) : null;

            foreach (explode('|', $parameters['orderby']) as $i => $column) {
                $direction = isset($directions[$i]) ? $directions[$i] : 'asc';
                $query->orderBy($column, $direction);
            }
        }

        if (isset($parameters['dynamic_parameters'])) {
            $this->scopeDynamicParameters(
                $query,
                explode('|', $parameters['dynamic_parameters']),
                $request
            );
        }

        foreach ($parameters as $key => $value) {
            $this->scopeTagparam($query, $key, $value);
        }

        return $query;
    }

    /**
     * {@inheritdoc}
     */
    public function getPrefix()
    {
        return 'entry';
    }

    /**
     * Create a new instance of a ChannelData model
     * @return \rsanchez\Deep\Model\ChannelData
     */
    protected function newChannelData()
    {
        $channelData = new ChannelData();

        if ($this->exists) {
            $channelData->exists = true;
            $channelData->entry_id = $this->entry_id;
            $channelData->channel_id = $this->channel_id;
            $channelData->site_id = $this->site_id;
        }

        return $channelData;
    }

    /**
     * {@inheritdoc}
     */
    public function getCustomFieldAttributes()
    {
        return $this->channelData ? $this->channelData->getAttributes() : [];
    }

    /**
     * {@inheritdoc}
     */
    public function setCustomFieldAttribute($key, $value)
    {
        if (is_null($this->channelData)) {
            $this->channelData = $this->newChannelData();
        }

        return $this->channelData->$key = $value;
    }

    /**
     * {@inheritdoc}
     */
    public function hasCustomFieldAttribute($key)
    {
        return $this->channelData && array_key_exists($key, $this->channelData->getAttributes());
    }

    /**
     * Dehydrate all custom fields and save to the channel_data table
     *
     * @param  bool $isNew
     * @return void
     */
    protected function saveCustomFields($isNew)
    {
        // it wasn't fetched from DB
        if (is_null($this->channelData)) {
            $this->channelData = $this->newChannelData();
        }

        $this->channelData->exists = ! $isNew;
        $this->channelData->entry_id = $this->entry_id;
        $this->channelData->channel_id = $this->channel_id;
        $this->channelData->site_id = $this->site_id;

        foreach ($this->getProperties() as $field) {
            $name = $field->getName();
            $identifier = $field->getIdentifier();

            $dehydrator = $this->dehydrators->get($field->getType());

            if ($dehydrator) {
                $this->channelData->$identifier = $dehydrator->dehydrate($this, $field);
            } elseif (array_key_exists($name, $this->customFields) && $this->isDataScalar($this->customFields[$name])) {
                $this->channelData->$identifier = $this->dataToScalar($this->customFields[$name]);
            } elseif (! $this->channelData->hasAttribute($identifier)) {
                $this->channelData->$identifier = null;
            }

            if ($field->getType() !== 'date' && ! $this->channelData->hasAttribute('field_ft_'.$field->getId())) {
                 $this->channelData->{'field_ft_'.$field->getId()} = 'none';
            }
        }

        $this->channelData->save();
    }
}
