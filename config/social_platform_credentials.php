<?php

/**
 * Maps SocialMediaPlatform slug to credential fields for CRUD forms and to autopost config keys.
 * Used for hamzahassanm/laravel-social-auto-post integration.
 */
return [
    'facebook' => [
        ['key' => 'access_token', 'config' => 'facebook_access_token', 'label' => 'Access Token', 'type' => 'password'],
        ['key' => 'page_id', 'config' => 'facebook_page_id', 'label' => 'Page ID', 'type' => 'text'],
        ['key' => 'api_version', 'config' => 'facebook_api_version', 'label' => 'API Version (optional)', 'type' => 'text'],
    ],
    'twitter' => [
        ['key' => 'bearer_token', 'config' => 'twitter_bearer_token', 'label' => 'Bearer Token', 'type' => 'password'],
        ['key' => 'api_key', 'config' => 'twitter_api_key', 'label' => 'API Key', 'type' => 'text'],
        ['key' => 'api_secret', 'config' => 'twitter_api_secret', 'label' => 'API Secret', 'type' => 'password'],
        ['key' => 'access_token', 'config' => 'twitter_access_token', 'label' => 'Access Token', 'type' => 'password'],
        ['key' => 'access_token_secret', 'config' => 'twitter_access_token_secret', 'label' => 'Access Token Secret', 'type' => 'password'],
    ],
    'linkedin' => [
        ['key' => 'access_token', 'config' => 'linkedin_access_token', 'label' => 'Access Token', 'type' => 'password'],
        ['key' => 'person_urn', 'config' => 'linkedin_person_urn', 'label' => 'Person URN', 'type' => 'text'],
        ['key' => 'organization_urn', 'config' => 'linkedin_organization_urn', 'label' => 'Organization URN (optional)', 'type' => 'text'],
    ],
    'instagram' => [
        ['key' => 'access_token', 'config' => 'instagram_access_token', 'label' => 'Access Token', 'type' => 'password'],
        ['key' => 'account_id', 'config' => 'instagram_account_id', 'label' => 'Account ID', 'type' => 'text'],
        ['key' => 'facebook_page_id', 'config' => 'facebook_page_id', 'label' => 'Facebook Page ID (required for Instagram)', 'type' => 'text'],
    ],
    'tiktok' => [
        ['key' => 'access_token', 'config' => 'tiktok_access_token', 'label' => 'Access Token', 'type' => 'password'],
        ['key' => 'client_key', 'config' => 'tiktok_client_key', 'label' => 'Client Key', 'type' => 'text'],
        ['key' => 'client_secret', 'config' => 'tiktok_client_secret', 'label' => 'Client Secret', 'type' => 'password'],
    ],
    'youtube' => [
        ['key' => 'api_key', 'config' => 'youtube_api_key', 'label' => 'API Key', 'type' => 'password'],
        ['key' => 'access_token', 'config' => 'youtube_access_token', 'label' => 'Access Token', 'type' => 'password'],
        ['key' => 'channel_id', 'config' => 'youtube_channel_id', 'label' => 'Channel ID', 'type' => 'text'],
    ],
    'pinterest' => [
        ['key' => 'access_token', 'config' => 'pinterest_access_token', 'label' => 'Access Token', 'type' => 'password'],
        ['key' => 'board_id', 'config' => 'pinterest_board_id', 'label' => 'Board ID', 'type' => 'text'],
    ],
    'telegram' => [
        ['key' => 'bot_token', 'config' => 'telegram_bot_token', 'label' => 'Bot Token', 'type' => 'password'],
        ['key' => 'chat_id', 'config' => 'telegram_chat_id', 'label' => 'Chat ID', 'type' => 'text'],
    ],
];
