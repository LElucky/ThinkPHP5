<?php
namespace app\admin\validate;
use think\Validate;
class VideotypeCheckedValidate extends Validate
{
    protected $rule =   [
        'url'       => 'require|url|unique:video_type',
        'title'     => 'require|max:50|unique:video_type',
        'count'     => 'number'
    ];

    protected $message  =   [
        'url.require'    => 'url必填',
        'title.require'  => '标题必填',
        'url.url'       => 'URL格式错误',
        'url.unique'    => 'URL已存在',
        'title.max'     => '标题最长不得超过50个字符',
        'title.unique'  => '标题不得重复',
        'count.number'  => '总数必须为数字'
    ];

    protected $scene    = [
        'add'   => [    'url','title','count'],
        'edit'  => [    
            'url'   => 'url|unique:video_type',
            'title' =>'max:50|unique:video_type',
            'count' => 'number'
        ],
    ];
}
?>