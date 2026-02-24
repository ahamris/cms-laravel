<?php

namespace App\OpenApi\Schemas;

use OpenApi\Attributes as OA;

#[OA\Tag(name: 'Academy', description: 'Course content (categories, videos) and live sessions. All endpoints under /api/course.')]
#[OA\Schema(
    schema: 'CoursePresenter',
    title: 'Course presenter',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'name', type: 'string'),
        new OA\Property(property: 'avatar', type: 'string', format: 'uri', nullable: true),
        new OA\Property(property: 'sort_order', type: 'integer'),
    ]
)]
#[OA\Schema(
    schema: 'CourseStats',
    title: 'Course stats',
    properties: [
        new OA\Property(property: 'video_count', type: 'integer', example: 42),
        new OA\Property(property: 'total_duration_seconds', type: 'integer', example: 36000),
        new OA\Property(property: 'hero_duration', type: 'string', nullable: true, example: '10 hr 0 min'),
    ]
)]
#[OA\Schema(
    schema: 'CourseVideoListItem',
    title: 'Course video list item',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'title', type: 'string'),
        new OA\Property(property: 'slug', type: 'string'),
        new OA\Property(property: 'description', type: 'string', nullable: true),
        new OA\Property(property: 'thumbnail_url', type: 'string', format: 'uri', nullable: true),
        new OA\Property(property: 'duration_seconds', type: 'integer', nullable: true),
        new OA\Property(property: 'duration_formatted', type: 'string', nullable: true, example: '5:30'),
        new OA\Property(property: 'video_provider', type: 'string', example: 'youtube', description: 'youtube, vimeo, local, or unknown'),
        new OA\Property(property: 'sort_order', type: 'integer'),
        new OA\Property(property: 'url', type: 'string', format: 'uri'),
        new OA\Property(property: 'category', type: 'object', nullable: true, properties: [
            new OA\Property(property: 'id', type: 'integer'),
            new OA\Property(property: 'name', type: 'string'),
            new OA\Property(property: 'slug', type: 'string'),
            new OA\Property(property: 'url', type: 'string', format: 'uri'),
        ]),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
    ]
)]
#[OA\Schema(
    schema: 'CourseVideo',
    title: 'Course video',
    description: 'Single course video with category and chapter',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'title', type: 'string'),
        new OA\Property(property: 'slug', type: 'string'),
        new OA\Property(property: 'description', type: 'string', nullable: true),
        new OA\Property(property: 'video_source_url', type: 'string', format: 'uri', nullable: true),
        new OA\Property(property: 'video_provider', type: 'string', example: 'youtube'),
        new OA\Property(property: 'video_id', type: 'string', nullable: true),
        new OA\Property(property: 'thumbnail_url', type: 'string', format: 'uri', nullable: true),
        new OA\Property(property: 'duration_seconds', type: 'integer', nullable: true),
        new OA\Property(property: 'duration_formatted', type: 'string', nullable: true),
        new OA\Property(property: 'sort_order', type: 'integer'),
        new OA\Property(property: 'url', type: 'string', format: 'uri'),
        new OA\Property(property: 'template', type: 'string', description: 'Frontend template hint (e.g. course-video-detail)'),
        new OA\Property(property: 'category', type: 'object', nullable: true, properties: [
            new OA\Property(property: 'id', type: 'integer'),
            new OA\Property(property: 'name', type: 'string'),
            new OA\Property(property: 'slug', type: 'string'),
            new OA\Property(property: 'url', type: 'string', format: 'uri'),
        ]),
        new OA\Property(property: 'chapter', type: 'object', nullable: true, properties: [
            new OA\Property(property: 'id', type: 'integer'),
            new OA\Property(property: 'name', type: 'string'),
            new OA\Property(property: 'sort_order', type: 'integer'),
        ]),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', nullable: true),
    ]
)]
#[OA\Schema(
    schema: 'CourseCategoryListItem',
    title: 'Course category list item',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'name', type: 'string'),
        new OA\Property(property: 'slug', type: 'string'),
        new OA\Property(property: 'description', type: 'string', nullable: true),
        new OA\Property(property: 'image_url', type: 'string', format: 'uri', nullable: true),
        new OA\Property(property: 'sort_order', type: 'integer'),
        new OA\Property(property: 'url', type: 'string', format: 'uri'),
        new OA\Property(property: 'videos_count', type: 'integer', nullable: true),
        new OA\Property(property: 'videos_duration_seconds', type: 'integer', nullable: true),
        new OA\Property(property: 'videos', type: 'array', items: new OA\Items(ref: '#/components/schemas/CourseVideoListItem')),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
    ]
)]
#[OA\Schema(
    schema: 'CourseChapter',
    title: 'Course chapter',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'name', type: 'string'),
        new OA\Property(property: 'description', type: 'string', nullable: true),
        new OA\Property(property: 'sort_order', type: 'integer'),
        new OA\Property(property: 'videos', type: 'array', items: new OA\Items(ref: '#/components/schemas/CourseVideoListItem')),
    ]
)]
#[OA\Schema(
    schema: 'CourseCategory',
    title: 'Course category',
    description: 'Single category with chapters and videos',
    properties: [
        new OA\Property(property: 'id', type: 'integer'),
        new OA\Property(property: 'name', type: 'string'),
        new OA\Property(property: 'slug', type: 'string'),
        new OA\Property(property: 'description', type: 'string', nullable: true),
        new OA\Property(property: 'image_url', type: 'string', format: 'uri', nullable: true),
        new OA\Property(property: 'sort_order', type: 'integer'),
        new OA\Property(property: 'url', type: 'string', format: 'uri'),
        new OA\Property(property: 'template', type: 'string', description: 'Frontend template hint (e.g. course-category-detail)'),
        new OA\Property(property: 'chapters', type: 'array', items: new OA\Items(ref: '#/components/schemas/CourseChapter')),
        new OA\Property(property: 'videos', type: 'array', items: new OA\Items(ref: '#/components/schemas/CourseVideoListItem')),
        new OA\Property(property: 'videos_count', type: 'integer', nullable: true),
        new OA\Property(property: 'created_at', type: 'string', format: 'date-time', nullable: true),
        new OA\Property(property: 'updated_at', type: 'string', format: 'date-time', nullable: true),
    ]
)]
#[OA\Schema(
    schema: 'CourseIndexData',
    title: 'Course index data',
    properties: [
        new OA\Property(property: 'featured_session', type: 'object', nullable: true, description: 'Featured live session or null'),
        new OA\Property(property: 'upcoming_sessions', type: 'array', items: new OA\Items(type: 'object'), description: 'Upcoming live sessions'),
        new OA\Property(property: 'recent_videos', type: 'array', items: new OA\Items(ref: '#/components/schemas/CourseVideoListItem')),
        new OA\Property(property: 'presenters', type: 'array', items: new OA\Items(ref: '#/components/schemas/CoursePresenter')),
        new OA\Property(property: 'categories', type: 'array', items: new OA\Items(ref: '#/components/schemas/CourseCategoryListItem')),
        new OA\Property(property: 'search_query', type: 'string'),
        new OA\Property(property: 'stats', ref: '#/components/schemas/CourseStats'),
    ]
)]
#[OA\Schema(
    schema: 'CourseIndexResponse',
    title: 'Course index response',
    properties: [
        new OA\Property(property: 'template', type: 'string', description: 'Frontend template hint (e.g. academy)'),
        new OA\Property(property: 'data', ref: '#/components/schemas/CourseIndexData'),
    ]
)]
#[OA\Schema(
    schema: 'CourseVideoResponse',
    title: 'Course video response',
    properties: [
        new OA\Property(property: 'template', type: 'string', description: 'Frontend template hint (e.g. course-video-detail)'),
        new OA\Property(property: 'data', ref: '#/components/schemas/CourseVideo'),
        new OA\Property(property: 'related_videos', type: 'array', items: new OA\Items(ref: '#/components/schemas/CourseVideoListItem')),
    ]
)]
class CourseSchema
{
}
