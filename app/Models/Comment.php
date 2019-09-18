<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;

class Comment extends BaseModel
{
    use SoftDeletes;

    // 评论类别
    const COMMENT_TYPE_ARTICLE = 1;
    const COMMENT_TYPE_SITE = 2;

    protected $table = 'comments';

    protected $fillable = [
        'nickname',
        'email',
        'content',
        'target',
        'reply_comment_id',
        'comment_type',
        'ip',
        'thumb',
    ];

    /**
     * @var array
     */
    public static $commentType = [
        self::COMMENT_TYPE_ARTICLE => '文章评论',
        self::COMMENT_TYPE_SITE => '站内留言',
    ];

    /**
     * @param array ...$arguments
     * @return mixed
     */
    public static function getModelData(...$arguments)
    {
        self::modelBuild(Comment::class);
        self::$instance->argumentParse($arguments);
        return self::$instance->modelProcess();
    }

    /**
     * @return mixed
     */
    public static function modelProcess()
    {
        self::$instance->query = Comment::orderBy('comments.updated_at', 'desc');
        self::$instance->filterAndPagination();
        return self::$instance->query->get();
    }

    /**
     * @param $value
     */
    public function nicknameFilter($value)
    {
        $this->query->where('nickname', 'like', '%' . $value . '%');
    }

    /**
     * @param $value
     */
    public function emailFilter($value)
    {
        $this->query->where('email', 'like', '%' . $value . '%');
    }

    /**
     * @param $value
     */
    public function commentTypeFilter($value)
    {
        $this->query->where('comment_type', $value);
    }
}
