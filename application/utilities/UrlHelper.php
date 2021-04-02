<?php

class UrlHelper
{
    public static function isStaticContentProvider(string $controller, string $action): bool
    {
        /* @todo @refactor*/
        if (in_array($controller, array_merge(CONF_STATIC_FILE_CONTROLLERS, ['js-css', 'image']))) {
            return true;
        }

        $arr = [
            'teacher-lessons-plan' => [
                'lesson-plan-file',
                'lesson-plan-image',
            ],
            'teacher-courses' => [
                'teacher-course-image'
            ],
            'my-app' => [
                'pwa-manifest'
            ]
        ];

        return array_key_exists($controller, $arr) && in_array($action, $arr[$controller]);
    }
}
